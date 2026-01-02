<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MyClass; // untuk relation my_class
use App\User;    // untuk relation teacher

class Subject extends Model
{
    // Pastikan semua column yang boleh diisi
    protected $fillable = [
        'name',
        'slug',
        'my_class_id',
        'teacher_id'
    ];

    /**
     * Relationship ke MyClass
     */
    public function my_class()
    {
        return $this->belongsTo(MyClass::class, 'my_class_id');
    }

    /**
     * Relationship ke Teacher (User)
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
