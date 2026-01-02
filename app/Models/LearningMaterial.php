<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Models\Subject;
use App\Models\MyClass;
use App\User;

class LearningMaterial extends Model
{
    // kalau table ikut nama plural 'learning_materials', tak perlu specify
    // kalau awak nak custom nama, boleh uncomment line bawah:
    // protected $table = 'learning_materials';

    protected $fillable = [
        'teacher_id',
        'class_id',
        'subject_id',
        'title',
        'description',
        'file_path',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Material dimiliki oleh seorang cikgu
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Material dikaitkan dengan satu class
    public function myclass()
    {
        // kalau awak guna model MyClass untuk table classes
        return $this->belongsTo(MyClass::class, 'class_id');
    }

    // Material dikaitkan dengan satu subject
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
