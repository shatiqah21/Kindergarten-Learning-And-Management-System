@extends('layouts.master')

@section('content')
<div class="alert alert-danger mt-3">
    <h4>Payment Cancelled</h4>
    <p>Your payment was not completed.</p>
    <a href="{{ route('parent.payments.index') }}" class="btn btn-secondary">Back to Payments</a>
</div>
@endsection
