@extends('layouts.master')
@section('page_title', 'Student Add-on List')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Student Add-on List</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        {{-- Tabs Daycare / Transit --}}
        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#daycare" class="nav-link active" data-toggle="tab">Daycare</a></li>
            <li class="nav-item"><a href="#transit" class="nav-link" data-toggle="tab">Transit</a></li>
        </ul>

        <div class="tab-content mt-3">
            {{-- Daycare Tab --}}
            <div class="tab-pane fade show active" id="daycare">
                <table class="table datatable-button-html5-columns">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Add-on</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($daycare_students as $s)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $s->name }}</td>
                            <td>{{ $s->email }}</td>
                            <td>{{ $s->class_name ?? '-' }}</td>
                            <td>{{ $s->section_name ?? '-' }}</td>
                            <td>
                                {{-- Add-on Form --}}
                                <form action="{{ route('students.addon', $s->id) }}" method="POST">
                                    @csrf
                                    <div class="input-group input-group-sm">
                                        <select name="addon_type" class="form-control" required>
                                            <option value="">-- Add-on --</option>
                                            <option value="daycare">Daycare</option>
                                            <option value="transit">Transit</option>
                                        </select>
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary btn-sm">Add</button>
                                        </div>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Transit Tab --}}
            <div class="tab-pane fade" id="transit">
                <table class="table datatable-button-html5-columns">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Add-on</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transit_students as $s)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $s->name }}</td>
                            <td>{{ $s->email }}</td>
                            <td>{{ $s->class_name ?? '-' }}</td>
                            <td>{{ $s->section_name ?? '-' }}</td>
                            <td>
                                {{-- Add-on Form --}}
                                <form action="{{ route('students.addon', $s->id) }}" method="POST">
                                    @csrf
                                    <div class="input-group input-group-sm">
                                        <select name="addon_type" class="form-control" required>
                                            <option value="">-- Add-on --</option>
                                            <option value="daycare">Daycare</option>
                                            <option value="transit">Transit</option>
                                        </select>
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary btn-sm">Add</button>
                                        </div>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

@endsection
