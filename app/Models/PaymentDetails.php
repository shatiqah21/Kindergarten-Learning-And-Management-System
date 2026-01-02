<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'student_record_id',
        'amt_paid',
        'balance',
        'paid',
        'year',
    ];

    // Relationships
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function studentRecord()
    {
        return $this->belongsTo(StudentRecord::class, 'student_record_id');
    }
}
