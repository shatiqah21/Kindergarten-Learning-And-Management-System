<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Teacher Timetable PDF</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 5px;
        }
        th {
            background-color: #f2f2f2;
        }
        hr {
            border: 0;
            border-top: 1px dashed #000;
            margin: 2px 0;
        }
        div.slot {
            margin-bottom: 2px;
        }
    </style>
</head>
<body>
    <h3 style="text-align: center;">Teacher Timetable</h3>

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
                <td>{{ $time }} - {{ date('H:i', strtotime($time.' +1 hour')) }}</td>
                @foreach($days as $day)
                    @php $slot = $timetables[$day][$time] ?? null; @endphp
                    <td>
                        @if($slot)
                            @foreach($slot as $t)
                                <div class="slot">
                                    <strong>{{ $t->class->name ?? '-' }}</strong><br>
                                    {{ $t->teacher->name ?? '-' }}<br>
                                    {{ $t->subject->name ?? '-' }}
                                </div>
                                <hr>
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
</body>
</html>
