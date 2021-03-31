let userId = document.getElementById('js-getUserId').dataset.name,
    authUserId = document.getElementById('js-getAuthUserId').dataset.name;

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
        type: 'PUT',
        data:{
            "id": event_id,
            "newDate": newDate
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