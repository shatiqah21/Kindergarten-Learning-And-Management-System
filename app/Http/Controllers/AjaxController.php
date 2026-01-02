<?php

namespace App\Http\Controllers;

use App\Helpers\Qs;
use App\Repositories\LocationRepo;
use App\Repositories\MyClassRepo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\TeacherTimetable;

class AjaxController extends Controller
{
    protected $loc, $my_class;

    public function __construct(LocationRepo $loc, MyClassRepo $my_class)
    {
        $this->loc = $loc;
        $this->my_class = $my_class;
    }

    public function get_lga($state_id)
    {
//        $state_id = Qs::decodeHash($state_id);
//        return ['id' => Qs::hash($q->id), 'name' => $q->name];

        $lgas = $this->loc->getLGAs($state_id);
        return $data = $lgas->map(function($q){
            return ['id' => $q->id, 'name' => $q->name];
        })->all();
    }

    public function get_class_sections($class_id)
    {
        $sections = $this->my_class->getClassSections($class_id);
        return $sections = $sections->map(function($q){
            return ['id' => $q->id, 'name' => $q->name];
        })->all();
    }

    public function get_class_subjects($class_id)
    {
        $sections = $this->my_class->getClassSections($class_id);
        $subjects = $this->my_class->findSubjectByClass($class_id);

        if(Qs::userIsTeacher()){
            $subjects = $this->my_class->findSubjectByTeacher(Auth::user()->id)->where('my_class_id', $class_id);
        }

        $d['sections'] = $sections->map(function($q){
            return ['id' => $q->id, 'name' => $q->name];
        })->all();
        $d['subjects'] = $subjects->map(function($q){
            return ['id' => $q->id, 'name' => $q->name];
        })->all();

        return $d;
    }

    public function getTeacherSubjects(Request $request)
    {
        $class_id = $request->class_id;
        $teacher_id = auth()->id();

        $subjects = Subject::where('my_class_id', $class_id)
                           ->where('teacher_id', $teacher_id)
                           ->get();

        return response()->json($subjects);
    }

    public function getTeacherTimetable(Request $request)
    {
        $teacherId = Auth::id();

        $query = TeacherTimetable::with(['class', 'subject', 'teacher'])
                    ->where('teacher_id', $teacherId);

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $entries = $query->orderBy('day')->orderBy('time_start')->get();

        $days = $entries->pluck('day')->unique()->sort();
        $time_slots = $entries->pluck('time_start')->unique()->sort();

        $timetables = [];
        foreach ($entries as $entry) {
            $timetables[$entry->day][$entry->time_start][] = $entry;
        }

        return view('pages.support_team.timetables.teacher.partials.timetable_grid', compact(
            'days', 'time_slots', 'timetables'
        ));
    }


}
