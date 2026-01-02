<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Requests\Subject\SubjectCreate;
use App\Http\Requests\Subject\SubjectUpdate;
use App\Repositories\MyClassRepo;
use App\Repositories\UserRepo;
use App\Http\Controllers\Controller;

class SubjectController extends Controller
{
    protected $my_class, $user;

    public function __construct(MyClassRepo $my_class, UserRepo $user)
    {
        $this->middleware('teamSA', ['except' => ['destroy',] ]);
        $this->middleware('super_admin', ['only' => ['destroy',] ]);

        $this->my_class = $my_class;
        $this->user = $user;
    }

   public function index()
    {
        $d['my_classes'] = $this->my_class->getAcademicClasses(); // ✅ guna academic classes je
        $d['teachers'] = $this->user->getUserByType('teacher');
        $d['subjects'] = $this->my_class->getAllSubjects();

        return view('pages.support_team.subjects.index', $d);
    }

   public function store(SubjectCreate $req)
    {
        $data = $req->all();

        // Check class type
        $class = $this->my_class->find($data['my_class_id']);
        if ($class && in_array($class->class_type->name, ['Daycare', 'Transit'])) {
            return response()->json([
                'status' => 0,
                'message' => 'Daycare / Transit classes cannot have subjects.'
            ], 422);
        }

        $this->my_class->createSubject($data);

        return Qs::jsonStoreOk();
    }

    public function edit($id)
    {
        $d['s'] = $sub = $this->my_class->findSubject($id);
        $d['my_classes'] = $this->my_class->all();
        $d['teachers'] = $this->user->getUserByType('teacher');

        return is_null($sub) ? Qs::goWithDanger('subjects.index') : view('pages.support_team.subjects.edit', $d);
    }

    public function update(SubjectUpdate $req, $id)
    {
        $data = $req->all();

        // Check class type
        $class = $this->my_class->find($data['my_class_id']);
        if ($class && in_array($class->class_type->name, ['Daycare', 'Transit'])) {
            return response()->json([
                'status' => 0,
                'message' => 'Daycare / Transit classes cannot have subjects.'
            ], 422);
        }

        $this->my_class->updateSubject($id, $data);

        return Qs::jsonUpdateOk();
    }

  public function destroy($id)
    {
        $this->my_class->deleteSubject($id);

        return redirect()->back()->with('flash_success', 'Record Had Been Deleted');
    }

}
