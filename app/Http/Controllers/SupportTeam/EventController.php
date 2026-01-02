<?php

namespace App\Http\Controllers\SupportTeam;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return view('pages.support_team.events.index', compact('events'));
    }

    public function create()
    {
        return view('pages.support_team.events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
        ]);

        Event::create([
            'title'       => $request->title,
            'description' => $request->description,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
        ]);

        return redirect()->route('events.index')->with('success', 'Event added successfully!');
    }

    public function edit(Event $event)
    {
        return view('pages.support_team.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
        ]);

        $event->update([
            'title'       => $request->title,
            'description' => $request->description,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
        ]);

        return redirect()->route('events.index')->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted successfully!');
    }

  public function getEvents()
    {
        $events = Event::all()->map(function($event) {
            return [
                'id'    => $event->id,
                'title' => $event->title,
                'desc'  => $event->description??'',
                'start' => $event->start_date,
                'end'   => $event->end_date,
                'color' => $event->color ?? 'rgba(59, 131, 108, 1)',
            ];
        });
        return response()->json($events, 200, [], JSON_PRETTY_PRINT);
    }

   


   


}
