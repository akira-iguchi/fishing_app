function addEvent(calendar,info){

    var title = "サンプルベント";
    $.ajax({
        url: '/ajax/addEvent',
        type: 'POST',
        dataTape: 'json',
        data:{
            "title":title,
            "date":info.dateStr
        }
    }).done(function(result) {
        calendar.addEvent({
            id:result['event_id'],
            title:title,
            start: info.dateStr,
        });

    });
}

function editEvent(info){
    location.href = 'https://qiita.com/y-temp4/items/94727de9f2029a357e09';
}

function editEventDate(info){
    var event_id = info.event.id;
    var date = formatDate(info.event.start);

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
    var year = date.getFullYear();
    var month = date.getMonth() + 1;
    var day = date.getDate();
    var newDate = year + '-' + month + '-' + day;
    return newDate;
}
