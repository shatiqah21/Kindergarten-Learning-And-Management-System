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
            'teacher_id' => 'required',
            'class_id'   => 'required',
            'subject_id' => 'required',
            'day'        => 'required',
            'time_start' => 'required',
            'time_end'   => 'required|after:time_start',
        ]);

        // 🔴 Check overlapping slot
        $conflict = DB::table('teacher_timetables')
            ->where('teacher_id', $request->teacher_id)
            ->where('day', $request->day)
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

        // ✅ Save timetable
        DB::table('teacher_timetables')->insert([
            'teacher_id' => $request->teacher_id,
            'class_id'   => $request->class_id,
            'subject_id' => $request->subject_id,
            'day'        => $request->day,
            'time_start' => $request->time_start,
            'time_end'   => $request->time_end,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('timetables.teacher.index')
            ->with('success', 'Timetable created successfully.');
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

    public function downloadPdf()
    {
        $timetables = TeacherTimetable::with(['teacher','class','subject'])
            ->get()
            ->groupBy(['day','time_start']);

        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday'];
        $time_slots = ['08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00'];

        $pdf = Pdf::loadView('pages.support_team.timetables.teacher.pdf', compact('timetables','days','time_slots'));

        return $pdf->download('teacher_timetable.pdf');
    }

    public function teacherView()
    {
        $teacherId = Auth::id();

        $timetables = TeacherTimetable::with(['class','subject'])
                        ->where('teacher_id', $teacherId)
                        ->get();

        $classes = MyClass::whereHas('subjects', function($q) use ($teacherId){
            $q->where('teacher_id', $teacherId);
        })->get();

        $subjects = Subject::where('teacher_id', $teacherId)->get();

        $days = $timetables->pluck('day')->unique();
        $time_slots = $timetables->pluck('time_start')->unique();

        $grid = [];
        foreach($timetables as $entry){
            $grid[$entry->day][$entry->time_start][] = $entry;
        }

        return view('pages.support_team.timetables.teacher.view', compact('classes','subjects','days','time_slots','grid'));
    }
}
