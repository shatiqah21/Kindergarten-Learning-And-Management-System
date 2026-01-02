@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Pay School Fees</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form id="payment-selection-form">
        @csrf
        <h4>Select Children</h4>
        @foreach($children as $child)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="children[]" value="{{ $child->id }}" id="child-{{ $child->id }}">
                <label class="form-check-label" for="child-{{ $child->id }}">
                    {{ $child->user->name }} - Class: {{ $child->my_class->name }}
                </label>
            </div>
        @endforeach

        <h4>Payment Method</h4>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="payment_method" value="cash" id="cash" checked>
            <label class="form-check-label" for="cash">Cash</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="payment_method" value="card" id="card">
            <label class="form-check-label" for="card">Credit/Debit Card (Stripe)</label>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Proceed to Payment</button>
    </form>

    <div id="stripe-payment" style="display:none;">
        <h4>Card Details</h4>
        <form id="payment-form">
            <div id="card-element" class="mb-3"></div>
            <div id="card-errors" role="alert" style="color:red;"></div>
            <button id="submit" class="btn btn-success">Pay RM<span id="total-amount"></span></button>
        </form>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>

var stripe = Stripe('{{ env("STRIPE_KEY") }}');
var  elements = stripe.elements();
var card = elements.create('card', {style:{base:{fontSize:'16px'}}});
 cardElement.mount('#card-element');

const selectionForm = document.getElementById('payment-selection-form');
const stripeContainer = document.getElementById('stripe-payment');
const totalAmountSpan = document.getElementById('total-amount');
const paymentForm = document.getElementById('payment-form');
let clientSecret;

selectionForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const selectedChildren = Array.from(selectionForm.querySelectorAll('input[name="children[]"]:checked')).map(i=>i.value);
    const paymentMethod = selectionForm.querySelector('input[name="payment_method"]:checked').value;

    if(selectedChildren.length === 0){
        alert('Select at least one child.');
        return;
    }

    if(paymentMethod === 'cash'){
        // Cash payment
        const formData = new FormData();
        selectedChildren.forEach(c=>formData.append('children[]',c));
        formData.append('payment_method','cash');
        formData.append('_token','{{ csrf_token() }}');

        fetch('{{ route("parent.payments.store") }}',{
            method:'POST',
            body: formData
        }).then(res=>res.json())
        .then(data=>{
            window.location.href = '{{ route("parent.payments.index") }}';
        }).catch(err=>console.error(err));
    } else if(paymentMethod === 'card'){
        // Stripe card payment
        const res = await fetch('{{ route("parent.payments.stripe.create") }}',{
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body: JSON.stringify({children:selectedChildren,payment_method:'card'})
        });
        const data = await res.json();
        if(data.error){ alert(data.error); return; }

        clientSecret = data.clientSecret;
        totalAmountSpan.textContent = data.amount.toFixed(2);

        selectionForm.style.display = 'none';
        stripeContainer.style.display = 'block';
    }
});

// Stripe card submission
paymentForm.addEventListener('submit', async (e)=>{
    e.preventDefault();
    const submitBtn = document.getElementById('submit');
    submitBtn.disabled = true;

    const {error, paymentIntent} = await stripe.confirmCardPayment(clientSecret,{payment_method:{card:card}});

    if(error){
        document.getElementById('card-errors').textContent = error.message;
        submitBtn.disabled = false;
    } else if(paymentIntent.status === 'succeeded'){
        alert('Payment successful!');
        window.location.href = '{{ route("parent.payments.index") }}';
    }
});
</script>
@endsection
