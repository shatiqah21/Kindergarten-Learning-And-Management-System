<!DOCTYPE html>
<html>
<head>
    <title>Teacher Timetable</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; }
        th { background-color: #f2f2f2; }
        
        .slot {
            background-color: #17a2b8;
            color: #fff;
            padding: 3px;
            margin-bottom: 2px;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <h3 style="text-align:center;">{{ Auth::user()->user_type == 'teacher' ? 'My Timetable' : 'Full Timetable' }}</h3>

    <table>
        <thead>
            <tr>
                <th>Time</th>
                @foreach($days as $day)
                    <th class="{{ $day == date('l') ? 'highlight' : '' }}">{{ $day }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($time_slots as $time)
                <tr>
                    <td>{{ $time }} - {{ date('H:i', strtotime($time.' +1 hour')) }}</td>
                    @foreach($days as $day)
                        @php
                            $slots = $timetables[$day][$time] ?? [];
                        @endphp
                        <td>
                            @if(count($slots))
                                @foreach($slots as $slot)
                                    <div class="slot">
                                        {{-- Show teacher name only for admin --}}
                                        @if(Auth::user()->user_type != 'teacher')
                                            {{ $slot->teacher->name ?? '-' }}<br>
                                        @endif
                                        {{ $slot->class->name ?? '-' }}<br>
                                        {{ $slot->subject->name ?? '-' }}<br>
                                        {{ \Carbon\Carbon::parse($slot->time_start)->format('H:i') }}
                                        - {{ \Carbon\Carbon::parse($slot->time_end)->format('H:i') }}
                                    </div>
                                @endforeach
                            
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
