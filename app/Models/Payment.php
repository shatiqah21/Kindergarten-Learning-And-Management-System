<?php

namespace App\Models;

use Eloquent;
use App\User;

class Payment extends Eloquent
{
    protected $fillable = [
        'title',
        'amount',
        'method',
        'my_class_id',
        'my_parent_id',
        'year',
        'ref_no',
        'description',
        'status',
        'stripe_payment_id',
        'receipt_url'
    ];

    public function my_class()
    {
        return $this->belongsTo(MyClass::class);
    }

    public function my_parent()
    {
        return $this->belongsTo(User::class, 'my_parent_id');
    }

    public function paymentDetails()
    {
        return $this->hasMany(PaymentDetails::class, 'payment_id', 'id');
    }
}
