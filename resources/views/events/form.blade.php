<div class="mx-auto d-block col-lg-4 event_form_body">
    <h4>釣りを記録しよう</h4>
    <div class="event_form">
        <form action="" name="eventForm">
            @csrf

            <div class="date">
                <label class="required">日付</label>
                <input type="date" name="date">
            </div>
            <span class="error_msg">
                <p id="date_error"></p>
            </span>

            <div class="date">
                <label>時間</label>
                <input type="time" name="fishing_start_time">〜<input type="time" name="fishing_end_time">
            </div>

            <div class="form-field">
                <label class="required">釣り方</label>
                <input type="text" class="input-text" name="fishing_type" placeholder="例）サビキ釣り">
            </div>
            <span class="error_msg">
                <p id="fishing_type_error"></p>
            </span>

            <div class="form-field">
                <label class="required">釣り場</label>
                <input type="text" class="input-text" name="spot" placeholder="例）かもめ大橋">
            </div>
            <span class="error_msg">
                <p id="spot_error"></p>
            </span>

            <div class="form-field">
                <label class="event_label">エサ</label>
                <input type="text" class="input-text" name="bait" placeholder="例）アミエビ">
            </div>
            <span class="error_msg">
                <p id="bait_error"></p>
            </span>

            <div class="form-field">
                <label class="event_label">天気</label>
                <input type="text" class="input-text" name="weather" placeholder="例）晴れ">
            </div>
            <span class="error_msg">
                <p id="weather_error"></p>
            </span>

            <div class="form-field">
                <label class="event_label">詳細</label><br>
                <textarea rows="6" id="textArea" class="form-input-text_area" name="detail" placeholder="例） アジがたくさん釣れた。"></textarea>
                残り<span id="textLest">100</span>文字
                <p id="textAttention" style="display:none; color:red;">入力文字数が多すぎます。</p>
            </div>
            <span class="error_msg">
                <p id="detail_error"></p>
            </span>

            <div>
                <button type='button' class="spot-create-edit-button" onclick="addEvent()"><i class="fas fa-pencil-alt"></i>&thinsp;投稿</button>
            </div>
        </div>

    </div>
</div>