@extends('layouts.master')
@section('page_title', 'Teacher Timetable')
@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between flex-wrap">
        <h6>Teacher Timetable Grid</h6>
        <div class="mt-2 mt-md-0">
            <a href="{{ route('teacher_timetables.create') }}" class="btn btn-primary">Add New</a>
            <a href="{{ route('teacher_timetables.pdf') }}" class="btn btn-success">PDF</a>
        </div>
    </div>

    <div class="card-body">
        <!-- Scrollable table container -->
        <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered text-center table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 2;">Time</th>
                        @foreach($days as $day)
                            <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 2;">{{ $day }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($time_slots as $time)
                        <tr>
                            <td class="align-middle">
                                {{ $time }} - {{ date('H:i', strtotime($time.' +1 hour')) }}
                            </td>
                            @foreach($days as $day)
                                @php
                                    $slots = $timetables[$day][$time] ?? null;
                                @endphp
                                <td class="align-top p-1" style="min-width: 120px;">
                                    @if($slots && count($slots) > 0)
                                        @foreach($slots as $slot)
                                            <div class="border p-1 mb-1 rounded bg-light">
                                                <strong>{{ $slot->class->name ?? '' }}</strong><br>
                                                {{ $slot->teacher->name ?? '' }}<br>
                                                {{ $slot->subject->name ?? '' }}<br>
                                                <form method="POST" action="{{ route('teacher_timetables.destroy', $slot->id) }}" onsubmit="return confirm('Delete this slot?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger mt-1">Del</button>
                                                </form>
                                            </div>
                                        @endforeach
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
