<?php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Mail\Mailable;
use PDF;

class PaymentSuccess extends Mailable
{
    public $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function build()
    {
        $pdf = PDF::loadView('pdf.payment_receipt', [
            'payment' => $this->payment
        ]);

        return $this->subject('Payment Receipt')
            ->view('emails.payment_success')
            ->attachData($pdf->output(), 'receipt.pdf');
    }
}
