@extends('layouts.master')
@section('page_title', 'Edit Event')

@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Edit Event</h5>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('events.update', $event->id) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="title">Event Title</label>
                    <input type="text" name="title" value="{{ old('title', $event->title) }}" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="description">Event Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $event->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="start">Start Date</label>
                    <input type="datetime-local" name="start_date"
                        value="{{ old('start_date', $event->start_date ? \Carbon\Carbon::parse($event->start_date)->format('Y-m-d\TH:i') : '') }}"
                        class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="end">End Date</label>
                    <input type="datetime-local" name="end_date"
                        value="{{ old('end_date', $event->end_date ? \Carbon\Carbon::parse($event->end_date)->format('Y-m-d\TH:i') : '') }}"
                        class="form-control" required>
                </div>

                {{-- ✅ Submit button --}}
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">
                        Update Event
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection
