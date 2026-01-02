<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\User;


class TeacherTimetable extends Model
{
    protected $fillable = [
        'teacher_id', 'class_id', 'subject_id', 'day', 'time_start', 'time_end'
    ];

  public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id')->where('user_type', 'teacher'); // atau 'user_type_id', ikut column awak
    }


    public function class()
    {
        return $this->belongsTo(MyClass::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }


}
