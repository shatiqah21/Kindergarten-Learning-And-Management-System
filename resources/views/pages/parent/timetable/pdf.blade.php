<!DOCTYPE html>
<html>
<head>
    <title>Child Timetable PDF</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; }
        h3, h4 { margin-bottom: 5px; }
    </style>
</head>
<body>
    <h3>Child Timetable</h3>
    <p>Parent: {{ $parent->name }}</p>


    {{-- ✅ Loop for each child --}}
    @if($parent->children && $parent->children->count() > 0)
        
        @foreach($parent->children as $child)
           <h5 class="mb-0 fw-bold">{{ $child->my_class->name ?? 'No Class' }}</h5>
            <medium class="text-muted d-block">{{ $child->user->name ?? 'No Name' }}</medium>

            <table>
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
                            <td>
                                {{ is_numeric($time) 
                                    ? \Carbon\Carbon::createFromTimestamp($time)->format('h:i A') 
                                    : $time }}
                            </td>

                            @foreach($days as $day)
                                @php 
                                    $slot = $timetables[$day][$time] ?? [];
                                    $slot_for_child = array_filter($slot, fn($t) => $t['tt_record']['my_class_id'] == $child->my_class_id);
                                @endphp
                                <td>
                                    @if(count($slot_for_child))
                                        @foreach($slot_for_child as $t)
                                            {{ $t['subject']['name'] ?? 'No Subject' }}
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
            <br>
        @endforeach
    @else
        <p>No children found.</p>
    @endif
</body>
</html>
