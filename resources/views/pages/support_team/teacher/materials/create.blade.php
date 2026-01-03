@extends('layouts.master')

@section('page_title', 'Upload Learning Material')

@section('content')
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Upload New Material</h6>
        <a href="{{ route('teacher.materials.index') }}" class="btn btn-primary btn-sm">
            <i class="icon-arrow-left52"></i> Back
        </a>
    </div>

    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('teacher.materials.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Class Dropdown --}}
            <div class="form-group">
                <label>Class:</label>
                <select name="class_id" id="class_id" class="form-control" required>
                    <option value="">-- Select Class --</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" 
                                {{ ($firstClassId == $class->id) ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Subject Dropdown --}}
            <div class="form-group">
                <label>Subject:</label>
                <select name="subject_id" id="subject_id" class="form-control" required>
                    <option value="">-- Select Subject --</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" data-class="{{ $subject->my_class_id }}">
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Title --}}
            <div class="form-group">
                <label>Title:</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            {{-- Description --}}
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>

            {{-- File Upload --}}
            <div class="form-group">
                <label>File (pdf, doc, ppt, zip):</label>
                <input type="file" name="file" class="form-control-file" required>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn btn-success">
                <i class="icon-upload"></i> Upload
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Filter subjects berdasarkan class yang dipilih
    $('#class_id').on('change', function() {
        var classId = $(this).val();

        $('#subject_id option').each(function() {
            var subjectClass = $(this).data('class');

            // Default option "-- Select Subject --"
            if (!subjectClass) {
                $(this).show();
            } 
            // Show only subjects assigned to selected class
            else if (subjectClass == classId) {
                $(this).show();
            } 
            // Hide all others
            else {
                $(this).hide();
            }
        });

        // Reset dropdown subject ke default
        $('#subject_id').val('');
    });

    // Trigger change event on page load untuk firstClassId
    $('#class_id').trigger('change');
});
</script>
@endsection
