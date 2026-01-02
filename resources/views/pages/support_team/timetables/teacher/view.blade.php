@extends('layouts.master')
@section('page_title', 'My Timetable')
@section('content')

<div class="card">
    <div class="card-header">
        <h6>My Timetable</h6>
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
                                $slots = $grid[$day][$time] ?? [];
                            @endphp
                            <td>
                                @if(count($slots))
                                    @foreach($slots as $slot)
                                        <div>
                                            <strong>{{ $slot->class->name ?? '-' }}</strong><br>
                                            {{ $slot->subject->name ?? '-' }}
                                        </div>
                                    @endforeach
                                @else
                                    -
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
