<template>
    <div class="contact_body">
        <div class="container">
            <div class="contact_header">
                <h1>お問い合わせ</h1>
            </div>

            <div class="col-lg-6 contact_form">

                <div v-show="loading" class="mt-3">
                    <Loader />
                </div>

                <form @submit.prevent="confirmContact">

                    <label for="email">メールアドレス</label>
                    <input
                        id="email"
                        type="email"
                        class="form-control"
                        autocomplete="email"
                        autofocus
                        v-model="contactForm.email"
                        required
                    >
                    <div v-if="contactErrors">
                        <ul v-if="contactErrors.email">
                            <li
                                class="text-danger"
                                v-for="msg in contactErrors.email"
                                :key="msg"
                            ><p>{{ msg }}</p>
                            </li>
                        </ul>
                    </div>

                    <label for="title">タイトル</label>
                    <input
                        id="title"
                        type="text"
                        class="form-control"
                        autocomplete="title"
                        v-model="contactForm.title"
                        required
                    >
                    <div v-if="contactErrors">
                        <ul v-if="contactErrors.title">
                            <li
                                class="text-danger"
                                v-for="msg in contactErrors.title"
                                :key="msg"
                            ><p>{{ msg }}</p>
                            </li>
                        </ul>
                    </div>

                    <label for="body">お問い合わせ内容</label>
                    <div v-if="500 < wordCount" v-on="changeTrue()"></div>
                    <div v-else-if="500 >= wordCount" v-on="changeFalse()"></div>
                    <textarea
                        rows="4"
                        id="body"
                        class="form-control"
                        v-on:keydown.enter="$event.stopPropagation()"
                        v-model="contactForm.body"
                        required
                    ></textarea>
                    <p class="text_limit">
                        <span
                            v-bind:class="{ 'text-danger':isActive }"
                        >{{ wordCount }}
                        </span>/500
                    </p>
                    <div v-if="contactErrors">
                        <ul v-if="contactErrors.body">
                            <li
                                class="text-danger"
                                v-for="msg in contactErrors.body"
                                :key="msg"
                            ><p>{{ msg }}</p>
                            </li>
                        </ul>
                    </div>

                    <button type="submit" class="contact_button">
                        入力内容確認
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
                contactForm: {
                    email: "",
                    title: "",
                    body: ""
                },
                contactErrors: null,
                loading: false,
            }
        },
        computed: {
            wordCount () {
                return this.contactForm.body.length
            },
        },
        watch: {
            $route: {
                async handler () {
                    await this.fetchContact()
                },
                immediate: true
            }
        },
        methods: {
            async fetchContact () {
                const response = await axios.get(`/api/contact`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                // 確認ページから戻った場合データを取得
                this.contactForm.email = this.$route.params.email || ""
                this.contactForm.title = this.$route.params.title || ""
                this.contactForm.body = this.$route.params.body || ""
            },
            async confirmContact () {
                this.loading = true
                const response = await axios.post('/api/contact/confirm/send', this.contactForm)

                this.loading = false

                if (response.status === UNPROCESSABLE_ENTITY) {
                    this.contactErrors = response.data.errors
                    return false
                }

                this.$router.push({
                    name: 'contact.confirm',
                    params: {
                        email: this.contactForm.email,
                        title: this.contactForm.title,
                        body: this.contactForm.body,
                    }
                })
            },
            // 文字数
            changeTrue:function () {
                this.isActive = true
            },
            changeFalse:function () {
                this.isActive = false
            },
        },
    }
</script>