<template>
    <div class="popup-wrapper" @click="closeModal">
        <div class="popup">
            <div class="popup-close" @click="closeModal">✕</div>
            <div class="popup-content">
                <h2 id="modal-date"></h2>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th>釣り場</th>
                            <td>{{ event.title }}</td>
                        </tr>
                        <tr>
                            <th>釣り方</th>
                            <td>{{ event.extendedProps.fishing_type }}</td>
                        </tr>
                        <tr>
                            <th>時間</th>
                            <td>
                                <span>
                                    {{ event.extendedProps.fishing_start_time.slice(0, -3) }}
                                </span>
                                〜
                                <span>
                                    {{ event.extendedProps.fishing_end_time.slice(0, -3) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>詳細</th>
                            <td class="modal_detail">{{ event.extendedProps.detail }}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="event_private">
                    <button class="edit_link_button">
                        <RouterLink :to="`/users/${ userData.id }/events/${ event.id }/edit`">編集</RouterLink>
                    </button>
                    <button type="button" class="delete_button">削除</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
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
        computed: {
        },
        watch: {
        },
        methods: {
            closeModal () {
                this.$emit("closeModal")
            }
        },
    }
</script>