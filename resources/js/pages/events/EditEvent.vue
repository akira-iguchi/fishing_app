<template>
    <div class="container">
        <div class="row">

            <FullCalendar
                class="mx-auto d-block col-lg-8 calendar"
                :options="calendarOptions"
            />

            <EventModal
                :editEventData="event"
                :eventData="eventData"
                :userData="user"
                v-if="popup"
                @closeModal="closeModal"
                @deleteEvent="deleteEvent"
            />

            <div class="mx-auto d-block col-lg-4 event_form_body" v-if="userId == AuthUser.id">
                <h4>釣りを記録しよう</h4>
                <div class="event_form">
                    <EventForm
                        v-if="eventDataLoaded"
                        :intialEventValue="event"
                        :errors="errors"
                        :deleteInput="deleteInput"
                        @getEvent="editEvent"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../../util'
    import EventForm from '../../components/events/EventForm.vue'
    import EventModal from '../../components/events/EventModal.vue'
    import FullCalendar from '@fullcalendar/vue'
    import dayGridPlugin from '@fullcalendar/daygrid'
    import interactionPlugin from '@fullcalendar/interaction'

    export default {
        components: {
            EventForm,
            EventModal,
            FullCalendar,
        },
        props: {
            userId: {
                type: String,
                required: true
            },
            eventId: {
                type: String,
                required: true
            }
        },
        data () {
            return {
                calendarOptions: {
                    plugins: [
                        dayGridPlugin,
                        interactionPlugin,
                    ],
                    initialView: 'dayGridMonth',
                    defaultView: 'dayGridMonth',
                    editable: true,
                    droppable: true,
                    contentHeight: 'auto',
                    locale: "ja",
                    timeZone: 'Asia/Tokyo',
                    eventDurationEditable: false,
                    selectLongPressDelay: 0,
                    buttonText: {
                        today: '今日'
                    },
                    dayCellContent (e) {
                        e.dayNumberText = e.dayNumberText.replace('日', '');
                    },
                    events: {},
                    eventClick: this.popupModal,
                    eventDrop: this.editEventDate,
                },
                eventDataLoaded: false,
                user: {},
                event: {},
                errors: null,
                popup: false,
                eventData: {},
                deleteInput: false,
            }
        },
        computed: {
            AuthUser () {
                return this.$store.getters['auth/AuthUser']
            },
        },
        watch: {
            $route: {
                async handler () {
                    await this.fetchEditEvent()
                },
                immediate: true
            }
        },
        methods: {
            async fetchEditEvent () {
                const response = await axios.get(`/api/users/${ this.userId }/events/${ this.eventId }/edit`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                if (this.AuthUser.id !== Number(this.userId)) {
                    this.$router.push('/')
                    this.$store.commit('message/setContent', {
                        content: '他のユーザーのイベントは編集できません',
                        timeout: 4000
                    })
                    return false
                }

                this.user = response.data[0]
                this.event = response.data[1]
                this.calendarOptions.events = response.data[2]

                this.eventDataLoaded = true
            },
            async editEvent (data) {
                const formData = new FormData()
                formData.append('date', data[0])
                formData.append('fishing_start_time', data[1])
                formData.append('fishing_end_time', data[2])
                formData.append('fishing_type', data[3])
                formData.append('spot', data[4])
                formData.append('detail', data[5])
                const response = await axios.post(`/api/users/${ this.userId }/events/${ this.eventId }`, formData, {
                    // PUTに変換
                    headers: {
                        'X-HTTP-Method-Override': 'PUT'
                    }
                })

                if (response.status === UNPROCESSABLE_ENTITY) {
                    this.errors = response.data.errors
                    return false
                }

                if (response.status !== CREATED) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.errors = null
                this.deleteInput = true

                this.$store.commit('message/setContent', {
                    content: 'イベントを投稿しました',
                    timeout: 4000
                })

                this.$router.push(`/users/${ this.userId }/events/`)
            },
            popupModal (info) {
                this.popup = true
                this.eventData = info.event
            },
            closeModal () {
                this.popup = false
            },
            async editEventDate (info) {
                const event_id = info.event.id,
                    newDate = this.formatDate(info.event.start)

                const response = await axios.post(`/api/users/${ this.userId }/editEventDate`, {
                    id: event_id,
                    newDate: newDate
                }, {
                    // PUTに変換
                    headers: {
                        'X-HTTP-Method-Override': 'PUT'
                    }
                })

                if (response.status === UNPROCESSABLE_ENTITY) {
                    return false
                }

                this.$store.commit('message/setContent', {
                    content: 'イベントの日時を更新しました',
                    timeout: 4000
                })
            },
            async deleteEvent (eventId) {
                const response = await axios.delete(`/api/users/${ this.id }/events/${ eventId }`)

                this.$store.commit('message/setContent', {
                    content: 'イベントを削除しました',
                    timeout: 4000
                })

                this.fetchEditEvent()
            },
            formatDate (date) {
                const year = date.getFullYear();
                const month = date.getMonth() + 1;
                const day = date.getDate();
                const newDate = year + '-' + month + '-' + day;
                return newDate;
            },
        },
    }
</script>