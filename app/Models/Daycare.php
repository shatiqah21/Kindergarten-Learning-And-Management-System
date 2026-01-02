<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Daycare extends Model
{
    use HasFactory;

    protected $fillable = [
        'Daycare',        // contoh: Daycare Halfday / Fullday
        'Until 3 PM', // optional
    ];

    // Relationship dengan StudentRecord
    public function student_records()
    {
        return $this->hasMany(StudentRecord::class);
    }
}
