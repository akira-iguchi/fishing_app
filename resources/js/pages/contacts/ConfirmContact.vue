<template>
    <div class="contact_body">
        <div class="container">
            <div class="contact_header">
                <h1>お問い合わせ内容確認</h1>
            </div>

            <div class="col-lg-8 contact_form">

                <div v-show="loading" class="mt-3">
                    <Loader />
                </div>

                <form @submit.prevent="sendContact">

                    <table class="contact_table">
                        <tr>
                            <th>メールアドレス</th>
                            <td>{{ contactForm.email }}</td>
                            <input
                                type="hidden"
                                v-model="contactForm.email"
                                required
                            >
                        </tr>
                        <tr>
                            <th>タイトル</th>
                            <td>{{ contactForm.title }}</td>
                            <input
                                type="hidden"
                                v-model="contactForm.title"
                                required
                            >
                        </tr>
                        <tr>
                            <th>お問い合わせ内容</th>
                            <td class="contact_table_body">
                                <span v-html="contactForm.body.replace(/\n/g,'<br/>')">
                                    {{ contactForm.body }}
                                </span>
                            </td>
                            <textarea
                                type="hidden"
                                v-model="contactForm.body"
                                required
                            ></textarea>
                        </tr>
                    </table>

                    <button type="submit" class="contact_button">
                        送信
                    </button>
                    <button type="button" class="contact_button" @click="back">
                        入力内容修正
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import { OK, UNPROCESSABLE_ENTITY } from '../../util'
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
            // 前のページにデータを送る
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
                const response = await axios.post('/api/contact/send', {
                    email: this.contactForm.email,
                    title: this.contactForm.title,
                    body: this.contactForm.body,
                })

                this.loading = false

                if (response.status === UNPROCESSABLE_ENTITY) {
                    this.contactErrors = response.data.errors
                    return false
                }

                this.$router.push('/contact/thanks')
            },
        },
    }
</script>