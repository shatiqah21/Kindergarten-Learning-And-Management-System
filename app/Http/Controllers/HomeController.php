<?php

namespace App\Http\Controllers;

use App\Helpers\Qs;
use App\Repositories\UserRepo;
use App\Models\Event;
use App\Models\StudentRecord;
use App\Models\Attendance;
use App\Models\Section;

class HomeController extends Controller
{
    protected $user;
    public function __construct(UserRepo $user)
    {
        $this->user = $user;
    }


    public function index()
    {
        return redirect()->route('dashboard');
    }

    public function privacy_policy()
    {
        $data['app_name'] = config('app.name');
        $data['app_url'] = config('app.url');
        $data['contact_phone'] = Qs::getSetting('phone');
        return view('pages.other.privacy_policy', $data);
    }

    public function terms_of_use()
    {
        $data['app_name'] = config('app.name');
        $data['app_url'] = config('app.url');
        $data['contact_phone'] = Qs::getSetting('phone');
        return view('pages.other.terms_of_use', $data);
    }

    
    public function dashboard()
    {
        $d = [];

        // Check if user is part of TeamSA
        if(Qs::userIsTeamSA()){
            $d['users'] = $this->user->getAll();

            // Get all events for FullCalendar
            $d['events'] = Event::all()->map(function($event){
                return [
                    'id'    => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_date,
                    'end'   => $event->end_date,
                ];
            });
        } else {
            // Default empty values to prevent errors in Blade
            $d['users'] = collect();
            $d['events'] = collect();
        }

        // Attendance Overview
        // Check if user is admin
        if(Qs::userIsTeamSAT()){
            $d['sections'] = Section::with('students.user')->get(); // ambil semua section
        }

        // Attendance overview for today
        $d['attendance'] = [
            'present' => StudentRecord::whereHas('attendances', function($q){
                $q->whereDate('date', now())->where('status','present');
            })->count(),
            'absent'  => StudentRecord::whereHas('attendances', function($q){
                $q->whereDate('date', now())->where('status','absent');
            })->count(),
            'late'    => StudentRecord::whereHas('attendances', function($q){
                $q->whereDate('date', now())->where('status','late');
            })->count(),
        ];

        return view('pages.support_team.dashboard', $d);
    }

}
