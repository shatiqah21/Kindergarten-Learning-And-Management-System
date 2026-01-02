@extends('layouts.master')

@section('page_title', 'Student Attendance')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
       <h6 class="card-title">Attendance {{ $section->name }} ({{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }})</h6>

        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">

       {{-- Section Dropdown --}}
        @if(Qs::userIsTeamSA())
            <div class="mb-4">
                <div class="d-flex align-items-center">
                    <label class="mr-2 font-weight-bold mb-0">Select Section:</label>
                    <form method="GET" action="{{ route('attendance.show') }}" class="mb-0">
                        <select name="section_id" class="form-control d-inline-block w-auto" onchange="this.form.submit()">
                            @foreach($sections as $sec)
                                <option value="{{ $sec->id }}" {{ $sec->id == $section->id ? 'selected' : '' }}>
                                    {{ $sec->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        @endif

        {{-- Date Filter --}}
        
        <div class="mb-4 d-flex align-items-center">
            <form method="GET" action="{{ route('attendance.index', $section->id) }}" class="form-inline">
                <label for="date" class="mr-2 font-weight-bold mb-0">Select Date:</label>
                <input type="date" name="date" id="date" value="{{ $selectedDate }}" class="form-control mr-2" onchange="this.form.submit()">
                
                @if(Qs::userIsTeamSA())
                    {{-- Keep section_id in case SA view multiple --}}
                    <input type="hidden" name="section_id" value="{{ $section->id }}">
                @endif
            </form>
        </div>
        {{-- Monthly Report Download --}}
        <div class="mb-4">
            <form action="{{ route('attendance.export.monthly') }}" method="GET" class="form-inline">
                <label for="month" class="mr-2 font-weight-bold mb-0">Select Month:</label>
                <input type="month" name="month" id="month" class="form-control mr-2" required>
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-download"></i> Download Monthly Report
                </button>
            </form>
        </div>



        {{-- Daily Attendance Summary --}}
        <div class="row mb-3 text-center">
            @php
                $presentCount = $students->filter(fn($s) => ($s->attendanceToday ?? '') == 'present')->count();
                $absentCount  = $students->filter(fn($s) => ($s->attendanceToday ?? '') == 'absent')->count();
                $lateCount    = $students->filter(fn($s) => ($s->attendanceToday ?? '') == 'late')->count();
                $excusedCount = $students->filter(fn($s) => ($s->attendanceToday ?? '') == 'excused')->count();
            @endphp

            <div class="col-sm-3">
                <div class="card text-white bg-success mb-2">
                    <div class="card-body">
                        <h5>{{ $presentCount }}</h5>
                        <small>Present</small>
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="card text-white bg-danger mb-2">
                    <div class="card-body">
                        <h5>{{ $absentCount }}</h5>
                        <small>Absent</small>
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="card text-white bg-warning mb-2">
                    <div class="card-body">
                        <h5>{{ $lateCount }}</h5>
                        <small>Late</small>
                    </div>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="card text-white bg-info mb-2">
                    <div class="card-body">
                        <h5>{{ $excusedCount }}</h5>
                        <small>Excused</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Attendance Table --}}
        <form action="{{ route('attendance.store', $section->id) }}" method="POST">
            @csrf
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        @php
                            $status = $student->attendanceToday ?? '';
                            $rowClass = '';
                            switch($status) {
                                case 'present': $rowClass = 'table-success'; break;
                                case 'absent':  $rowClass = 'table-danger'; break;
                                case 'late':    $rowClass = 'table-warning'; break;
                                case 'excused': $rowClass = 'table-info'; break;
                            }
                        @endphp
                        <tr class="{{ $rowClass }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $student->user->name }}</td>
                            <td>
                                @if($canManage)
                                    <select name="attendance[{{ $student->id }}]" class="form-control">
                                        <option value="present" {{ $status == 'present' ? 'selected' : '' }}>Present</option>
                                        <option value="absent" {{ $status == 'absent' ? 'selected' : '' }}>Absent</option>
                                        <option value="excused" {{ $status == 'excused' ? 'selected' : '' }}>Excused</option>
                                        <option value="late" {{ $status == 'late' ? 'selected' : '' }}>Late</option>
                                    </select>
                                @else
                                    {{ ucfirst($status ?: 'N/A') }}
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No students found for this section.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($canManage)
                <button type="submit" class="btn btn-primary mt-3">Save Attendance</button>
            @endif
        </form>
    </div>
</div>
@endsection
