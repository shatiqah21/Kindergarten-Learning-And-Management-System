@extends('layouts.master')
@section('page_title', 'Add Teacher Timetable')
@section('content')

<div class="card">
    <div class="card-header">
        <h6>Add New Teacher Timetable</h6>
    </div>
    <div class="card-body">

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- Validation errors --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- Step 1: Teacher & Class Selection (GET Form) --}}
        <form method="GET" action="{{ route('teacher_timetables.create') }}">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Teacher</label>
                    <select name="teacher_id" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Select Teacher --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ ($selected_teacher ?? '') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label>Class</label>
                    <select name="class_id" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ ($selected_class ?? '') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Step 2: Main Form (POST) --}}
        <form action="{{ route('teacher_timetables.store') }}" method="POST">
            @csrf

            {{-- Only show the rest of the form if teacher AND class are selected --}}
            @if($selected_teacher && $selected_class)

                {{-- Subject --}}
                <div class="form-group">
                    <label>Subject</label>
                    <select name="subject_id" class="form-control" required>
                        <option value="">-- Select Subject --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Day --}}
                <div class="form-group">
                    <label>Day</label>
                    <select name="day" class="form-control" required>
                        @php
                            $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                        @endphp
                        @foreach($days as $day)
                            <option value="{{ $day }}" {{ old('day') == $day ? 'selected' : '' }}>{{ $day }}</option>
                        @endforeach
                    </select>
                    @error('day')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

               {{-- Time Start --}}
                <div class="form-group">
                    <label>Time Start</label>
                    <select name="time_start" class="form-control" required>
                        <option value="">-- Select Start Time --</option>
                        @foreach($time_slots as $time)
                            <option value="{{ $time }}" {{ old('time_start') == $time ? 'selected' : '' }}>
                                {{ $time }}
                            </option>
                        @endforeach
                    </select>
                    @error('time_start')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Time End --}}
                <div class="form-group">
                    <label>Time End</label>
                    <select name="time_end" class="form-control" required>
                        <option value="">-- Select End Time --</option>
                        @foreach($time_slots as $time)
                            <option value="{{ $time }}" {{ old('time_end') == $time ? 'selected' : '' }}>
                                {{ $time }}
                            </option>
                        @endforeach
                    </select>
                    @error('time_end')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>


                {{-- Hidden fields to carry selected teacher & class --}}
                <input type="hidden" name="teacher_id" value="{{ $selected_teacher }}">
                <input type="hidden" name="class_id" value="{{ $selected_class }}">

                {{-- Buttons --}}
                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ route('teacher_timetables.index') }}" class="btn btn-secondary">Cancel</a>

            @else
                <p class="text-muted">Please select a teacher and a class first to load subjects.</p>
            @endif

        </form>

    </div>
</div>

@endsection
