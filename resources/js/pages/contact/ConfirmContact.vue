<template>
    <div class="login_body">
        <div class="container">
            <div class="row justify-content-center">
                <div class="login-signup">
                    <div class="login-signup-header">
                        <h1>お問い合わせ内容の確認</h1>
                    </div>

                    <div class="login-signup-form">

                        <div v-show="loading" class="mt-3">
                            <Loader />
                        </div>

                        <form @submit.prevent="sendContact">

                            <table class="contact_table">
                                <tr>
                                    <td>メールアドレス</td>
                                    <td class="contact_value">{{ contactForm.email }}</td>
                                    <input
                                        type="hidden"
                                        v-model="contactForm.email"
                                        required
                                    >
                                </tr>
                                <tr>
                                    <td>タイトル</td>
                                    <td class="contact_value">{{ contactForm.title }}</td>
                                    <input
                                        type="hidden"
                                        v-model="contactForm.title"
                                        required
                                    >
                                </tr>
                                <tr>
                                    <td>お問い合わせ内容</td>
                                    <td
                                        class="contact_value"
                                        v-html="contactForm.body.replace(/\n/g,'<br/>')"
                                    >{{ contactForm.body }}</td>
                                    <textarea
                                        type="hidden"
                                        v-model="contactForm.body"
                                        required
                                    ></textarea>
                                </tr>
                            </table>

                            <button type="submit" class="login-signup-button">
                                送信
                            </button>
                        </form>
                    </div>
                    <button class="login-signup-button back" @click="back">
                        入力内容修正
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../../util'
    import Loader from '../../components/commons/Loader.vue'

    export default {
        components: {
            Loader,
        },
        data () {
            return {
                contactData: {},
                contactForm: {
                    email: "",
                    title: "",
                    body: ""
                },
                loading: false,
            }
        },
        watch: {
            $route: {
                async handler () {
                    await this.fetchContactConfirm()
                },
                immediate: true
            }
        },
        methods: {
            async fetchContactConfirm () {
                const response = await axios.get(`/api/contact/confirm`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.contactForm.email = this.$route.params.email
                this.contactForm.title = this.$route.params.title
                this.contactForm.body = this.$route.params.body

                if (!this.contactForm.email) {
                    this.$router.push('/contact')

                    this.$store.commit('message/setContent', {
                        content: '入力に不備が見られました',
                        timeout: 4000
                    })
                }
            },
            back () {
                this.$router.push({
                    name: 'contact',
                    params: {
                        email: this.contactForm.email,
                        title: this.contactForm.title,
                        body: this.contactForm.body,
                    }
                })
            },
            async sendContact () {
                this.loading = true
                const response = await axios.post('/api/contact/thanks', this.contactForm)

                this.loading = false

                if (response.status === UNPROCESSABLE_ENTITY) {
                    this.contactErrors = response.data.errors
                    return false
                }
            },
        },
    }
</script>