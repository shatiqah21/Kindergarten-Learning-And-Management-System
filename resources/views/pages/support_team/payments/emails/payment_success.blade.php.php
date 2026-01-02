<p>Dear Parent,</p>

<p>Your payment has been successfully completed.</p>

<p>
<strong>Reference No:</strong> {{ $payment->ref_no }} <br>
<strong>Total Amount:</strong> RM {{ number_format($payment->amount, 2) }}
</p>

<p>This payment may include one or more children.</p>

<p>Please find the receipt attached.</p>

<p>Thank you.</p>
