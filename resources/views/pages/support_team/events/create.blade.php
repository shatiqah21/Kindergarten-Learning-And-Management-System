@extends('layouts.master')
@section('page_title', 'Add Event')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">Add Event</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('events.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <input type="text" name="description" class="form-control" required>
            </div>

           <div class="form-group">
                    <label>Start Date & Time</label>
                    <input type="datetime-local" name="start_date" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>End Date & Time</label>
                    <input type="datetime-local" name="end_date" class="form-control" required>
                </div>

            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
</div>
@endsection
