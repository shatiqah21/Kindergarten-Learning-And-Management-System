<?php

namespace App\Http\Controllers\SupportTeam;

use App\Models\Attendance;
use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Helpers\Qs;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MonthlyAttendanceExport;
;
use Carbon\Carbon;
use PDF;

class AttendanceController extends Controller
{
  public function index(Request $request, $section_id)
    {
        $section = Section::with('students.user')->findOrFail($section_id);
        $canManage = auth()->id() == $section->teacher_id;

        // ✅ Tarikh dipilih user atau default hari ini
        $selectedDate = $request->date ?? now()->toDateString();

        foreach ($section->students as $student) {
            $attendance = Attendance::where('student_id', $student->id)
                ->where('section_id', $section_id)
                ->whereDate('date', $selectedDate)
                ->first();

            $student->attendanceToday = $attendance->status ?? null;
        }

        $sections = [];
        if (Qs::userIsTeamSA()) {
            $sections = Section::all();
        }

        return view('pages.support_team.attendance.index', [
            'section' => $section,
            'students' => $section->students,
            'canManage' => $canManage,
            'sections' => $sections,
            'selectedDate' => $selectedDate, // ✅ Hantar ke view
        ]);
    }



    public function store(Request $request, $section_id)
    {
        $section = Section::findOrFail($section_id);

        // hanya teacher yang assign section boleh manage
        if (auth()->id() != $section->teacher_id) {
            return back()->with('error', 'You are not allowed to update this section attendance.');
        }

        $today = now()->toDateString();

        foreach ($request->attendance as $student_id => $status) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $student_id,
                    'section_id' => $section_id,
                    'date'       => $today,
                ],
                [
                    'status'     => $status,
                    'marked_by'  => auth()->id(),
                ]
            );
        }

        return back()->with('success', 'Attendance saved successfully!');
    }

   public function showSectionAttendance(Request $request)
    {
        $user = auth()->user();
        $canManage = false;

        if(Qs::userIsTeamSA()) {
            $sections = Section::with('students.user')->get();

            $section_id = $request->section_id ?? $sections->first()->id ?? null;
            $section = Section::with('students.user')->findOrFail($section_id);
            $students = $section->students;

            $canManage = false;

            return view('pages.support_team.attendance.index', compact('sections', 'section', 'students', 'canManage'));
        }

        elseif(Qs::userIsTeacher()) {
            $section = $user->teacher->section;
            $students = $section ? $section->students : collect();
            $canManage = true;
            return view('pages.support_team.attendance.index', compact('section', 'students', 'canManage'));
        }

        else {
            abort(403);
        }



        // attach attendanceToday
        $today = now()->toDateString();
        foreach ($students as $student) {
            $attendance = Attendance::where('student_id', $student->id)
                ->where('section_id', $section->id)
                ->whereDate('date', $today)
                ->first();
            $student->attendanceToday = $attendance->status ?? null;
        }

        return view('pages.support_team.attendance.index', compact('sections', 'section', 'students', 'canManage'));
    }

    

    public function monthlyReport(Request $request, $section_id)
    {
        $section = Section::with('students.user')->findOrFail($section_id);
        $canManage = auth()->id() == $section->teacher_id;

        // 🗓️ Get month & year from request (default: current)
        $month = $request->month ?? now()->month;
        $year = $request->year ?? now()->year;

        // 🧮 Fetch all attendance records for this section in the selected month
        $attendances = Attendance::with('student.user')
            ->where('section_id', $section_id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        $students = $section->students;

        // Group by student for easier display
        $attendanceData = $students->map(function ($student) use ($attendances) {
            $records = $attendances->where('student_id', $student->id);
            return [
                'student' => $student,
                'records' => $records,
            ];
        });

        return view('pages.support_team.attendance.monthly', [
            'section' => $section,
            'attendanceData' => $attendanceData,
            'month' => $month,
            'year' => $year,
            'canManage' => $canManage,
        ]);
    }

    

   

    public function exportMonthlyAttendance(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);

        $fileName = 'attendance_' . $month . '_' . now()->format('Ymd') . '.xlsx';
        return Excel::download(new MonthlyAttendanceExport($month), $fileName);
    }


        

}
