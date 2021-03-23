document.addEventListener('DOMContentLoaded', function() {
    let calendarEl = document.getElementById('calendar');

    const userId = document.getElementById('js-getUserId').dataset.name;

    let calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: [ 'interaction', 'dayGrid' ],
        defaultView: 'dayGridMonth',
        editable: true,
        firstDay : 1,
        eventDurationEditable : false,
        selectLongPressDelay:0,
        events: `/users/${userId}/setEvents`,

        eventDrop: function(info){
            editEventDate(info);
        },

        eventClick: function(info) {
            showEvent(info)
        },
    });
    calendar.render();
});