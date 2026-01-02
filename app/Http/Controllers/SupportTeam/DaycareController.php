<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\Daycare;
use Illuminate\Http\Request;

class DaycareController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSA', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        $data['daycares'] = Daycare::with('student')->get();
        return view('pages.support_team.daycare.index', $data);
    }

    public function create()
    {
        return view('pages.support_team.daycare.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:student_records,id',
            'checkin_time' => 'required',
            'checkout_time' => 'required',
        ]);

        Daycare::create($validated);

        return redirect()->route('daycare.index')->with('flash_success', __('msg.store_ok'));
    }

    public function show($id)
    {
        $data['daycare'] = Daycare::with('student')->findOrFail($id);
        return view('pages.support_team.daycare.show', $data);
    }

    public function edit($id)
    {
        $data['daycare'] = Daycare::findOrFail($id);
        return view('pages.support_team.daycare.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'checkin_time' => 'required',
            'checkout_time' => 'required',
        ]);

        $daycare = Daycare::findOrFail($id);
        $daycare->update($validated);

        return redirect()->route('daycare.index')->with('flash_success', __('msg.update_ok'));
    }

    public function destroy($id)
    {
        $daycare = Daycare::findOrFail($id);
        $daycare->delete();

        return back()->with('flash_success', __('msg.del_ok'));
    }
}
