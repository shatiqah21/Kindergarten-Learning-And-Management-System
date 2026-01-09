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

       @if(Qs::userIsTeacher() || Qs::userIsParent())
        <div class="row">

            {{-- Total Students --}}
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-blue-400 has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <h3 class="mb-0">{{ $totalStudents }}</h3>
                            <span class="text-uppercase font-size-xs font-weight-bold">Total Students</span>
                        </div>
                        <div class="ml-3 align-self-center">
                            <i class="icon-users4 icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Teachers --}}
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-danger-400 has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <h3 class="mb-0">{{ $totalTeachers }}</h3>
                            <span class="text-uppercase font-size-xs">Total Teachers</span>
                        </div>
                        <div class="ml-3 align-self-center">
                            <i class="icon-users2 icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Parents --}}
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-indigo-400 has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <h3 class="mb-0">{{ $totalParents }}</h3>
                            <span class="text-uppercase font-size-xs font-weight-bold">Total Parents</span>
                        </div>
                        <div class="ml-3 align-self-center">
                            <i class="icon-user icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        @endif


   
  
   

    
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

    // Function untuk format masa full (modal)
    function formatDateTime(date) {
        const day = String(date.getDate()).padStart(2,'0');
        const monthNames = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
        const month = monthNames[date.getMonth()];
        const year = date.getFullYear();

        let hours = date.getHours();
        const minutes = String(date.getMinutes()).padStart(2,'0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12; hours = hours ? hours : 12;

        return `${day} ${month} ${year} ${hours}:${minutes} ${ampm}`;
    }

    var calendarEl = document.querySelector('.fullcalendar-custom');
    if (!calendarEl) return;

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
        displayEventTime: true, // pastikan month view tunjuk masa
        eventTimeFormat: {      // format default untuk views lain
            hour: '2-digit',
            minute: '2-digit',
            hour12: true,
            meridiem: 'short'
        },

        // Paksa event block guna custom HTML
        eventContent: function(arg) {
            const start = new Date(arg.event.start);
            const end = new Date(arg.event.end);

            // Format masa sendiri (hh:mm AM/PM)
            function formatBlockTime(date) {
                let hours = date.getHours();
                let minutes = String(date.getMinutes()).padStart(2,'0');
                const ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12; hours = hours ? hours : 12;
                return `${hours}:${minutes} ${ampm}`;
            }

            let timeText = formatBlockTime(start) + ' - ' + formatBlockTime(end);

            return { html: '<b>' + arg.event.title + '</b><br>' + timeText };
        },

        // Modal popup bila click event
        eventClick: function(info) {
            var start = new Date(info.event.start);
            var end = new Date(info.event.end);

            var timing = formatDateTime(start) + ' - ' + formatDateTime(end);

            $('#eventTitle').text(info.event.title);
            $('#eventTiming').text(timing);
            $('#eventDescription').text(info.event.extendedProps.description || 'No description');

            var eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
            eventModal.show();
        }
    });

    calendar.render();

    // Form edit event (Ajax)
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
});
</script>
@endpush
