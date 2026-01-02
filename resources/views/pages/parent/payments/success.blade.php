@extends('layouts.master')

@section('content')
<div class="card">
    <div class="card-header">

        <h2>Payment Successful</h2>
        <p>Thank you! Payment of RM{{ number_format($payment->amount, 2) }} has been completed.</p>

        <ul>
            @foreach($payment->paymentDetails as $detail)
                <li>Child ID: {{ $detail->student_record_id }}, Amount Paid: RM{{ number_format($detail->amt_paid, 2) }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
