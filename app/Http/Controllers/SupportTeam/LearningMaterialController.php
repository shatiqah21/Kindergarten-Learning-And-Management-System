<?php

namespace App\Http\Controllers\SupportTeam;

use App\Models\LearningMaterial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MyClass;
use App\Models\Subject;

class LearningMaterialController extends Controller
{
    // Senarai material ikut teacher login, boleh filter ikut class_id
    public function index(Request $request)
    {
        $query = LearningMaterial::where('teacher_id', auth()->id());

        // Filter by class_id jika ada
        if ($request->has('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        $materials = $query->get();

        return view('pages.support_team.teacher.materials.index', compact('materials'));
    }

   public function ajaxSubjects(Request $request)
    {
        $classId = $request->class_id;

        if (!$classId) return response()->json([]);

        // Fetch subjects assigned to the logged-in teacher for this class
        $subjects = Subject::where('teacher_id', auth()->id())
                        ->where('my_class_id', $classId)
                        ->get(['id', 'name']);

        return response()->json($subjects);
    }



    // Paparkan form upload
    public function create()
    {
        // Get classes where teacher has at least one assigned subject
        $classes = MyClass::whereHas('subjects', function($q){
            $q->where('teacher_id', auth()->id());
        })->get();

        // If there is at least one class, preload subjects for first class
        $firstClassId = $classes->first()->id ?? null;
        $subjects = collect();

        if($firstClassId){
            $subjects = Subject::where('teacher_id', auth()->id())
                            ->where('my_class_id', $firstClassId)
                            ->get();
        }

        return view('pages.support_team.teacher.materials.create', compact('classes', 'subjects', 'firstClassId'));
    }

   

    // Simpan upload baru
    public function store(Request $request)
    {
        $request->validate([
            'class_id'    => 'required|exists:my_classes,id',
            'subject_id'  => 'required|exists:subjects,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'file'        => 'required|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
        ]);

        $path = $request->file('file')->store('materials', 'public');

        LearningMaterial::create([
            'teacher_id'  => auth()->id(),
            'user_id'     => auth()->id(), // fix untuk field user_id
            'class_id'    => $request->class_id,
            'subject_id'  => $request->subject_id,
            'title'       => $request->title,
            'description' => $request->description,
            'file_path'   => $path,
        ]);

        return redirect()->route('teacher.materials.index')
                         ->with('success', 'Learning material uploaded successfully.');
    }

    // Download / view material
    public function download(LearningMaterial $material)
    {
        if ($material->teacher_id !== auth()->id()) {
            abort(403); // cikgu lain tak boleh download material orang lain
        }

        $originalName = $material->title . '.' . pathinfo($material->file_path, PATHINFO_EXTENSION);
        return response()->download(storage_path("app/public/{$material->file_path}"), $originalName);
    }

    // Delete material
    public function destroy(LearningMaterial $material)
    {
        if ($material->teacher_id !== auth()->id()) {
            abort(403);
        }

        // Delete file dari storage sekali (optional)
        if (\Storage::disk('public')->exists($material->file_path)) {
            \Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return redirect()->route('teacher.materials.index')
                         ->with('success', 'Learning material deleted.');
    }

    public function parentIndex()
    {
        // ambil class anak parent
        $student_ids = auth()->user()->parent->students->pluck('id');
        $class_ids = \App\Models\StudentRecord::whereIn('student_id', $student_ids)->pluck('my_class_id');

        $materials = LearningMaterial::whereIn('class_id', $class_ids)
                        ->with(['class', 'subject'])
                        ->get();

        return view('pages.parents.materials.index', compact('materials'));
    }

}
