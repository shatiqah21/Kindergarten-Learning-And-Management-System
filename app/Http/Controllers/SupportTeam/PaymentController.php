<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Helpers\Pay;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\PaymentCreate;
use App\Http\Requests\Payment\PaymentUpdate;
use App\Models\Setting;
use App\Models\User;
use App\Repositories\MyClassRepo;
use App\Repositories\PaymentRepo;
use App\Repositories\StudentRepo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PDF;

class PaymentController extends Controller
{
    protected $my_class, $pay, $student, $year;

    public function __construct(MyClassRepo $my_class, PaymentRepo $pay, StudentRepo $student)
    {
        $this->my_class = $my_class;
        $this->pay = $pay;
        $this->year = Qs::getCurrentSession();
        $this->student = $student;

        $this->middleware('teamAccount');
    }

   public function index()
    {
        $d['selected'] = false;

        // Get all years that have payments
        $d['years'] = $this->pay->getPaymentYears();

        // Default year = current session/year
        $currentYear = Qs::getCurrentSession(); // or use now()->year if Qs not available
        $d['year'] = $currentYear;

        // If there are payments for current year, select it
        $paymentsForCurrentYear = $this->pay->getPayment(['year' => $currentYear])->get();
        if($paymentsForCurrentYear->count() > 0){
            $d['payments'] = $paymentsForCurrentYear;
            $d['selected'] = true;
        } else {
            $d['payments'] = collect(); // empty collection
        }

        // Load all classes for the tabs
        $d['my_classes'] = $this->my_class->all();

        return view('pages.support_team.payments.index', $d);
    }


    public function show($year = null)
    {
        // Use current session/year if none selected
        $year = $year ?? $this->year;

        $payments = $this->pay->getPayment(['year' => $year])->get();
        $my_classes = $this->my_class->all();
        $years = $this->pay->getPaymentYears();

        $selected = $payments->count() > 0;

        // If no payments for selected year, fall back to current session
        if (!$selected && $year != $this->year) {
            $year = $this->year;
            $payments = $this->pay->getPayment(['year' => $year])->get();
            $selected = $payments->count() > 0;
        }

        $message = $payments->count() < 1
            ? "No payments found for $year session."
            : null;

        return view('pages.support_team.payments.index', compact(
            'payments', 'my_classes', 'years', 'year', 'selected', 'message'
        ));
    }


    public function select_year(Request $req)
    {
        return Qs::goToRoute(['payments.show', $req->year]);
    }

    public function create()
    {
        $d['my_classes'] = $this->my_class->all();
        return view('pages.support_team.payments.create', $d);
    }

    public function invoice($st_id, $year = NULL)
    {
        if(!$st_id) {return Qs::goWithDanger();}

        $inv = $year ? $this->pay->getAllMyPR($st_id, $year) : $this->pay->getAllMyPR($st_id);

        $d['sr'] = $this->student->findByUserId($st_id)->first();
        $pr = $inv->get();
        $d['uncleared'] = $pr->where('paid', 0);
        $d['cleared'] = $pr->where('paid', 1);

        return view('pages.support_team.payments.invoice', $d);
    }

    public function receipts($pr_id)
    {
        if(!$pr_id) {return Qs::goWithDanger();}

        try {
            $d['pr'] = $pr = $this->pay->getRecord(['id' => $pr_id])->with('receipt')->first();
        } catch (ModelNotFoundException $ex) {
            return back()->with('flash_danger', __('msg.rnf'));
        }
        $d['receipts'] = $pr->receipt;
        $d['payment'] = $pr->payment;
        $d['sr'] = $this->student->findByUserId($pr->student_id)->first();
        $d['s'] = Setting::all()->flatMap(function($s){
            return [$s->type => $s->description];
        });

        return view('pages.support_team.payments.receipt', $d);
    }

    public function pdf_receipts($pr_id)
    {
        if(!$pr_id) {return Qs::goWithDanger();}

        try {
            $d['pr'] = $pr = $this->pay->getRecord(['id' => $pr_id])->with('receipt')->first();
        } catch (ModelNotFoundException $ex) {
            return back()->with('flash_danger', __('msg.rnf'));
        }
        $d['receipts'] = $pr->receipt;
        $d['payment'] = $pr->payment;
        $d['sr'] = $sr =$this->student->findByUserId($pr->student_id)->first();
        $d['s'] = Setting::all()->flatMap(function($s){
            return [$s->type => $s->description];
        });

        $pdf_name = 'Receipt_'.$pr->ref_no;

        return PDF::loadView('pages.support_team.payments.receipt', $d)->download($pdf_name);
    }

    protected function downloadReceipt($page, $data, $name = NULL){
        $path = 'receipts/file.html';
        $disk = Storage::disk('local');
        $disk->put($path, view($page, $data) );
        $html = $disk->get($path);
        return PDF::loadHTML($html)->download($name);
    }

    public function pay_now(Request $req, $pr_id)
    {
        $this->validate($req, [
            'amt_paid' => 'required|numeric'
        ], [], ['amt_paid' => 'Amount Paid']);

        $pr = $this->pay->findRecord($pr_id);
        $payment = $this->pay->find($pr->payment_id);
        $d['amt_paid'] = $amt_p = $pr->amt_paid + $req->amt_paid;
        $d['balance'] = $bal = $payment->amount - $amt_p;
        $d['paid'] = $bal < 1 ? 1 : 0;

        $this->pay->updateRecord($pr_id, $d);

        $d2['amt_paid'] = $req->amt_paid;
        $d2['balance'] = $bal;
        $d2['pr_id'] = $pr_id;
        $d2['year'] = $this->year;

        $this->pay->createReceipt($d2);
        return Qs::jsonUpdateOk();
    }

    public function manage($class_id = NULL)
    {
        $d['my_classes'] = $this->my_class->all();
        $d['selected'] = false;

        if($class_id){
            $d['students'] = $st = $this->student->getRecord(['my_class_id' => $class_id])->get()->sortBy('user.name');
            if($st->count() < 1){
                return Qs::goWithDanger('payments.manage');
            }
            $d['selected'] = true;
            $d['my_class_id'] = $class_id;
        }

        return view('pages.support_team.payments.manage', $d);
    }

    public function select_class(Request $req)
    {
        $this->validate($req, [
            'my_class_id' => 'required|exists:my_classes,id'
        ], [], ['my_class_id' => 'Class']);

        $wh['my_class_id'] = $class_id = $req->my_class_id;

        $pay1 = $this->pay->getPayment(['my_class_id' => $class_id, 'year' => $this->year])->get();
        $pay2 = $this->pay->getGeneralPayment(['year' => $this->year])->get();
        $payments = $pay2->count() ? $pay1->merge($pay2) : $pay1;
        $students = $this->student->getRecord($wh)->get();

        if($payments->count() && $students->count()){
            foreach($payments as $p){
                foreach($students as $st){
                    $pr['student_id'] = $st->user_id;
                    $pr['payment_id'] = $p->id;
                    $pr['year'] = $this->year;
                    $rec = $this->pay->createRecord($pr);
                    $rec->ref_no ?: $rec->update(['ref_no' => mt_rand(100000, 99999999)]);
                }
            }
        }

        return Qs::goToRoute(['payments.manage', $class_id]);
    }

    public function store(PaymentCreate $req)
    {
        $data = $req->all();
        $data['year'] = $this->year;
        $data['ref_no'] = Pay::genRefCode();

        // ✅ Set default status
        $data['status'] = 'active';

        $this->pay->create($data);

        return Qs::jsonStoreOk();
    }


    public function edit($id)
    {
        $d['payment'] = $pay = $this->pay->find($id);

        return is_null($pay) ? Qs::goWithDanger('payments.index') : view('pages.support_team.payments.edit', $d);
    }

    public function update(PaymentUpdate $req, $id)
    {
        $data = $req->all();
        $this->pay->update($id, $data);

        return Qs::jsonUpdateOk();
    }

    public function destroy($id)
    {
        $this->pay->find($id)->delete();

        return Qs::deleteOk('payments.index');
    }

    public function reset_record($id)
    {
        $pr['amt_paid'] = $pr['paid'] = $pr['balance'] = 0;
        $this->pay->updateRecord($id, $pr);
        $this->pay->deleteReceipts(['pr_id' => $id]);

        return back()->with('flash_success', __('msg.update_ok'));
    }

    /**
     * Stripe webhook terbaru (update parent payments & receipts)
     */
    public function stripeWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                config('services.stripe.webhook_secret')
            );
        } catch (\UnexpectedValueException $e) {
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }

        if ($event->type === 'payment_intent.succeeded') {
            $paymentIntent = $event->data->object;

            $parentId = $paymentIntent->metadata->parent_id ?? null;
            $classId = $paymentIntent->metadata->class_id ?? null;
            $amount = $paymentIntent->amount / 100;
            $refNo = $paymentIntent->id;

            if ($parentId) {
                $payments = $this->pay->getPayment([
                    'my_parent_id' => $parentId,
                    'my_class_id' => $classId,
                    'status' => 'pending'
                ])->get();

                foreach ($payments as $payment) {
                    $payment->update([
                        'status' => 'paid',
                        'stripe_payment_id' => $refNo
                    ]);

                    foreach ($payment->paymentDetails as $detail) {
                        $detail->amt_paid = $detail->balance = $detail->amount;
                        $detail->paid = 1;
                        $detail->save();

                        $receiptData = [
                            'pr_id' => $detail->id,
                            'amt_paid' => $detail->amt_paid,
                            'balance' => 0,
                            'year' => $this->year
                        ];
                        $this->pay->createReceipt($receiptData);
                    }

                    $parent = $payment->my_parent;
                    if ($parent) {
                        Mail::to($parent->email)
                            ->send(new \App\Mail\PaymentSuccess($payment));
                    }
                }
            }
        }

        return response('Webhook Handled', 200);
    }
}
