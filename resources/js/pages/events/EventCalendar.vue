<template>
    <div class="container">
        <div class="row">

            <FullCalendar
                class="mx-auto d-block col-lg-8 calendar"
                :options="calendarOptions"
            />

            <!-- @include('events.modal') -->

            <div class="mx-auto d-block col-lg-4 event_form_body" v-if="id == AuthUser.id">
                <h4>釣りを記録しよう</h4>
                <div class="event_form">
                    <EventForm />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { OK } from '../../util'
    import EventForm from '../../components/events/EventForm.vue'
    import FullCalendar from '@fullcalendar/vue'
    import dayGridPlugin from '@fullcalendar/daygrid'
    import interactionPlugin from '@fullcalendar/interaction'

    export default {
        components: {
            EventForm,
            FullCalendar
        },
        props: {
            id: {
                type: String,
                required: true
            }
        },
        data () {
            return {
                calendarOptions: {
                    plugins: [ dayGridPlugin, interactionPlugin ],
                    initialView: 'dayGridMonth',
                    defaultView: 'dayGridMonth',
                    editable: true,
                    contentHeight: 'auto',
                    locale: "ja",
                    timeZone: 'Asia/Tokyo',
                    eventDurationEditable: false,
                    selectLongPressDelay: 0,
                    buttonText: {
                        today: '今日'
                    },
                    dayCellContent: function (e) {
                        e.dayNumberText = e.dayNumberText.replace('日', '');
                    },
                }
            }
        },
        computed: {
            AuthUser () {
                return this.$store.getters['auth/AuthUser']
            },
        },
        methods: {
            async fetchUserId () {
                const response = await axios.get(`/api/spots/${ this.id }/events`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.spot = response.data[0]
            },
        },
        watch: {
            $route: {
                async handler () {
                    await this.fetchUserId()
                },
                immediate: true
            }
        }
    }
</script>