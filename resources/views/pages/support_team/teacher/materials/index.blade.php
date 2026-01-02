@extends('layouts.master')

@section('page_title', 'Learning Materials')

@section('content')
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">My Learning Materials</h6>
        <a href="{{ route('teacher.materials.create') }}" class="btn btn-success btn-sm">
            <i class="icon-plus2"></i> Upload New
        </a>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>Title</th>
                        <th>Class</th>
                        <th>Subject</th>
                        <th>Description</th>
                        <th>File</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materials as $material)
                        <tr>
                            <td>{{ $material->title }}</td>
                            <td>{{ $material->myclass->name ?? '-' }}</td>
                            <td>{{ $material->subject->name ?? '-' }}</td>
                            <td>{{ $material->description }}</td>
                            <td>
                                <a href="{{ route('teacher.materials.download', $material->id) }}" class="btn btn-sm btn-info">
                                    <i class="icon-download"></i> Download
                                </a>
                            </td>
                            <td>
                                <form action="{{ route('teacher.materials.destroy', $material->id) }}" method="POST" onsubmit="return confirm('Delete this material?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="icon-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No materials uploaded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
