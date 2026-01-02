@extends('layouts.master')

@section('page_title', 'Monthly Attendance Report')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="card-title">Monthly Attendance - {{ $section->name }}</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        {{-- Month & Year Selector --}}
        <form method="GET" action="{{ route('attendance.monthly', $section->id) }}" class="form-inline mb-4">
            <label class="mr-2 font-weight-bold">Select Month:</label>
            <input type="month" name="month" value="{{ sprintf('%04d-%02d', $year, $month) }}" class="form-control mr-2">
            <button type="submit" class="btn btn-primary">View</button>
        </form>


        {{-- Download Button --}}
       <form action="{{ route('attendance.export.monthly') }}" method="GET" class="mb-3">
            <label for="month">Select Month:</label>
            <input type="month" name="month" id="month" class="form-control w-auto d-inline-block">
            <button type="submit" class="btn btn-success">Download Monthly Report</button>
        </form>


        {{-- Attendance Table --}}
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Total Present</th>
                    <th>Total Absent</th>
                    <th>Total Late</th>
                    <th>Total Excused</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendanceData as $data)
                    @php
                        $present = $data['records']->where('status', 'present')->count();
                        $absent  = $data['records']->where('status', 'absent')->count();
                        $late    = $data['records']->where('status', 'late')->count();
                        $excused = $data['records']->where('status', 'excused')->count();
                    @endphp
                    <tr>
                        <td>{{ $data['student']->user->name }}</td>
                        <td>{{ $present }}</td>
                        <td>{{ $absent }}</td>
                        <td>{{ $late }}</td>
                        <td>{{ $excused }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
