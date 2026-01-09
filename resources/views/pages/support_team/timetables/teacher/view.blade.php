@extends('layouts.master')
@section('page_title', 'My Timetable')
@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6>My Timetable</h6>
        {{-- Download Button --}}
        <a href="{{ route('teacher.timetables.download_pdf') }}" class="btn btn-primary btn-sm">
            <i class="icon-download"></i> Download PDF
        </a>
    </div>

    <div class="card-body" style="overflow-x:auto; overflow-y:auto; max-height:600px;">
        <table class="table table-bordered table-hover text-center">
            <thead class="thead-light">
                <tr>
                    <th style="position: sticky; left:0; background:#f8f9fa; z-index:2;">Time</th>
                    @foreach($days as $day)
                        <th class="{{ $day == ($current_day ?? '') ? 'table-warning' : '' }}">
                            {{ $day }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($time_slots as $time)
                    <tr>
                        {{-- Sticky Time column --}}
                        <td style="position: sticky; left:0; background:#fff; z-index:1;" class="font-weight-bold">
                            {{ $time }} - {{ date('H:i', strtotime($time.' +1 hour')) }}
                        </td>

                        @foreach($days as $day)
                            @php
                                $slots = $grid[$day][$time] ?? [];
                            @endphp
                            <td class="align-top" style="min-width:120px;">
                                @if(count($slots))
                                    @foreach($slots as $slot)
                                        <div class="p-1 mb-1 bg-info text-white rounded">
                                            <strong>{{ $slot->class->name ?? '-' }}</strong><br>
                                            {{ $slot->subject->name ?? '-' }}<br>
                                            <small>{{ \Carbon\Carbon::parse($slot->time_start)->format('H:i') }}
                                            - {{ \Carbon\Carbon::parse($slot->time_end)->format('H:i') }}</small>
                                        </div>
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
