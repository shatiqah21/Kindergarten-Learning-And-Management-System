<?php

namespace App\Models;

use App\User;
use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentRecord extends Eloquent
{
    use HasFactory;

    protected $fillable = [
        'session', 
        'user_id', 
        'my_class_id', 
        'section_id', 
        'my_parent_id', 
        'dorm_id', 
        'dorm_room_no', 
        'adm_no', 
        'year_admitted', 
        'wd', 
        'wd_date', 
        'grad', 
        'grad_date', 
        'house', 
        'age',
        'add_on',
        'transit_id',   //  baru tambah
        'daycare_id'    //  baru tambah
    ];

    // --- RELATIONSHIPS ---
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    

    public function my_parent()
    {
        return $this->belongsTo(User::class, 'my_parent_id');
    }

    public function my_class()
    {
        return $this->belongsTo(MyClass::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function dorm()
    {
        return $this->belongsTo(Dorm::class);
    }

    public function addon()
    {
        return $this->add_on;
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    public function getAttendanceTodayAttribute()
    {
        // ambil attendance hari ini dari table attendance
        $record = $this->attendances()->whereDate('date', now())->first();
        return $record->status ?? null;
    }

    
}
