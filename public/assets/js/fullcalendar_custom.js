/* ------------------------------------------------------------------------------
 *
 *  # Fullcalendar custom options
 *
 *  File ini khas untuk override demo basic/advanced supaya guna event dari server.
 *
 * ---------------------------------------------------------------------------- */

var FullCalendarCustom = function() {

    //
    // Setup module components
    //

    var _componentFullCalendarCustom = function() {
        if (typeof $.fn.fullCalendar === 'undefined') {
            console.warn('Warning - fullcalendar.min.js is not loaded.');
            return;
        }

        // Calendar element
        var calendarEl = $('.fullcalendar-custom');

        if (calendarEl.length) {
            calendarEl.fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay,listMonth'
                },
                defaultDate: moment().format('YYYY-MM-DD'),
                navLinks: true, // boleh klik nama hari/week
                editable: false,
                eventLimit: false, // limit event dlm 1 hari
               
                // ==== ambil event dari Laravel route ====
                events: '/events/json', // route Laravel yg return JSON

                // ==== grid view: month, agendaWeek, agendaDay ====
                eventRender: function(event, element, view) {
                    if(view.name === 'month' || view.name === 'agendaWeek' || view.name === 'agendaDay'){
                        element.find('.fc-title').text(event.title); // hanya title
                    }
                },
                 // ==== list view: listMonth, listWeek, listDay ====
                eventAfterRender: function(event, element, view){
                    if(view.name.indexOf('list') !== -1){
                        let detail = event.title;
                        if(event.start) detail += " | Start: " + moment(event.start).format('YYYY-MM-DD');
                        if(event.end) detail += " | End: " + moment(event.end).format('YYYY-MM-DD');
                        if(event.desc) detail += " | Desc: " + event.desc;

                        element.find('.fc-list-item-title a').text(detail);
                    }
                },
                // ==== event style ====
                eventRender: function(event, element) {
                    if (event.color) {
                        element.css('background-color', event.color);
                        element.css('border-color', event.color);
                    }
                },

                // ==== bila klik event ====
                eventClick: function(event) {
                    alert('Event: ' + event.title);
                    // kalau nak redirect:
                    // if (event.url) {
                    //     window.open(event.url, '_blank');
                    //     return false;
                    // }
                }
            });
        }
    };

    //
    // Return objects assigned to module
    //

    return {
        init: function() {
            _componentFullCalendarCustom();
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function() {
    FullCalendarCustom.init();
});
