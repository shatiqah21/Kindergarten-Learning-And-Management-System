@extends('layouts.master')
@section('page_title', 'My Dashboard')
@section('content')

    @if(Qs::userIsTeamSA())
       <div class="row">
           <div class="col-sm-6 col-xl-3">
               <div class="card card-body bg-blue-400 has-bg-image">
                   <div class="media">
                       <div class="media-body">
                           <h3 class="mb-0">{{ $users->where('user_type', 'student')->count() }}</h3>
                           <span class="text-uppercase font-size-xs font-weight-bold">Total Students</span>
                       </div>

                       <div class="ml-3 align-self-center">
                           <i class="icon-users4 icon-3x opacity-75"></i>
                       </div>
                   </div>
               </div>
           </div>


           <div class="col-sm-6 col-xl-3">
               <div class="card card-body bg-danger-400 has-bg-image">
                   <div class="media">
                       <div class="media-body">
                           <h3 class="mb-0">{{ $users->where('user_type', 'teacher')->count() }}</h3>
                           <span class="text-uppercase font-size-xs">Total Teachers</span>
                       </div>

                       <div class="ml-3 align-self-center">
                           <i class="icon-users2 icon-3x opacity-75"></i>
                       </div>
                   </div>
               </div>
           </div>

           <div class="col-sm-6 col-xl-3">
               <div class="card card-body bg-success-400 has-bg-image">
                   <div class="media">
                       <div class="mr-3 align-self-center">
                           <i class="icon-pointer icon-3x opacity-75"></i>
                       </div>

                       <div class="media-body text-right">
                           <h3 class="mb-0">{{ $users->where('user_type', 'admin')->count() }}</h3>
                           <span class="text-uppercase font-size-xs">Total Administrators</span>
                       </div>
                   </div>
               </div>
           </div>

           <div class="col-sm-6 col-xl-3">
               <div class="card card-body bg-indigo-400 has-bg-image">
                   <div class="media">
                       <div class="mr-3 align-self-center">
                           <i class="icon-user icon-3x opacity-75"></i>
                       </div>

                       <div class="media-body text-right">
                           <h3 class="mb-0">{{ $users->where('user_type', 'parent')->count() }}</h3>
                           <span class="text-uppercase font-size-xs">Total Parents</span>
                       </div>
                   </div>
               </div>
           </div>
       </div>
       @endif

   
  
   {{-- Attendance Overview --}}
    <div class="row mt-3">
        <div class="col-sm-4">
            <div class="card card-body bg-success-400">
                <h3 class="mb-0">{{ $attendance['present'] ?? 0 }}</h3>
                <span class="text-uppercase font-size-xs font-weight-bold">Present Today</span>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card card-body bg-danger-400">
                <h3 class="mb-0">{{ $attendance['absent'] ?? 0 }}</h3>
                <span class="text-uppercase font-size-xs font-weight-bold">Absent Today</span>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card card-body bg-warning-400">
                <h3 class="mb-0">{{ $attendance['late'] ?? 0 }}</h3>
                <span class="text-uppercase font-size-xs font-weight-bold">Late Today</span>
            </div>
        </div>
    </div>

    
    {{--Events Calendar Begins--}} 
    <div class="card"> 
        <div class="card-header header-elements-inline">
        <h5 class="card-title">School Events Calendar</h5> 
            {!! Qs::getPanelOptions() !!} </div> <div class="card-body">
            <div class="fullcalendar-custom"></div> 
        </div> 
    </div> 
    <!-- Edit Event Modal -->
    <div class="modal fade" id="editEventModal" tabindex="-1" role="dialog" aria-labelledby="editEventModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEventModalLabel">Edit Event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    <form id="editEventForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <input type="hidden" id="edit_event_id" name="id">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" class="form-control" id="edit_title" name="title">
                            </div>
                            <div class="form-group">
                                <label>Start</label>
                                <input type="datetime-local" class="form-control" id="edit_start" name="start">
                            </div>
                            <div class="form-group">
                                <label>End</label>
                                <input type="datetime-local" class="form-control" id="edit_end" name="end">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
{{--Events Calendar Ends--}}

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // FullCalendar setup
    var calendarEl = document.querySelector('.fullcalendar-custom');
    if (calendarEl) {
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            editable: true,
            selectable: true,
            events: '/events',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            eventClick: function(info) {
                $('#edit_event_id').val(info.event.id);
                $('#edit_title').val(info.event.title);
                $('#edit_start').val(moment(info.event.start).format('YYYY-MM-DDTHH:mm'));
                $('#edit_end').val(info.event.end ? moment(info.event.end).format('YYYY-MM-DDTHH:mm') : '');
                $('#editEventModal').modal('show');
            }
        });
        calendar.render();

        $('#editEventForm').on('submit', function(e) {
            e.preventDefault();
            var eventId = $('#edit_event_id').val();
            var formData = {
                _token: $('input[name=_token]').val(),
                _method: 'PUT',
                title: $('#edit_title').val(),
            };
            $.ajax({
                url: '/events/' + eventId,
                method: 'POST',
                data: formData,
                success: function() {
                    $('#editEventModal').modal('hide');
                    calendar.refetchEvents();
                },
                error: function() {
                    alert('Update failed');
                }
            });
        });
    }

   
});
</script>
@endpush
