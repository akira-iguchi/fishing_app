<template>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-8 user-edit_body mx-auto d-block">
                <h1>プロフィール編集</h1>
                <div class="user_edit_form">

                    <div v-show="loading" class="mt-3">
                        <Loader />
                    </div>

                    <form  @submit.prevent="editUserProfile">

                        <label for="user_name" class="required">ユーザー名</label>
                        <div class="user-edit_text">
                            <input id="user_name" type="text" placeholder="お名前" autocomplete="user_name" v-model="userName" autofocus required>
                            <span><i class="fa fa-user fa-lg fa-fw" aria-hidden="true"></i></span>
                        </div>
                        <div v-if="errors">
                            <ul class="user_errors" v-if="errors.user_name">
                                <li class="text-danger" v-for="msg in errors.user_name" :key="msg">{{ msg }}</li>
                            </ul>
                        </div>

                        <label for="email" class="required mt-3">メールアドレス</label>
                        <div class="user-edit_text">
                            <input id="email" type="email" placeholder="メールアドレス" autocomplete="email" v-model="email" required>
                            <span><i class="fa fa-envelope fa-lg fa-fw" aria-hidden="true"></i></span>
                        </div>
                        <div v-if="errors">
                            <ul class="user_errors" v-if="errors.email">
                                <li class="text-danger" v-for="msg in errors.email" :key="msg">{{ msg }}</li>
                            </ul>
                        </div>

                        <div class="mt-3">
                            <label for="image">プロフィール画像</label>
                            <input id="image" type="file" @change="onFileChange">
                            <p v-if="preview">
                                <img class="file_preview" :src="preview" alt="">
                            </p>
                            <span class="error_msg">
                                <p>{{ imageErrorMessage }}</p>
                            </span>
                        </div>
                        <div v-if="errors">
                            <ul class="user_errors" v-if="errors.user_image">
                                <li class="text-danger" v-for="msg in errors.user_image" :key="msg">{{ msg }}</li>
                            </ul>
                        </div>

                        <label for="textAreaIntroduction">自己紹介</label>
                        <div v-if="0 > wordCount" v-on="changeTrue()"></div>
                        <div v-else-if="0 <= wordCount" v-on="changeFalse()"></div>
                        <div class="user-edit_text">
                            <textarea
                                rows="5"
                                id="textAreaIntroduction"
                                v-model="introduction"
                                placeholder="よろしくおねがいします！"
                            ></textarea>
                        </div>
                        <p>残り<span v-bind:class="{ 'text-danger':isActive }">{{ wordCount }}</span>文字</p>
                        <div v-if="errors">
                            <ul class="user_errors" v-if="errors.introduction">
                                <li class="text-danger" v-for="msg in errors.introduction" :key="msg">{{ msg }}</li>
                            </ul>
                        </div>

                        <div>
                            <button class="user_edit-button">更新&emsp;<i class="fas fa-angle-right fa-lg"></i></button>
                        </div>
                    </form>
                </div>
                <p class="back_link" onclick="history.back()">戻る</p>
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
        props: {
            id: {
                type: String,
                required: true
            }
        },
        data(){
            return {
                loading: false,
                user: {},
                wordLimit: 100,
                imageErrorMessage: "",
                preview: null,
                userName: "",
                email: "",
                userImage: "",
                introduction: "",
                errors: null,
            }
        },
        computed: {
            wordCount(){
                return this.wordLimit - this.introduction.length
            },
        },
        watch: {
            $route: {
                async handler () {
                    await this.fetchEditUserProfile()
                },
                immediate: true
            }
        },
        methods: {
            async fetchEditUserProfile () {
                this.loading = true
                const response = await axios.get(`/api/users/${ this.id }/edit`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.loading = false

                this.user = response.data

                this.userName = this.user.user_name
                this.email = this.user.email
                this.introduction = this.user.introduction

                if (this.user.id === 4) {
                    this.$router.push('/', () => {})

                    this.$store.commit('message/setContent', {
                        content: 'ゲストユーザーは編集できません',
                        timeout: 4000
                    })
                }
            },
            // 文字数
            changeTrue: function() {
                this.isActive = true
            },
            changeFalse: function() {
                this.isActive = false
            },
            // 画像ファイルをプレビュー、エラーメッセージ処理（３つ）
            onFileChange (event) {
                if (event.target.files.length === 0) {
                    this.imageErrorMessage = ""
                    this.preview = null
                    return false
                }
                if (! event.target.files[0].type.match('image.*')) {
                    this.imageErrorMessage = "画像ファイルを選択して下さい"
                    this.preview = null
                    return false
                }
                const reader = new FileReader()
                reader.onload = e => {
                    this.preview = e.target.result
                }
                reader.readAsDataURL(event.target.files[0])
                this.userImage = event.target.files[0]
                this.imageErrorMessage = ""
            },
            async editUserProfile () {
                this.loading = true
                const formData = new FormData()
                formData.append('user_name', this.userName)
                formData.append('email', this.email)
                formData.append('user_image', this.userImage)
                formData.append('introduction', this.introduction)
                const response = await axios.post(`/api/users/${ this.id }`, formData, {
                    // PUTに変換
                    headers: {
                        'X-HTTP-Method-Override': 'PUT'
                    }
                })

                this.loading = false

                if (response.status === UNPROCESSABLE_ENTITY) {
                    this.errors = response.data.errors
                    return false
                }

                this.imageErrorMessage = ""
                this.preview = null
                this.$emit('input', false)

                if (response.status !== CREATED) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.$store.commit('message/setContent', {
                    content: 'ユーザー情報を更新しました',
                    timeout: 6000
                })

                this.$router.push(`/users/${response.data.id}`)
            }
        },
    }
</script>

<style>
    .commentImg {
        margin-top: 15px;
        width: 10em;
        border-radius: 10px;
    }
</style>