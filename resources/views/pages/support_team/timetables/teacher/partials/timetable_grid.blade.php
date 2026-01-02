@if(empty($days) || empty($time_slots))
    <p class="text-center">No timetable available.</p>
@else
<div class="table-responsive">
    <table class="table table-bordered text-center timetable-grid-sm">
        <thead class="thead-light">
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
                            $slot = $timetables[$day][$time] ?? null;
                        @endphp
                        <td>
                            @if($slot)
                                @foreach($slot as $t)
                                    <div class="slot-cell-sm">
                                        <strong>{{ $t->class->name ?? '-' }}</strong><br>
                                        {{ $t->subject->name ?? '-' }}<br>
                                        {{ $t->teacher->name ?? '-' }}
                                    </div>
                                    <hr class="my-1">
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
@endif

<style>
/* Compact timetable grid */
.timetable-grid-sm td, .timetable-grid-sm th {
    font-size: 12px;
    padding: 3px 5px;
}

.slot-cell-sm {
    margin-bottom: 2px;
}

@media (max-width: 576px) {
    .timetable-grid-sm td, .timetable-grid-sm th {
        font-size: 10px;
        padding: 2px 3px;
    }
}
</style>
