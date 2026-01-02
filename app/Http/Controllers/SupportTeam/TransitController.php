<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\Transit;
use Illuminate\Http\Request;

class TransitController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSA', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        $data['transits'] = Transit::with('student')->get();
        return view('pages.support_team.transit.index', $data);
    }

    public function create()
    {
        return view('pages.support_team.transit.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:student_records,id',
            'pickup_time' => 'required',
            'dropoff_time' => 'required',
        ]);

        Transit::create($validated);

        return redirect()->route('transit.index')->with('flash_success', __('msg.store_ok'));
    }

    public function show($id)
    {
        $data['transit'] = Transit::with('student')->findOrFail($id);
        return view('pages.support_team.transit.show', $data);
    }

    public function edit($id)
    {
        $data['transit'] = Transit::findOrFail($id);
        return view('pages.support_team.transit.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'pickup_time' => 'required',
            'dropoff_time' => 'required',
        ]);

        $transit = Transit::findOrFail($id);
        $transit->update($validated);

        return redirect()->route('transit.index')->with('flash_success', __('msg.update_ok'));
    }

    public function destroy($id)
    {
        $transit = Transit::findOrFail($id);
        $transit->delete();

        return back()->with('flash_success', __('msg.del_ok'));
    }
}
