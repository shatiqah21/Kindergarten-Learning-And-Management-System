@extends('layouts.master')
@section('page_title', 'School Events')

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="card-title">Events</h6>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $e)
                <tr>
                    <td>{{ $e->title }}</td>
                    <td>{{ \Carbon\Carbon::parse($e->date)->format('d/m/Y') }}</td>
                    <td>{{ $e->description }}</td>
                </tr>
                @empty
                <tr><td colspan="3">No events available.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
