@extends('layouts.master')
@section('page_title', 'My Payments')
@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0"><i class="icon-cash2 mr-2"></i> My Payments</h5>
    </div>

    <div class="card-body">
        {{-- Desktop Table --}}
        <div class="d-none d-md-block table-responsive">
            <table class="table table-striped table-bordered table-hover table-sm">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Total Amount</th>
                        <th>Ref No</th>
                        <th>Status</th>
                        <th>Children</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $p->title }}</td>
                            <td>RM {{ number_format($p->amount, 2) }}</td>
                            <td>{{ $p->ref_no }}</td>
                            <td>
                                @if($p->status == 'paid')
                                    <span class="badge badge-success">Paid</span>
                                @else
                                    <span class="badge badge-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                <ul class="mb-0 pl-3 medium">
                                    @foreach($p->paymentDetails as $d)
                                        <li>{{ $d->studentRecord->user->name ?? 'N/A' }} – RM {{ number_format($d->amt_paid, 2) }}</li>
                                    @endforeach
                                </ul>
                            </td>

                            <td class="text-nowrap">
                                <div class="d-flex flex-column flex-md-row gap-1">
                                    @if($p->status == 'pending')
                                        <a href="{{ route('parent.payments.checkout', $p->id) }}" class="btn btn-primary btn-sm">Pay Now</a>
                                    @else
                                        <a href="{{ route('parent.payments.receipt', $p->id) }}" class="btn btn-success btn-sm">View Receipt</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No payments found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Card View --}}
        <div class="d-md-none">
            @forelse($payments as $p)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $p->title }}</h5>
                        <p class="mb-1"><strong>Total Amount:</strong> RM {{ number_format($p->amount, 2) }}</p>
                        <p class="mb-1"><strong>Ref No:</strong> {{ $p->ref_no }}</p>
                        <p class="mb-1">
                            <strong>Status:</strong>
                            @if($p->status == 'paid')
                                <span class="badge badge-success">Paid</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </p>
                        <p class="mb-2"><strong>Children:</strong></p>
                       <ul class="mb-2 pl-3 medium">
                            @foreach($p->paymentDetails as $d)
                                <li>{{ $d->studentRecord->user->name ?? 'N/A' }} – RM {{ number_format($d->amt_paid, 2) }}</li>
                            @endforeach
                        </ul>


                        <div class="d-flex gap-2 flex-wrap">
                            @if($p->status == 'pending')
                                <a href="{{ route('parent.payments.checkout', $p->id) }}" class="btn btn-primary btn-sm flex-fill">Pay Now</a>
                            @else
                                <a href="{{ route('parent.payments.receipt', $p->id) }}" class="btn btn-success btn-sm flex-fill">View Receipt</a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-muted">No payments found</p>
            @endforelse
        </div>

    </div>
</div>

@endsection
