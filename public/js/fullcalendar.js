document.addEventListener('DOMContentLoaded', function() {
    let calendarEl = document.getElementById('calendar');

    let calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: [ 'interaction', 'dayGrid' ],
        defaultView: 'dayGridMonth',
        editable: true,
        firstDay : 1,
        eventDurationEditable : false,
        selectLongPressDelay:0,
        events: "/setEvents",

        eventDrop: function(info){
            editEventDate(info);
        },

        eventClick: function(info) {
            editEvent(info);
        },
    });
    calendar.render();
});