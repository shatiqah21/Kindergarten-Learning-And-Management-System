<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MyClass extends Model
{
    // Columns yang boleh diisi
    protected $fillable = ['name', 'class_type_id'];

    /**
     * Sections dalam class ni
     */
    public function section()
    {
        return $this->hasMany(Section::class, 'my_class_id');
    }

    /**
     * Class type
     */
    public function class_type()
    {
        return $this->belongsTo(ClassType::class, 'class_type_id');
    }

    /**
     * Student records dalam class ni
     */
    public function student_record()
    {
        return $this->hasMany(StudentRecord::class, 'my_class_id');
    }

    /**
     * Optional: Subjects dalam class ni
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'my_class_id');
    }
}
