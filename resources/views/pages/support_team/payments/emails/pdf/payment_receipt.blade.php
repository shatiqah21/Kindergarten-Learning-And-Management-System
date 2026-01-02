<h2>Payment Receipt</h2>
<hr>

<p><strong>Reference No:</strong> {{ $payment->ref_no }}</p>
<p><strong>Parent ID:</strong> {{ $payment->my_parent_id }}</p>
<p><strong>Total Amount:</strong> RM {{ number_format($payment->amount, 2) }}</p>
<p><strong>Status:</strong> PAID</p>

<h4>Children Paid</h4>
<ul>
@foreach($payment->paymentDetails as $detail)
    <li>
        Student ID: {{ $detail->student_id }} –
        RM {{ number_format($detail->amount, 2) }}
    </li>
@endforeach
</ul>

<p>Date: {{ now() }}</p>
