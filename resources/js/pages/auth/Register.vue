<template>
    <div class="signup_body">
        <div class="container">
            <div class="row justify-content-center">
                <div class="login-signup">
                    <div class="login-signup-header">
                        <h1>新規登録</h1>
                    </div>

                    <div class="login-signup-form">
                        <form @submit.prevent="register">

                            <label for="user_name" class="required">ユーザー名</label>
                            <input id="user_name" type="text" class="form-control" v-model="registerForm.user_name" autocomplete="user_name" placeholder="10文字以内" autofocus>
                            <div v-if="registerErrors">
                                <ul v-if="registerErrors.user_name">
                                    <li class="text-danger" v-for="msg in registerErrors.user_name" :key="msg"><p>{{ msg }}</p></li>
                                </ul>
                            </div>

                            <label for="email" class="required">メールアドレス</label>
                            <input id="email" type="email" class="form-control" v-model="registerForm.email" autocomplete="email">
                            <div v-if="registerErrors">
                                <ul v-if="registerErrors.email">
                                    <li class="text-danger" v-for="msg in registerErrors.email" :key="msg"><p>{{ msg }}</p></li>
                                </ul>
                            </div>

                            <label for="textArea">自己紹介</label>
                            <div v-if="0 > wordCount" v-on="changeTrue()"></div>
                            <div v-else-if="0 <= wordCount" v-on="changeFalse()"></div>
                            <textarea rows="4" id="textArea" class="form-control" v-on:keydown.enter="$event.stopPropagation()" v-model="registerForm.introduction"></textarea>
                            <p>残り<span v-bind:class="{ 'text-danger':isActive }">{{ wordCount }}</span>文字</p>
                            <div v-if="registerErrors">
                                <ul v-if="registerErrors.introduction">
                                    <li class="text-danger" v-for="msg in registerErrors.introduction" :key="msg"><p>{{ msg }}</p></li>
                                </ul>
                            </div>

                            <label for="password">パスワード</label>
                            <div class="login-signup-password">
                                <input id="password" :type="inputType" class="form-control" autocomplete="current-password" v-model="registerForm.password">
                                <input class="password_toggle" type="checkbox" @click="inputChange">
                                <div class="password_label"><i :class="iconType"></i></div>
                            </div>
                            <div v-if="registerErrors">
                                <ul v-if="registerErrors.password">
                                    <li class="text-danger" v-for="msg in registerErrors.password" :key="msg"><p>{{ msg }}</p></li>
                                </ul>
                            </div>

                            <label for="password-confirm" class="required">パスワード（確認）</label>
                            <input id="password-confirm" :type="inputType" class="form-control" v-model="registerForm.password_confirmation" autocomplete="new-password">

                            <button type="submit" class="login-signup-button">
                                登録
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
                registerForm: {
                    user_name: '',
                    email: '',
                    introduction: '',
                    password: '',
                    password_confirmation: ''
                },
                isChecked: false,
                wordLimit: 100,
            }
        },

        computed: {
            ...mapState({
                apiStatus: state => state.auth.apiStatus,
                registerErrors: state => state.auth.registerErrorMessages
            }),
            inputType: function () {
                return this.isChecked ? "text" : "password";
            },
            iconType: function () {
                return this.isChecked ? "fas fa-eye-slash fa-lg" : "fas fa-eye fa-lg";
            },
            wordCount(){
                return this.wordLimit - this.registerForm.introduction.length
            },
        },

        methods: {
            async register () {
                await this.$store.dispatch('auth/register', this.registerForm)

                if (this.apiStatus) {
                    this.$router.push('/')
                }
            },
            clearError () {
                this.$store.commit('auth/setRegisterErrorMessages', null)
            },
            inputChange: function() {
                this.isChecked = !this.isChecked;
            },
            // 文字数
            changeTrue: function() {
                this.isActive = true
            },
            changeFalse: function() {
                this.isActive = false
            },
        },
        created () {
            this.clearError()
        }
    }
</script>