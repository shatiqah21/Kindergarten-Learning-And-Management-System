<?php

namespace App\Http\Controllers;

use App\Helpers\Qs;
use App\Repositories\UserRepo;
use App\Models\Event;
use App\Models\StudentRecord;
use App\Models\Attendance;
use App\Models\Section;
use App\User;

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
        } elseif(Qs::userIsTeacher() || Qs::userIsParent()) {
            $d['totalStudents'] = User::where('user_type','student')->count();
            $d['totalTeachers'] = User::where('user_type','teacher')->count();
            $d['totalParents']  = User::where('user_type','parent')->count();

            $d['events'] = collect();
        }

        
        return view('pages.support_team.dashboard', $d);
    }

}
