@extends('layouts.master')
@section('page_title', 'Edit Teacher Timetable')
@section('content')

<div class="card">
    <div class="card-header">
        <h6>Edit Teacher Timetable</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('teacher_timetables.update', $teacherTimetable->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Teacher --}}
            <div class="form-group">
                <label>Teacher</label>
                <select name="teacher_id" class="form-control" required>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ $teacherTimetable->teacher_id == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Class --}}
            <div class="form-group">
                <label>Class</label>
                <select name="class_id" class="form-control" required>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ $teacherTimetable->class_id == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Subject --}}
            <div class="form-group">
                <label>Subject</label>
                <select name="subject_id" class="form-control" required>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ $teacherTimetable->subject_id == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Day --}}
            <div class="form-group">
                <label>Day</label>
                <select name="day" class="form-control" required>
                    @php $days = ['Monday','Tuesday','Wednesday','Thursday','Friday']; @endphp
                    @foreach($days as $day)
                        <option value="{{ $day }}" {{ $teacherTimetable->day == $day ? 'selected' : '' }}>{{ $day }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Time Start --}}
            <div class="form-group">
                <label>Time Start</label>
                <select name="time_start" class="form-control" required>
                    @foreach($time_slots as $time)
                        <option value="{{ $time }}" {{ $teacherTimetable->time_start == $time ? 'selected' : '' }}>{{ $time }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Time End --}}
            <div class="form-group">
                <label>Time End</label>
                <select name="time_end" class="form-control" required>
                    @foreach($time_slots as $time)
                        <option value="{{ $time }}" {{ $teacherTimetable->time_end == $time ? 'selected' : '' }}>{{ $time }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('teacher_timetables.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
