@extends('layouts.master')
@section('page_title', 'Teachers')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Teachers</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <table class="table datatable-button-html5-columns">
            <thead>
            <tr>
                <th>S/N</th>
                <th>Photo</th>
                <th>Name</th>
                <th>Username</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($teachers as $teacher)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <img class="rounded-circle" style="height: 40px; width: 40px;" 
                             src="{{ $teacher->photo ?? asset('images/default-user.png') }}" 
                             alt="photo">
                    </td>
                    <td>{{ $teacher->name }}</td>
                    <td>{{ $teacher->username }}</td>
                    <td>{{ $teacher->phone ?? '-' }}</td>
                    <td>{{ $teacher->email }}</td>
                    <td class="text-center">
                       <a href="{{ route('users.show', Qs::hash($teacher->id)) }}" target="_blank"
                            class="btn btn-sm btn-primary w-100">
                                View Profile
                       </a>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
