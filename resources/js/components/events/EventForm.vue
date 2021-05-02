<template>
    <form>
        <div class="date">
            <label class="required">日付</label>
            <input type="date" placeholder="例）2021-03-27" v-model="date" required>
        </div>
        <div v-if="errors">
            <ul class="event_errors" v-if="errors.date">
                <li class="text-danger" v-for="msg in errors.date" :key="msg">{{ msg }}</li>
            </ul>
        </div>

        <div class="date">
            <label>時間</label>
            <input type="time" placeholder="例）07:10" v-model="fishingStartTime">
            〜
            <input type="time" placeholder="例）17:30" v-model="fishingEndTimeime">
        </div>
        <div v-if="errors">
            <ul class="event_errors" v-if="errors.fishing_start_time">
                <li class="text-danger" v-for="msg in errors.fishing_start_time" :key="msg">{{ msg }}</li>
            </ul>
            <ul class="event_errors" v-if="errors.fishing_end_time">
                <li class="text-danger" v-for="msg in errors.fishing_end_time" :key="msg">{{ msg }}</li>
            </ul>
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
        <div v-if="errors">
            <ul class="event_errors" v-if="errors.fishing_type">
                <li class="text-danger" v-for="msg in errors.fishing_type" :key="msg">{{ msg }}</li>
            </ul>
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
        <div v-if="errors">
            <ul class="event_errors" v-if="errors.spot">
                <li class="text-danger" v-for="msg in errors.spot" :key="msg">{{ msg }}</li>
            </ul>
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
        <div v-if="errors">
            <ul class="event_errors" v-if="errors.detail">
                <li class="text-danger" v-for="msg in errors.detail" :key="msg">{{ msg }}</li>
            </ul>
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
            intialEventValue: {
                type: Object,
                required: false,
            },
            errors: {
                type: Object,
                required: false,
            },
            deleteInput: {
                type: Boolean,
                default: false
            },
        },
        data () {
            return {
                event: this.intialEventValue,
                date: "",
                fishingStartTime: "",
                fishingEndTimeime: "",
                fishingType: "",
                spot: "",
                detail: "",
                wordLimit: 100,
            }
        },
        mounted () {
            function isEmpty(obj){
                return !Object.keys(obj).length;
            }

            if (!isEmpty(this.event)) {
                this.date = this.event.date
                this.fishingStartTime = this.event.fishing_start_time
                this.fishingEndTimeime = this.event.fishing_end_time
                this.fishingType = this.event.fishing_type
                this.spot = this.event.spot
                this.detail = this.event.detail
            }
        },
        computed: {
            wordCount () {
                return this.wordLimit - this.detail.length
            },
        },
        watch: {
            deleteInput () {
                this.date = ""
                this.fishingStartTime = ""
                this.fishingEndTimeime = ""
                this.fishingType = ""
                this.spot = ""
                this.detail = ""
            }
        },
        methods: {
            changeTrue () {
                this.isActive = true
            },
            changeFalse () {
                this.isActive = false
            },
            getEvent () {
                this.$emit("getEvent", [
                    this.date,
                    this.fishingStartTime,
                    this.fishingEndTimeime,
                    this.fishingType,
                    this.spot,
                    this.detail,
                ])
            }
        },
    }
</script>