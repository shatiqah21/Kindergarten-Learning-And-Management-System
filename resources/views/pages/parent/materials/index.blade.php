@extends('layouts.master')
@section('page_title', 'Learning Materials')

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="card-title">Learning Materials</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive"> <!-- Makes table scrollable on small screens -->
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Class</th>
                        <th>Subject</th>
                        <th>Description</th>
                        <th>Download</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materials as $m)
                        <tr>
                            <td>{{ $m->title }}</td>
                            <td>{{ $m->myclass->name ?? '-' }}</td>
                            <td>{{ $m->subject->name ?? '-' }}</td>
                            <td>{{ $m->description }}</td>
                            <td>
                                <a href="{{ route('materials.parent.download', $m->id) }}" class="btn btn-sm btn-primary">
                                    Download
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No materials available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
