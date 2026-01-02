<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\User;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'section_id',
        'teacher_id',
        'date',
        'status',
        'remarks'
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(StudentRecord::class, 'student_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
