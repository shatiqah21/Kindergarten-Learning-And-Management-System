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
        {{-- Validation Errors --}}
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
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Subject Dropdown --}}
            <div class="form-group">
                <label>Subject:</label>
                <select name="subject_id" id="subject_id" class="form-control" required>
                    <option value="">-- Select Subject --</option>
                    {{-- Subjects will populate dynamically via AJAX --}}
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

            {{-- File --}}
            <div class="form-group">
                <label>File (pdf, doc, ppt, zip):</label>
                <input type="file" name="file" class="form-control-file" required>
            </div>

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

    function loadSubjects(classId, selected = '') {
        if(!classId) {
            $('#subject_id').html('<option value="">-- Select Subject --</option>');
            return;
        }

        $.ajax({
            url: '{{ route("ajax.teacher_subjects") }}',
            type: 'GET',
            data: { class_id: classId },
            success: function(data) {
                var options = '<option value="">-- Select Subject --</option>';
                $.each(data, function(key, subject) {
                    var selectedAttr = (subject.id == selected) ? ' selected' : '';
                    options += '<option value="'+subject.id+'"'+selectedAttr+'>'+subject.name+'</option>';
                });
                $('#subject_id').html(options);
            },
            error: function(err) {
                console.error(err);
                alert('Could not load subjects. Check console.');
            }
        });
    }

    // Load subjects when class changes
    $('#class_id').change(function() {
        loadSubjects($(this).val());
    });

    // Optional: pre-load subjects for the first class
    var firstClass = $('#class_id').val();
    if(firstClass) {
        loadSubjects(firstClass);
    }
});
</script>
@endsection
