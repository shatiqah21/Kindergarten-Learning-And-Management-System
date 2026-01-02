/* ------------------------------------------------------------------------------
 *
 *  # Fullcalendar advanced options - Adjusted JS
 *
 * ------------------------------------------------------------------------------ */

var FullCalendarAdvanced = function() {

    // External events
    var _componentFullCalendarEvents = function() {
        if (!$().fullCalendar || typeof Switchery == 'undefined' || !$().draggable) {
            console.warn('Warning - fullcalendar.min.js, switchery.min.js or jQuery UI is not loaded.');
            return;
        }

        // Demo events with colors
        var eventColors = [
            { title: 'All Day Event', start: '2014-11-01', color: '#EF5350' },
            { title: 'Long Event', start: '2014-11-07', end: '2014-11-10', color: '#26A69A' },
            { id: 999, title: 'Repeating Event', start: '2014-11-09T16:00:00', color: '#26A69A' },
            { id: 999, title: 'Repeating Event', start: '2014-11-16T16:00:00', color: '#5C6BC0' },
            { title: 'Conference', start: '2014-11-11', end: '2014-11-13', color: '#546E7A' },
            { title: 'Meeting', start: '2014-11-12T10:30:00', end: '2014-11-12T12:30:00', color: '#546E7A' }
        ];

        // Initialize switch for "remove after drop"
        var removeSwitch = document.querySelector('.form-check-input-switchery');
        if(removeSwitch) new Switchery(removeSwitch);

        // Initialize calendar
        $('.fullcalendar-external').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            editable: true,
            defaultDate: '2014-11-12',
            events: eventColors,
            locale: 'en',
            droppable: true, // allow drop from external
            drop: function() {
                if ($('#drop-remove').is(':checked')) {
                    $(this).remove();
                }
            },
            isRTL: $('html').attr('dir') === 'rtl'
        });

        // Initialize external events
        $('#external-events .fc-event').each(function() {
            var $this = $(this);
            var color = $this.data('color');

            $this.css({ 'backgroundColor': color, 'borderColor': color });
            $this.data('event', { title: $.trim($this.text()), color: color, stick: true });

            $this.draggable({ zIndex: 999, revert: true, revertDuration: 0 });
        });
    };

    // FullCalendar RTL direction
    var _componentFullCalendarRTL = function() {
        if (!$().fullCalendar) {
            console.warn('Warning - fullcalendar.min.js is not loaded.');
            return;
        }

        var rtlEvents = [
            { title: 'All Day Event', start: '2014-11-01' },
            { title: 'Long Event', start: '2014-11-07', end: '2014-11-10' }
        ];

        $('.fullcalendar-rtl').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            defaultDate: '2014-11-12',
            editable: true,
            isRTL: true,
            locale: 'ar',
            events: rtlEvents
        });
    };

    return {
        init: function() {
            _componentFullCalendarEvents();
            _componentFullCalendarRTL();
        }
    };
}();

// Initialize module
document.addEventListener('DOMContentLoaded', function() {
    FullCalendarAdvanced.init();
});
