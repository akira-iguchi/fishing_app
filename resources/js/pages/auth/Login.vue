<template>
    <div class="login_body">
        <div class="container">
            <div class="row justify-content-center">
                <div class="login_signup">
                    <div class="login_signup_header">
                        <h1>ログイン</h1>
                    </div>

                    <div class="login_signup_form">
                        <form  @submit.prevent="login">
                            <label for="email">メールアドレス</label>
                            <input
                                id="email"
                                type="email"
                                class="form-control"
                                autocomplete="email"
                                autofocus
                                required
                                v-model="loginForm.email"
                            >
                            <div v-if="loginErrors">
                                <ul v-if="loginErrors.email">
                                    <li
                                        class="text-danger"
                                        v-for="msg in loginErrors.email"
                                        :key="msg"
                                    ><p>{{ msg }}</p></li>
                                </ul>
                            </div>

                            <label for="password">パスワード</label>
                            <div class="login-signup-password">
                                <input
                                    id="password"
                                    :type="inputType"
                                    class="form-control"
                                    autocomplete="current-password"
                                    required
                                    v-model="loginForm.password"
                                >
                                <input class="password_toggle" type="checkbox" @click="inputChange">
                                <div class="password_label"><i :class="iconType"></i></div>
                            </div>
                            <div v-if="loginErrors">
                                <ul v-if="loginErrors.password">
                                    <li
                                        class="text-danger"
                                        v-for="msg in loginErrors.password"
                                        :key="msg"
                                    ><p>{{ msg }}</p></li>
                                </ul>
                            </div>


                            <button type="submit" class="login-signup-button">
                                ログイン
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex'

    export default {
        data () {
            return {
                loginForm: {
                    email: '',
                    password: ''
                },
                isChecked: false,
            }
        },

        computed: {
            ...mapState({
                apiStatus: state => state.auth.apiStatus,
                loginErrors: state => state.auth.loginErrorMessages
            }),
            inputType: function () {
                return this.isChecked ? "text" : "password";
            },
            iconType: function () {
                return this.isChecked ? "fas fa-eye-slash fa-lg" : "fas fa-eye fa-lg";
            }
        },

        methods: {
            async login () {
                await this.$store.dispatch('auth/login', this.loginForm)
                window.scrollTo(0, 0)

                if (this.apiStatus) {
                    this.$router.push('/')
                    this.$store.commit('message/setContent', {
                        content: 'ログインしました',
                        timeout: 5000
                    })
                }

            },
            clearError () {
                this.$store.commit('auth/setLoginErrorMessages', null)
            },
            inputChange: function() {
                this.isChecked = !this.isChecked;
            }
        },
        created () {
            this.clearError()
        }
    }
</script>