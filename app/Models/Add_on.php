<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Add_On extends Model
{
    use HasFactory;

    protected $table = 'add_ons'; // kalau table kamu nama lain, tukar sini
    protected $fillable = ['student_id', 'type', 'price']; // contoh fields
}
