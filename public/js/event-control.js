let userId = document.getElementById('js-getUserId').dataset.name,
    authUserId = document.getElementById('js-getAuthUserId').dataset.name;

function addEvent() {
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
    }).done(function(data, calendar) {
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

        calendar.addEvent({
            id: data['id'],
            fishingType: data.fishingType,
            start: data.date,
        });

    });
}

function showEvent(info) {
    const popup = document.querySelector('.popup-wrapper'),
        private = document.querySelector('.event_private');
    document.getElementById('modal-date').innerHTML = info.event.start.toLocaleDateString();
    document.getElementById('modal-spot').innerHTML = info.event.extendedProps.spot;
    document.getElementById('modal-fishing_type').innerHTML = info.event.title;
    document.getElementById('modal-bait').innerHTML = info.event.extendedProps.bait;
    document.getElementById('modal-weather').innerHTML = info.event.extendedProps.weather;
    document.getElementById('modal-fishing_start_time').innerHTML = info.event.extendedProps.fishing_start_time;
    document.getElementById('modal-fishing_end_time').innerHTML = info.event.extendedProps.fishing_end_time;
    document.getElementById('modal-detail').innerHTML = info.event.extendedProps.detail;
    popup.style.display = 'block';
    if (userId === authUserId) {
        private.style.display = 'block';
    }
}

const popup = document.querySelector('.popup-wrapper');
const close = document.querySelector('.popup-close');
close.addEventListener('click', () => {
    popup.style.display = 'none';
});

popup.addEventListener('click', () => {
    popup.style.display = 'none';
});

function editEventDate(info) {
    const event_id = info.event.id;
    const newDate = formatDate(info.event.start);

    $.ajax({
        url: `/users/${userId}/ajax/editEventDate`,
        type: 'POST',
        data:{
            "id": event_id,
            "newDate": newDate
        }
    })
}

function deleteEvent() {
    $.ajax({
        url: `/users/${authUserId}/event/${eventId.dataset.name}/ajax/editEventDate`,
        type: "POST",
        data: {
                id: eventId.dataset.name,
                type: 'delete'
        },
        success: function () {
            alert('イベントを削除しました');
        }
    });
}

function formatDate(date) {
    const year = date.getFullYear();
    const month = date.getMonth() + 1;
    const day = date.getDate();
    const newDate = year + '-' + month + '-' + day;
    return newDate;
}