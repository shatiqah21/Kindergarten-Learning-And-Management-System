@extends('layouts.master')
@section('page_title', 'Manage Promotions')
@section('content')

{{-- Reset All --}}
<div class="card">
    <div class="card-body text-center">
        <button id="promotion-reset-all" class="btn btn-danger btn-large">
            Reset All Promotions for the Session
        </button>
    </div>
</div>

{{-- Promotions Table --}}
<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title font-weight-bold">
            Manage Promotions - Students Who Were Promoted From 
            <span class="text-danger">{{ $old_year }}</span> 
            TO <span class="text-success">{{ $new_year }}</span> Session
        </h5>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <table id="promotions-list" class="table datatable-button-html5-columns">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>From Class</th>
                    <th>To Class</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($promotions->sortBy('fc.name')->sortBy('student.name') as $p)
                <tr id="promotion-row-{{ $p->id }}">
                    <td>{{ $loop->iteration }}</td>
                    <td><img class="rounded-circle" style="height: 40px; width: 40px;" src="{{ $p->student->photo }}" alt="photo"></td>
                    <td>{{ $p->student->name }}</td>
                    <td>{{ $p->fc->name.' '.$p->fs->name }}</td>
                    <td>{{ $p->tc->name.' '.$p->ts->name }}</td>
                    <td>
                        @if($p->status === 'P')
                            <span class="text-success">Promoted</span>
                        @elseif($p->status === 'D')
                            <span class="text-danger">Not Promoted</span>
                        @else
                            <span class="text-primary">Graduated</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <button data-id="{{ $p->id }}" class="btn btn-danger promotion-reset">Reset</button>
                        <form id="promotion-reset-{{ $p->id }}" method="post" action="{{ route('students.promotion_reset', $p->id) }}">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {

    // Single Reset
    $(document).on('click', '.promotion-reset', function() {
        let pid = $(this).data('id');
        if(confirm('Are you sure you want to reset this promotion?')) {
            $.ajax({
                url: $('#promotion-reset-' + pid).attr('action'),
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: 'DELETE'
                },
                success: function(resp) {
                    $('#promotion-row-' + pid).fadeOut(400, function() { $(this).remove(); });
                    flash({msg: resp.msg || 'Promotion reset successfully', type: 'success'});
                },
                error: function(err) {
                    console.error(err);
                    alert('Something went wrong!');
                }
            });
        }
    });

    // Reset All
    $(document).on('click', '#promotion-reset-all', function() {
        if(confirm('Are you sure you want to reset all promotions for this session?')) {
            $.ajax({
                url: "{{ route('students.promotion_reset_all') }}",
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: 'DELETE'
                },
                success: function(resp) {
                    $('table#promotions-list > tbody > tr').fadeOut(400, function() { $(this).remove(); });
                    flash({msg: resp.msg || 'All promotions reset successfully', type: 'success'});
                },
                error: function(err) {
                    console.error(err);
                    alert('Something went wrong!');
                }
            });
        }
    });

});
</script>
@endpush


