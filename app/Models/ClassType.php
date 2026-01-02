<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassType extends Model
{
    protected $fillable = ['name', 'code'];

    /**
     * Semua classes bawah type ini
     */
    public function classes()
    {
        return $this->hasMany(MyClass::class, 'class_type_id');
    }
}
