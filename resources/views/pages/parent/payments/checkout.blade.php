@extends('layouts.master')
@section('page_title', 'Pay Payment')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">Pay {{ $payment->title }}</h5>
    </div>

    <div class="card-body">
        <p>Total Amount: <strong>RM {{ number_format($payment->amount, 2) }}</strong></p>

        <form id="payment-form">
            @csrf
            <div id="card-element"></div>
            <button id="submit" class="btn btn-primary mt-3">Pay Now</button>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe("{{ config('services.stripe.key') }}");
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');

    const form = document.getElementById('payment-form');
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const {error, paymentIntent} = await stripe.confirmCardPayment("{{ $client_secret }}", {
            payment_method: {
                card: cardElement,
            }
        });

        if(error){
            alert(error.message);
        } else {
            alert("Payment Successful!");
            window.location.href = "{{ route('parent.payments.index') }}";
        }
    });
</script>
@endsection
