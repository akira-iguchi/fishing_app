function addEvent(calendar){

    const date = document.eventForm.date.value,
        fishingType = document.eventForm.fishing_type.value,
        spot = document.eventForm.spot.value,
        bait = document.eventForm.bait.value,
        weather = document.eventForm.weather.value,
        fishingStartTime = document.eventForm.fishing_start_time.value,
        fishingEndTime = document.eventForm.fishing_end_time.value,
        detail = document.eventForm.detail.value;
    $.ajax({
        url: '/ajax/addEvent',
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
    }).done(function(result) {
        calendar.addEvent({
            id: result['id'],
            fishingType: fishingType,
            start: date,
        });

    });
}

function editEvent(info){
    location.href = 'https://qiita.com/y-temp4/items/94727de9f2029a357e09';
}

function editEventDate(info){
    const event_id = info.event.id;
    const date = formatDate(info.event.start);

    $.ajax({
        url: '/ajax/editEventDate',
        type: 'POST',
        data:{
            "id":event_id,
            "newDate":date
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
