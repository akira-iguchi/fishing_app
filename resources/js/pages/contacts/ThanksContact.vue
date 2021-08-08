<template>
    <div class="contact_body">
        <div class="container">
            <div class="col-lg-6 contact_form">
                <h2 class="text-center">お問い合わせ 送信完了</h2>
                <hr>
                <p>
                    お問い合わせありがとうございました。<br>
                    内容を確認のうえ、回答させて頂きます。<br>
                    しばらくお待ちください。
                </p>
                <RouterLink to="/contact">
                    <button type="button" class="contact_button">お問い合わせに戻る</button>
                </RouterLink>
            </div>
        </div>
    </div>
</template>

<script>
    import { OK } from '../../util'
    export default {
        watch: {
            $route: {
                async handler () {
                    await this.fetchContactThanks()
                },
                immediate: true
            }
        },
        methods: {
            async fetchContactThanks () {
                const response = await axios.get('/api/contact/thanks')

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }
            },
        },
    }
</script>