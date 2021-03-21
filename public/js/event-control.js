function addEvent(){

    let date = document.eventForm.date.value,
        fishingType = document.eventForm.fishing_type.value,
        spot = document.eventForm.spot.value,
        bait = document.eventForm.bait.value,
        weather = document.eventForm.weather.value,
        fishingStartTime = document.eventForm.fishing_start_time.value,
        fishingEndTime = document.eventForm.fishing_end_time.value,
        detail = document.eventForm.detail.value,
        userId = document.getElementById('js-getUserId');
    $.ajax({
        url: `/users/${userId.dataset.name}/ajax/addEvent`,
        type: 'POST',
        dataTape: 'json',
        data:{
            "date": date,
            "fishing_type": fishingType,
            "spot": spot,
            "bait": bait,
            "weather": weather,
            "fishing_start_time": fishingStartTime,
            "fishing_end_time": fishingEndTime,
            "detail": detail,
        }
    }).fail(function(data) {
        document.getElementById("date_error").innerHTML = data.responseJSON.errors.date || "";
        document.getElementById("fishing_type_error").innerHTML = data.responseJSON.errors.fishing_type || "";
        document.getElementById("spot_error").innerHTML = data.responseJSON.errors.spot || "";
        document.getElementById("bait_error").innerHTML = data.responseJSON.errors.bait || "";
        document.getElementById("weather_error").innerHTML = data.responseJSON.errors.weather || "";
        document.getElementById("detail_error").innerHTML = data.responseJSON.errors.detail || "";
    }).done(function(data, result) {
        document.eventForm.date.value = "";
        document.eventForm.fishing_type.value = "";
        document.eventForm.spot.value = "";
        document.eventForm.bait.value = "";
        document.eventForm.weather.value = "";
        document.eventForm.fishing_start_time.value = "";
        document.eventForm.fishing_end_time.value = "";
        document.eventForm.detail.value = "";
        console.log(data.date);
        calendar.addEvent({
            id: result['id'],
            fishingType: fishingType,
            start: date,
        });

    });
}

function editEvent(info){
    MicroModal.show('modal-1');
}

function editEventDate(info){
    const event_id = info.event.id;
    var date = formatDate(info.event.start);

    $.ajax({
        url: '/users/1/ajax/editEventDate',
        type: 'POST',
        data:{
            "id": event_id,
            "newDate": date
        }
    })
}

function formatDate(date) {
    const year = date.getFullYear();
    const month = date.getMonth() + 1;
    const day = date.getDate();
    const newDate = year + '-' + month + '-' + day;
    return newDate;
}