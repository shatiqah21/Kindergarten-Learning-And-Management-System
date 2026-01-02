@extends('layouts.master')
@section('page_title', 'Teacher Timetable')
@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h6>Teacher Timetable Grid</h6>
        <div>
            <a href="{{ route('teacher_timetables.create') }}" class="btn btn-primary">Add New</a>
            <a href="{{ route('teacher_timetables.pdf') }}" class="btn btn-success">PDF</a>
        </div>
    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Time</th>
                    @foreach($days as $day)
                        <th>{{ $day }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
            @foreach($time_slots as $time)
                    <tr>
                        <td>{{ $time }} - {{ date('H:i', strtotime($time.' +1 hour')) }}</td>
                        @foreach($days as $day)
                            @php
                                $slots = $timetables[$day][$time] ?? null;
                            @endphp
                            <td>
                                @if($slots)
                                    @foreach($slots as $slot)
                                        <div>
                                            <strong>{{ $slot->class->name ?? '' }}</strong><br>
                                            {{ $slot->teacher->name ?? '' }}<br>
                                            {{ $slot->subject->name ?? '' }}<br>
                                            <form method="POST" action="{{ route('teacher_timetables.destroy', $slot->id) }}" onsubmit="return confirm('Delete this slot?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger mt-1">Del</button>
                                            </form>
                                        </div>
                                        <hr class="my-1">
                                    @endforeach
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach


            </tbody>
        </table>
    </div>
</div>

@endsection
