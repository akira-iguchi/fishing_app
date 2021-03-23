document.addEventListener('DOMContentLoaded', function() {
    let calendarEl = document.getElementById('calendar');

    let userId = document.getElementById('js-getUserId').dataset.name,
        authUserId = document.getElementById('js-getAuthUserId').dataset.name;

    let calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: [ 'interaction', 'dayGrid', 'googleCalendarPlugin' ],
        defaultView: 'dayGridMonth',
        editable: true,
        locale: "ja",
        timeZone: 'Asia/Tokyo',
        eventDurationEditable : false,
        selectLongPressDelay:0,
        buttonText: {
            today: '今日'
        },
        events: `/users/${userId}/setEvents`,

        eventDrop: function(info){
            editEventDate(info);
        },

        eventClick: function(info) {
            showEvent(info)
        },
    });
    calendar.render();

    document.getElementById("addEvent").addEventListener('click', function() {
        let date = document.eventForm.date,
            fishingType = document.eventForm.fishing_type,
            spot = document.eventForm.spot,
            bait = document.eventForm.bait,
            weather = document.eventForm.weather,
            fishingStartTime = document.eventForm.fishing_start_time,
            fishingEndTime = document.eventForm.fishing_end_time,
            detail = document.eventForm.detail,
            eventSuccess = document.getElementById("event_success"),
            eventError = document.getElementById("event_error"),
            dateError = document.getElementById("date_error"),
            fishingTypeError = document.getElementById("fishing_type_error"),
            spotError = document.getElementById("spot_error"),
            baitError = document.getElementById("bait_error"),
            weatherError = document.getElementById("weather_error"),
            detailError = document.getElementById("detail_error");
        $.ajax({
            url: `/users/${userId}/ajax/addEvent`,
            type: 'POST',
            dataTape: 'json',
            data:{
                "date": date.value,
                "fishing_type": fishingType.value,
                "spot": spot.value,
                "bait": bait.value,
                "weather": weather.value,
                "fishing_start_time": fishingStartTime.value,
                "fishing_end_time": fishingEndTime.value,
                "detail": detail.value,
            }
        }).fail(function(data) {
            eventSuccess.innerHTML = "";
            eventError.innerHTML = "イベントの投稿に失敗しました";
            dateError.innerHTML = data.responseJSON.errors.date || "";
            fishingTypeError.innerHTML = data.responseJSON.errors.fishing_type || "";
            spotError.innerHTML = data.responseJSON.errors.spot || "";
            baitError.innerHTML = data.responseJSON.errors.bait || "";
            weatherError.innerHTML = data.responseJSON.errors.weather || "";
            detailError.innerHTML = data.responseJSON.errors.detail || "";
        }).done(function() {
            eventSuccess.innerHTML = "イベントを投稿しました";
            eventError.innerHTML = "";
            dateError.innerHTML = "";
            fishingTypeError.innerHTML = "";
            spotError.innerHTML = "";
            baitError.innerHTML = "";
            weatherError.innerHTML = "";
            detailError.innerHTML = "";
            date.value = "";
            fishingType.value = "";
            spot.value = "";
            bait.value = "";
            weather.value = "";
            fishingStartTime.value = "";
            fishingEndTime.value = "";
            detail.value = "";

            calendar.refetchEvents();
        });
    });

    function showEvent(info) {
        const popup = document.querySelector('.popup-wrapper'),
            private = document.querySelector('.event_private');
        document.getElementById('modal-date').innerHTML = info.event.start.toLocaleDateString();
        document.getElementById('modal-spot').innerHTML = info.event.extendedProps.spot;
        document.getElementById('modal-fishing_type').innerHTML = info.event.title;
        document.getElementById('modal-bait').innerHTML = info.event.extendedProps.bait;
        document.getElementById('modal-weather').innerHTML = info.event.extendedProps.weather;
        if (info.event.extendedProps.fishing_start_time !== null) {
            document.getElementById('modal-fishing_start_time').innerHTML = info.event.extendedProps.fishing_start_time.slice(0, -3);
        }
        if (info.event.extendedProps.fishing_end_time !== null) {
            document.getElementById('modal-fishing_end_time').innerHTML = info.event.extendedProps.fishing_end_time.slice(0, -3);
        }
        document.getElementById('modal-detail').innerHTML = info.event.extendedProps.detail;
        popup.style.display = 'block';
        if (userId !== authUserId) {
            private.remove();
        }

        document.getElementById("editEventLink").addEventListener('click', function() {
            window.location.href = `/users/${userId}/event/${info.event.id}/editEvent`;
        });

        document.getElementById("deleteEvent").addEventListener('click', function() {
            const deleteConfirm = confirm('本当に削除しますか？');

            if (deleteConfirm) {
                $.ajax({
                    url: `/users/${userId}/event/${info.event.id}/ajax/deleteEvent`,
                    type: "POST",
                    data: {
                        id: info.event.id,
                        type: 'delete'
                    },
                    success: function () {
                        calendar.refetchEvents();
                        alert('イベントを削除しました');
                    }
                });
            }
        });
    }

});