<?php

namespace App\Http\Controllers\MyParent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\StudentRecord;
use App\Repositories\StudentRepo;
use Illuminate\Support\Facades\Auth;
use App\Models\Exam;
use App\Models\Event;
use App\Models\TimeTable;
use App\Models\LearningMaterial;
use App\Models\Payment;
use App\Models\PaymentDetails;
use App\Models\TimeTableRecord;
use App\Models\Mark;
use PDF;
use App\Helpers\Qs;


class MyController extends Controller
{
    protected $student;
    public function __construct(StudentRepo $student)
    {
        $this->student = $student;
    }

    public function children()
    {
        $data['students'] = StudentRecord::with(['user', 'my_class', 'section'])
            ->where('my_parent_id', Auth::user()->id)
            ->get();

        return view('pages.parent.children', $data);
    }


     public function timetable()
    {
        $parent = Auth::user(); // ambil parent sekarang

        // Ambil semua class anak parent
        $class_ids = [];
        if($parent->children && $parent->children->count() > 0){
            $class_ids = $parent->children->pluck('my_class_id')->toArray();
        }

        // Ambil timetable anak parent sahaja
        $allTimetables = [];
        if(!empty($class_ids)){
            $allTimetables = TimeTable::with([
                    'time_slot', 
                    'tt_record.my_class', 
                    'subject'
                ])
                ->whereHas('tt_record', function($q) use ($class_ids) {
                    $q->whereIn('my_class_id', $class_ids);
                })
                ->get();
        }

        // Hari dan time slots unik
        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday'];
        $time_slots = $allTimetables->map(function($tt) {
                return $tt['time_slot']['start_time'] ?? $tt['timestamp_from'];
            })
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        // Susun grid: $timetables[day][time] = array of slots
        $timetables = [];
        foreach($allTimetables as $tt) {
            $day = $tt['day'] ?? 'Monday';
            $time = $tt['time_slot']['start_time'] ?? $tt['timestamp_from'] ?? '08:00';
            $timetables[$day][$time][] = $tt;
        }
        

        // **Pass $parent ke view supaya blade boleh loop anak**
        return view('pages.parent.timetable.index', compact('days','time_slots','timetables','parent'));
    }

    public function downloadPDF()
{
    $parent = Auth::user(); // ambil parent yang login

    // Ambil semua class anak parent
    $class_ids = [];
    if($parent->children && $parent->children->count() > 0){
        $class_ids = $parent->children->pluck('my_class_id')->toArray();
    }

    // Ambil timetable anak parent sahaja
    $allTimetables = [];
    if(!empty($class_ids)){
        $allTimetables = TimeTable::with([
                'time_slot', 
                'tt_record.my_class', 
                'subject'
            ])
            ->whereHas('tt_record', function($q) use ($class_ids) {
                $q->whereIn('my_class_id', $class_ids);
            })
            ->get();
    }

    // Hari dan masa
    $days = ['Monday','Tuesday','Wednesday','Thursday','Friday'];
    $time_slots = $allTimetables->map(function($tt) {
            return $tt['time_slot']['start_time'] ?? $tt['timestamp_from'];
        })
        ->filter()
        ->unique()
        ->sort()
        ->values()
        ->toArray();

    // Susun grid: $timetables[day][time] = array of slots
    $timetables = [];
    foreach($allTimetables as $tt) {
        $day = $tt['day'] ?? 'Monday';
        $time = $tt['time_slot']['start_time'] ?? $tt['timestamp_from'] ?? '08:00';
        $timetables[$day][$time][] = $tt;
    }

    // Generate PDF view
    $pdf = PDF::loadView('pages.parent.timetable.pdf', compact('days','time_slots','timetables','parent'))
            ->setPaper('A4', 'landscape');

    return $pdf->download('Child_Timetable.pdf');
}


    public function formatTime($timestamp)
    {
        return $timestamp ? date('g:i A', $timestamp) : '-';
    }




    public function materials()
    {
        $materials = LearningMaterial::all();
        return view('pages.parent.materials.index', compact('materials'));
    }

    public function events()
    {
        $events = Event::latest()->get();
        return view('pages.parent.events.index', compact('events'));
    }

    public function createPayment()
    {
        $parent = auth()->user();

        // Get children data for selection
        $children = StudentRecord::where('my_parent_id', $parent->id)->get();

        return view('pages.parent.payments.create', compact('children'));
    }


   public function storePayment(Request $request)
    {
        $request->validate([
            'children' => 'required|array',
            'payment_method' => 'required|in:cash,Credit/Debit Card',
        ]);

        $childrenIds = $request->children;
        $parent = auth()->user();

        DB::transaction(function() use ($childrenIds, $parent, $request) {

            // Example: sum fee from payments table for the children
            $totalAmount = Payment::whereIn('my_class_id', StudentRecord::whereIn('id', $childrenIds)->pluck('my_class_id'))->sum('amount');

            // Create parent payment
            $payment = Payment::create([
                'title' => 'School Fees',
                'amount' => $totalAmount,
                'method' => $request->payment_method,
                'my_parent_id' => $parent->id,
                'year' => now()->year,
                'ref_no' => 'PAY-' . strtoupper(uniqid()), // generate unique ref_no
            ]);


            // Create per-child payment details
            foreach ($childrenIds as $childId) {
                $childFee = Payment::where('my_class_id', StudentRecord::find($childId)->my_class_id)->value('amount');

                PaymentDetails::create([
                    'payment_id' => $payment->id,
                    'student_record_id' => $childId,
                    'amt_paid' => $childFee ?? 0,
                    'balance' => 0,
                    'year' => now()->year,
                ]);
            }
        });

        return redirect()->route('parent.payments.index')->with('success', 'Payment recorded successfully.');
    }

    public function showPayments()
    {
        $parent = auth()->user();

        // Get all children of this parent
        $children = StudentRecord::where('my_parent_id', $parent->id)->get();

        // Fetch all payments by this parent
        $payments = Payment::with('paymentDetails', 'paymentDetails.studentRecord')
                        ->where('my_parent_id', $parent->id)
                        ->get();

        return view('pages.parent.payments.index', compact('children', 'payments'));
    }

    public function checkout(Request $request)
    {
        $parent = auth()->user();
        $paymentId = $request->payment_id;

        $payment = Payment::findOrFail($paymentId);

        if($payment->method !== 'online'){
            return back()->with('error', 'Invalid payment method.');
        }

        // Example: redirect to gateway (pseudo-code)
        $gatewayData = [
            'amount' => $payment->amount,
            'order_id' => $payment->id,
            'return_url' => route('parent.payments.callback')
        ];

        // Redirect to gateway URL with $gatewayData
        return redirect()->away('https://example-payment-gateway.com/pay?'.http_build_query($gatewayData));
    }

    public function paymentCallback(Request $request)
    {
        // Gateway will return payment status and order_id
        $orderId = $request->input('order_id');
        $status = $request->input('status'); // 'success' or 'fail'

        $payment = Payment::findOrFail($orderId);

        if($status === 'success'){
            $payment->update(['status' => 'paid']);

            // Update child payment_details
            foreach($payment->details as $detail){
                $detail->update(['amt_paid' => $detail->amt_paid, 'balance' => 0, 'paid' => 1]);
            }

            return redirect()->route('parent.payments.index')->with('success', 'Payment successful.');
        }

        return redirect()->route('parent.payments.index')->with('error', 'Payment failed.');
    }





   public function exam()
    {
        $students = StudentRecord::with(['user', 'my_class', 'section'])
            ->where('my_parent_id', Auth::id())
            ->get();
         $data['students'] = StudentRecord::with(['user', 'my_class', 'section'])
            ->where('my_parent_id', Auth::user()->id)
            ->get();

        $exams = Exam::all();

        return view('pages.parent.exam.index', compact('students', 'exams'));
    }


   


    public function downloadMarksheet($student_id)
    {
        $parent = auth()->user();

        $student = StudentRecord::where('my_parent_id', $parent->id)
                    ->where('id', $student_id)
                    ->firstOrFail();

        $marks = Mark::with('subject', 'grade')
                    ->where('student_id', $student_id)
                    ->get();

        $pdf = PDF::loadView('pages.parent.exam.pdf_marksheet', compact('student', 'marks'))
                ->setPaper('A4', 'portrait');

        return $pdf->download('Marksheet_' . $student->name . '.pdf');
    }

    public function printReceipt($payment_id)
    {
        $parent = auth()->user();

        // Pastikan parent hanya boleh print payment dia sendiri
        $payment = Payment::with('paymentDetails.studentRecord.user')
                    ->where('my_parent_id', $parent->id)
                    ->where('id', $payment_id)
                    ->firstOrFail();

        // Generate PDF
        $pdf = \PDF::loadView('pages.parent.payments.receipt', compact('payment'))
                ->setPaper('A4', 'portrait');

        // Download or display
        return $pdf->stream('Receipt_' . $payment->ref_no . '.pdf');
    }

   

}