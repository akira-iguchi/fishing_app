<div class="popup-wrapper">
    <div class="popup">
        <div class="popup-close">✕</div>
        <div class="popup-content">
            <h2 id="modal-date"></h2>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th>釣り場</th>
                        <td id="modal-spot"></td>
                    </tr>
                    <tr>
                        <th>釣り方</th>
                        <td id="modal-fishing_type"></td>
                    </tr>
                    <tr>
                        <th>エサ</th>
                        <td id="modal-bait"></td>
                    </tr>
                    <tr>
                        <th>天気</th>
                        <td id="modal-weather"></td>
                    </tr>
                    <tr>
                        <th>時間</th>
                        <td>
                            <span id="modal-fishing_start_time"></span> 〜 <span id="modal-fishing_end_time"></span>
                        </td>
                    </tr>
                    <tr>
                        <th>詳細</th>
                        <td id="modal-detail"></td>
                    </tr>
                </tbody>
            </table>

            <div class="event_private">
                <button class="event_edit_button"><a href="" class="edit_link_button">編集</a></button>
                <button type="button" class="delete_button" onclick="deleteEvent()">削除</button>
            </div>
        </div>
    </div>
</div>