<template>
    <div class="login_body">
        <div class="container">
            <div class="row justify-content-center">
                <div class="login-signup">
                    <div class="login-signup-header">
                        <h1>ログイン</h1>
                    </div>

                    <div class="login-signup-form">
                        <form  @submit.prevent="login">
                            <label for="email">メールアドレス</label>
                            <input id="email" type="email" class="form-control" autocomplete="email" autofocus v-model="loginForm.email">

                            <label for="password">パスワード</label>
                            <div class="login-signup-password">
                                <input id="password" :type="inputType" class="form-control" autocomplete="current-password" v-model="loginForm.password">
                                <input class="password_toggle" type="checkbox" @click="inputChange">
                                <div class="password_label"><i :class="iconType"></i></div>
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

                this.$router.push('/')
            },
            inputChange: function() {
                this.isChecked = !this.isChecked;
            }
        }
    }
</script>