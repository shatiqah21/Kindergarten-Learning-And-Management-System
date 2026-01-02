<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    // table kalau nama lain daripada 'events' kena declare
    // protected $table = 'events';

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
    ];

    // Pastikan format date bila retrieve dari DB
    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
    ];

    // Optional: format output bila display
    public function getStartDateFormattedAttribute()
    {
        return $this->start_date ? Carbon::parse($this->start_date)->format('Y-m-d H:i') : null;
    }

    public function getEndDateFormattedAttribute()
    {
        return $this->end_date ? Carbon::parse($this->end_date)->format('Y-m-d H:i') : null;
    }
}
