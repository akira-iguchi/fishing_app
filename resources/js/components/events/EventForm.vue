<template>
    <form @submit.prevent="getEvent">
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
            <div v-if="100 < wordCount" v-on="changeTrue()"></div>
            <div v-else-if="100 >= wordCount" v-on="changeFalse()"></div>
            <label class="event_label">詳細</label><br>
            <textarea
                rows="5"
                class="form-input-text_area"
                v-model="detail"
                placeholder="例） アジがたくさん釣れた。"
            ></textarea>
            <p class="text_limit">
                <span
                    v-bind:class="{ 'text-danger':isActive }"
                >{{ wordCount }}
                </span>/100
            </p>
        </div>
        <div v-if="errors">
            <ul class="event_errors" v-if="errors.detail">
                <li class="text-danger" v-for="msg in errors.detail" :key="msg">{{ msg }}</li>
            </ul>
        </div>

        <div>
            <button class="spot-create-edit-button"  v-if="isEdit">
                <i class="fas fa-pencil-alt"></i>&thinsp;更新
            </button>

            <button class="spot-create-edit-button"  v-else>
                <i class="fas fa-pencil-alt"></i>&thinsp;投稿
            </button>
        </div>
    </form>
</template>

<script>
    export default {
        props: {
            isEdit: {
                type: Boolean,
                default: false,
            },
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
                return this.detail.length
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
            },
            intialEventValue (newEvent) {
                this.date = newEvent.date
                this.fishingStartTime = newEvent.fishing_start_time
                this.fishingEndTimeime = newEvent.fishing_end_time
                this.fishingType = newEvent.fishing_type
                this.spot = newEvent.spot
                this.detail = newEvent.detail
            },
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