<template>
    <div class="popup-wrapper">
        <div class="popup">
            <div class="popup-close" @click="closeModal">✕</div>
            <div class="popup-content">
                <h2>{{ event.startStr | moment }}</h2>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th>釣り方</th>
                            <td>{{ event.extendedProps.fishing_type }}</td>
                        </tr>
                        <tr>
                            <th>釣り場</th>
                            <td>{{ event.title }}</td>
                        </tr>
                        <tr v-if="event.extendedProps.fishing_start_time || event.extendedProps.fishing_end_time">
                            <th>時間</th>
                            <td>
                                <span>
                                    {{ event.extendedProps.fishing_start_time }}
                                </span>
                                〜
                                <span>
                                    {{ event.extendedProps.fishing_end_time }}
                                </span>
                            </td>
                        </tr>
                        <tr v-if="event.extendedProps.detail">
                            <th>詳細</th>
                            <td class="modal_detail">{{ event.extendedProps.detail }}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="event_private">
                    <RouterLink :to="`/users/${ userData.id }/events/${ eventData.id }/edit`">
                        <button class="edit_link_button">編集</button>
                    </RouterLink>

                    <!-- 編集しているイベントとモーダルのイベントが同じだと非表示 -->
                    <button
                        class="delete_button"
                        v-if="editEventData.id !== Number(eventData.id)"
                        @click="deleteEvent"
                    >削除</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';

    export default {
        props: {
            editEventData: {
                type: Object,
                required: true,
            },
            eventData: {
                type: Object,
                required: true,
            },
            userData: {
                type: Object,
                required: true,
            },
        },
        data () {
            return {
                event: this.eventData,
            }
        },
        filters: {
            moment: function (date) {
                return moment(date).format('YYYY/MM/DD');
            }
        },
        methods: {
            closeModal () {
                this.$emit("closeModal")
            },
            deleteEvent () {
                if (confirm('本当に削除しますか？')) {
                    this.$emit("deleteEvent", this.eventData.id)
                }
            }
        },
    }
</script>