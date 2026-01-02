<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transit extends Model
{
    use HasFactory;

    protected $fillable = [
        'Transit',        // contoh: Transit Pagi / Transit Petang
        'Until 5 PM', // optional
    ];

    // Relationship dengan StudentRecord
    public function student_records()
    {
        return $this->hasMany(StudentRecord::class);
    }
}
