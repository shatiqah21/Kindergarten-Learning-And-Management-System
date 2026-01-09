<?php

namespace App\Http\Controllers\SupportTeam;

use App\Models\TeacherTimetable;
use App\Models\MyClass;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class TeacherTimetableController extends Controller
{
 public function index()
    {
       $timetables = TeacherTimetable::with(['teacher','class','subject'])
            ->get()
            ->groupBy([
                function($item) {
                    return $item->day;
                },
                function($item) {
                    return date('H:i', strtotime($item->time_start));
                }
            ]);

        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday'];
        $time_slots = ['08:00','09:00','10:00','11:00','12:00',
                    '13:00','14:00','15:00','16:00','17:00'];
        $my_classes = MyClass::all();

        return view('pages.support_team.timetables.teacher.index', compact(
            'timetables','days','time_slots','my_classes'
        ));
    }




   
  public function create(Request $request)
    {
        $teachers = User::where('user_type', 'teacher')->get();
        $classes = MyClass::all();

        // Detect selected teacher & class from GET parameters
        $selected_teacher = $request->teacher_id ?? old('teacher_id');
        $selected_class   = $request->class_id ?? old('class_id');

        // Filter subjects by teacher & class
        $subjects = collect();
        if ($selected_teacher && $selected_class) {
            $subjects = Subject::where('teacher_id', $selected_teacher)
                            ->where('my_class_id', $selected_class)
                            ->get();
        }

        // Define fixed time slots
        $time_slots = [
            '08:00', '09:00', '10:00', '11:00', '12:00',
            '13:00', '14:00', '15:00', '16:00', '17:00'
        ];

        return view('pages.support_team.timetables.teacher.create', compact(
            'teachers',
            'classes',
            'subjects',
            'selected_teacher',
            'selected_class',
            'time_slots'
        ));
    }





    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'class_id'   => 'required|exists:my_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'day'        => 'required',
            'time_start' => 'required',
            'time_end'   => 'required|after:time_start',
        ]);

        // ✅ Check overlap sebelum simpan
        $conflict = TeacherTimetable::where('teacher_id', $request->teacher_id)
            ->where('day', $request->day)
            ->where(function($q) use ($request){
                $q->where('time_start', '<', $request->time_end)
                ->where('time_end', '>', $request->time_start);
            })
            ->exists();

        if ($conflict) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'This teacher already has a class during this time.');
        }

        // ✅ Save timetable
        TeacherTimetable::create($request->only([
            'teacher_id','class_id','subject_id','day','time_start','time_end'
        ]));

        // Flash success
        return redirect()->back()->with('success', 'Timetable saved successfully.');
    }



    public function edit(TeacherTimetable $teacherTimetable)
    {
        $teachers = User::where('user_type', 'teacher')->get();
        $classes = MyClass::all();
        $subjects = Subject::all();

        return view('pages.support_team.timetables.teacher.edit', compact('teacherTimetable','teachers','classes','subjects'));
    }

    public function update(Request $request, TeacherTimetable $teacherTimetable)
    {
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'class_id'   => 'required|exists:my_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'day'        => 'required',
            'time_start' => 'required',
            'time_end'   => 'required|after:time_start',
        ]);

        // 🔴 Check overlapping slot (exclude current record)
        $conflict = DB::table('teacher_timetables')
            ->where('teacher_id', $request->teacher_id)
            ->where('day', $request->day)
            ->where('id', '!=', $teacherTimetable->id)
            ->where(function ($query) use ($request) {
                $query->where('time_start', '<', $request->time_end)
                    ->where('time_end', '>', $request->time_start);
            })
            ->exists();

        if ($conflict) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'time_start' => 'This teacher already has a class during this time.'
                ]);
        }

        $teacherTimetable->update($request->only([
            'teacher_id',
            'class_id',
            'subject_id',
            'day',
            'time_start',
            'time_end',
        ]));

        return redirect()->route('teacher_timetables.index')
            ->with('success', 'Timetable updated!');
    }


    public function destroy(TeacherTimetable $teacherTimetable)
    {
        $teacherTimetable->delete();

        return redirect()->route('teacher_timetables.index')->with('success', 'Timetable deleted!');
    }

   public function admindownloadPdf()
    {
        $timetables = TeacherTimetable::with(['teacher','class','subject'])
            ->get()
            ->map(function($item){
                $item->day_key  = ucfirst(strtolower($item->day));      // Monday, Tuesday...
                $item->time_key = date('H:i', strtotime($item->time_start)); // 08:00, 09:00...
                return $item;
            })
            ->groupBy(['day_key','time_key']); // now keys match Blade

        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday'];
        $time_slots = ['08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00'];

        $pdf = PDF::loadView('pages.support_team.timetables.teacher.pdf', compact('timetables','days','time_slots'));

        return $pdf->download('teacher_timetable.pdf');
    }

    public function teacherDownloadPdf()
    {
        $teacherId = Auth::id();

        $timetables = TeacherTimetable::with(['class','subject'])
            ->where('teacher_id', $teacherId)
            ->get()
            ->map(function($item){
                $item->day_key  = ucfirst(strtolower($item->day));
                $item->time_key = date('H:i', strtotime($item->time_start));
                return $item;
            })
            ->groupBy(['day_key','time_key']);

        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday'];
        $time_slots = ['08:00','09:00','10:00','11:00','12:00',
                    '13:00','14:00','15:00','16:00','17:00'];

        $pdf = PDF::loadView('pages.support_team.timetables.teacher.pdf', compact('timetables','days','time_slots'));

        return $pdf->download('my_timetable.pdf');
    }


    public function teacherView()
    {
        $teacherId = Auth::id();

        // Ambil semua timetable teacher ni
        $timetables = TeacherTimetable::with(['class','subject'])
            ->where('teacher_id', $teacherId)
            ->get()
            ->sortBy('time_start');

        // Fixed days & time slots untuk table header
        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday'];
        $time_slots = ['08:00','09:00','10:00','11:00','12:00',
                    '13:00','14:00','15:00','16:00','17:00'];

        // Build grid [day][time_start] = array of entries
        $grid = [];
        foreach($timetables as $entry){
            // Normalize day & time
            $day_key = ucfirst(strtolower($entry->day));
            $time_key = date('H:i', strtotime($entry->time_start));

            $grid[$day_key][$time_key][] = $entry;
        }

        // Current day for highlight
        $current_day = date('l'); // Monday, Tuesday...

        return view('pages.support_team.timetables.teacher.view', compact(
            'days', 'time_slots', 'grid', 'current_day'
        ));
    }

}
