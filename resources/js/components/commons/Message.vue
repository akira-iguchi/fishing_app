<template>
    <div class="flash_message" v-show="message">
        {{ message }}
    </div>
</template>

<script>
    import { mapState } from 'vuex'

    export default {
            computed: {
                ...mapState({
                    message: state => state.message.content
            })
        }
    }
</script>