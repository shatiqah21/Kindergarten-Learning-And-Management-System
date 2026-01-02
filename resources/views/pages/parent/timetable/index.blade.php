@extends('layouts.master')
@section('page_title', 'Child Timetable')

@section('content')

<div class="card">
   <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-uppercase" style="font-size: 1.25rem;">
            Class timetable Grid
        </h5>    
            <a href="{{ route('parent.timetable.pdf') }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-download"></i> Download PDF
            </a>
</div>
    
    <div class="card-body">
                

        <div class="table-responsive">

            @if($parent->children && $parent->children->count() > 0)
                @foreach($parent->children as $child)
                   <div class="mt-3 mb-2">
                        <h5 class="mb-0 fw-bold">{{ $child->my_class->name ?? 'No Class' }}</h5>
                       <medium class="text-muted d-block">{{ $child->user->name ?? 'No Name' }}</medium>

                    </div>

                    <table class="table table-bordered text-center timetable-grid-sm mb-4">
                        <thead class="thead-light">
                            <tr>
                                <th>Time</th>
                                @foreach($days as $day)
                                    <th>{{ $day }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($time_slots as $time)
                                <tr>
                                    <td>
                                        @if(is_numeric($time))
                                            {{-- Jika $time ialah Unix timestamp --}}
                                            {{ \Carbon\Carbon::createFromTimestamp($time)->format('h:i A') }}
                                        @else
                                            @php
                                                // Jika $time adalah array / object TimeSlot
                                                $hour = $time['hour_from'] ?? $time->hour_from ?? null;
                                                $min = $time['min_from'] ?? $time->min_from ?? '00';
                                                $meridian = $time['meridian_from'] ?? $time->meridian_from ?? '';
                                                $display_time = $hour ? $hour . ':' . $min . ' ' . $meridian : $time;
                                            @endphp
                                            {{ $display_time }}
                                        @endif
                                    </td>

                                    @foreach($days as $day)
                                        @php 
                                            $slot = $timetables[$day][$time] ?? [];
                                            $slot_for_child = array_filter($slot, fn($t) => $t['tt_record']['my_class_id'] == $child->my_class_id);
                                        @endphp
                                        <td>
                                            @if(count($slot_for_child))
                                                @foreach($slot_for_child as $t)
                                                    <div class="slot-cell-sm">
                                                        {{ $t['subject']['name'] ?? 'No Subject' }}
                                                    </div>
                                                @endforeach
                                            @else
                                                -
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($days)+1 }}">No timetable available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @endforeach
            @else
                <p>No children found.</p>
            @endif

        </div>
    </div>
</div>

<style>
/* Compact grid styling */
.timetable-grid-sm td, .timetable-grid-sm th {
    font-size: 12px;
    padding: 3px 5px;
}
.slot-cell-sm { 
    margin-bottom: 2px; 
    font-weight: normal;
}
h5 {
    font-size: 16px;
    font-weight: 600;
}
@media (max-width: 576px) {
    .timetable-grid-sm td, .timetable-grid-sm th { 
        font-size: 10px; 
        padding: 2px 3px; 
    }
    h5 { font-size: 14px; }
}
</style>

@endsection
