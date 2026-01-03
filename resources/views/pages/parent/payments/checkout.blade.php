@extends('layouts.master')
@section('page_title', 'Pay Payment')

@section('content')
<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">Pay Fees</h5>
    </div>

    <div class="card-body">
        @if(session('flash_danger'))
            <div class="alert alert-danger">{{ session('flash_danger') }}</div>
        @endif

        <form action="{{ route('parent.payments.checkout.process') }}" method="POST" id="payment-form">
            @csrf

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Amount (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($children as $child)
                        <tr>
                            <td>{{ $child->name }}</td>
                            <td>
                                <input type="number" 
                                       name="children[{{ $loop->index }}][amount]" 
                                       class="form-control child-amount" 
                                       value="{{ $child->amount ?? 0 }}" 
                                       min="0.50" step="0.01" required>
                                <input type="hidden" name="children[{{ $loop->index }}][student_id]" value="{{ $child->id }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mb-3">
                <strong>Total: RM <span id="total-amount">0.00</span></strong>
            </div>

            <button type="submit" class="btn btn-success btn-lg">
                <i class="icon-credit-card"></i> Pay Now
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
