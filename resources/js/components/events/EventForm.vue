<template>
    <form>
        <div class="date">
            <label class="required">日付</label>
            <input type="date" placeholder="例）2021-03-27" v-model="date" required>
        </div>
        <!-- @if($errors->has('date'))
            <span class="error_msg">
                <p id="date_error">{{ $errors->first('date') }}</p>
            </span>
        @endif
        <span class="error_msg">
            <p id="date_error"></p>
        </span> -->

        <div class="date">
            <label>時間</label>
            <input type="time" placeholder="例）07:10" v-model="fishingStartTime">
            〜
            <input type="time" placeholder="例）17:30" v-model="fishingEndTimeime">
        </div>

        <div class="form-field">
            <label class="required">釣り方</label>
            <input
                type="text"
                class="input-text"
                placeholder="例）サビキ釣り"
                v-model="fishingType"
                required
            >
        </div>

        <div class="form-field">
            <label class="required">釣り場</label>
            <input
                type="text"
                class="input-text"
                placeholder="例）かもめ大橋"
                v-model="spot"
                required
            >
        </div>

        <div class="form-field">
            <div v-if="0 > wordCount" v-on="changeTrue()"></div>
            <div v-else-if="0 <= wordCount" v-on="changeFalse()"></div>
            <label class="event_label">詳細</label><br>
            <textarea
                rows="5"
                class="form-input-text_area"
                v-model="detail"
                placeholder="例） アジがたくさん釣れた。"
            ></textarea>
            <p>残り<span v-bind:class="{ 'text-danger':isActive }">{{ wordCount }}</span>文字</p>
        </div>

        <div>
            <button type='button' class="spot-create-edit-button" @click="getEvent">
                <i class="fas fa-pencil-alt"></i>&thinsp;投稿
            </button>
        </div>
    </form>
</template>

<script>
    export default {
        props: {
        },
        data () {
            return {
                date: "",
                fihsingStartTime: "",
                fihsingEndTime: "",
                fihsingType: "",
                spot: "",
                detail: "",
                wordLimit: 100,
            }
        },
        computed: {
            wordCount () {
                return this.wordLimit - this.detail.length
            },
        },
        methods: {
            changeTrue: function() {
                this.isActive = true
            },
            changeFalse: function() {
                this.isActive = false
            },
            getEvent () {
                this.$emit("getEvent", [
                    this.date,
                    this.fihsingStartTime,
                    this.fihsingEndTime,
                    this.fihsingType,
                    this.spot,
                    this.detail,
                ])
            }
        },
    }
</script>