<template>
    <div>
        <h2 class="mt-3">コメント一覧</h2>
        <i class="fa fa-comment mr-1"></i>{{ spotData.count_spot_comments }}

        <div
            class="comment_index"
            v-if="spotData.spot_comments && spotData.spot_comments.length > 0"
        >
            <div v-for="(comment, index) in spotCommentList" :key="comment.id">
                <div class="comment">
                    <div class="comment_top">
                        <div class="comment_created_at">{{ comment.created_at | moment }}</div>
                        <a v-bind:href="`/users/${comment.user_id}`">
                            <img :src="`${comment.user.user_image}`" alt="釣り場投稿者の画像" />
                            <span class="comment_creater_name">{{ comment.user.user_name }}</span>
                        </a>
                    </div>

                    <div class="comment_under">
                        {{ comment.comment }}
                    </div>

                    <div
                        class="comment_img"
                        v-if="comment.comment_image && comment.comment_image.length > 0"
                    >
                        <img :src="`${comment.comment_image}`" alt="釣り場コメントの画像" />
                    </div>

                    <div class="comment_delete">
                        <button
                            v-if="comment.user_id === AuthUser.id"
                            @click.prevent="deleteComment(comment.id, index)"
                            type="button"
                            onclick="return confirm('本当に削除しますか？')"
                        >
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div v-show="loading" class="mt-3">
            <Loader />
        </div>

        <form @submit.prevent="createComment">
            <div class="form-group">
                <div v-if="150 < wordCount" v-on="changeTrue()"></div>
                <div v-else-if="150 >= wordCount" v-on="changeFalse()"></div>
                <textarea
                    rows="4"
                    class="form-control mt-4"
                    placeholder="コメントしよう！"
                    v-model="commentContent"
                ></textarea>
                <p class="text_limit">
                    <span
                        v-bind:class="{ 'text-danger':isActive }"
                    >{{ wordCount }}
                    </span>/150
                </p>
            </div>
            <div v-if="commentErrors">
                <ul class="comment_errors" v-if="commentErrors.comment">
                    <li
                        class="text-danger"
                        v-for="msg in commentErrors.comment"
                        :key="msg"
                    >
                        {{ msg }}
                    </li>
                </ul>
            </div>

            <div class="form-group">
                <label for="comment_image">画像</label><br>

                <input id="comment_image" type="file" v-if="showInputImage" @change="onFileChange">
                <p v-if="preview">
                    <img class="commentImg" :src="preview" alt="">
                </p>
                <span class="error_msg">
                    <p>{{ commentImageMessage }}</p>
                </span>
            </div>
            <div v-if="commentErrors">
                <ul class="comment_errors" v-if="commentErrors.comment_image">
                    <li
                        class="text-danger"
                        v-for="msg in commentErrors.comment_image"
                        :key="msg"
                    >
                        {{ msg }}
                    </li>
                </ul>
            </div>

            <button class="spot-create-edit-button"><i class="fas fa-pencil-alt"></i>&thinsp;コメント</button>
        </form>
    </div>
</template>

<script>
    import { CREATED, UNPROCESSABLE_ENTITY } from '../../../util'
    import moment from 'moment';
    import Loader from '../../commons/Loader.vue'

    export default {
        components: {
            Loader,
        },
        props: {
            spotData: {
                type: Object,
                required: true
            },
        },
        data () {
            return {
                spot: this.spotData,
                spotCommentList: this.spotData.spot_comments.reverse(),
                commentContent: "",
                commentImage: "",
                isActive: false,
                commentImageMessage: "",
                preview: null,
                commentErrors: null,
                showInputImage: true,
                loading: false,
            }
        },
        filters: {
            moment: function (date) {
                return moment(date).format('YYYY/MM/DD');
            }
        },
        computed: {
            wordCount(){
                return this.commentContent.length
            },
            AuthUser () {
                return this.$store.getters['auth/AuthUser']
            },
        },
        watch: {
            spotData (newSpot) {
                this.spot = newSpot
                this.commentImageMessage = ""
                this.preview = null
                this.commentContent = ""
                this.commentErrors = null
                this.showInputImage = false
                this.$nextTick(function () {
                    this.showInputImage = true;
                })
                this.spotCommentList = newSpot.spot_comments.reverse()
                return this.spotCommentList
            }
        },
        methods: {
            async createComment () {
                this.loading = true
                const formData = new FormData()
                formData.append('spot_id', this.spotData.id)
                formData.append('comment', this.commentContent)
                formData.append('comment_image', this.commentImage)
                const response = await axios.post(`/api/spots/${ this.spot.id }/comments`, formData)

                this.loading = false

                if (response.status === UNPROCESSABLE_ENTITY) {
                    this.commentErrors = response.data.errors
                    return false
                }

                if (response.status !== CREATED) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.spot.count_spot_comments += 1
                this.commentImageMessage = ""
                this.preview = null
                this.commentContent = ""
                this.commentErrors = null
                this.showInputImage = false
                this.$nextTick(function () {
                    this.showInputImage = true;
                })

                this.spot.spot_comments.unshift(response.data)

                this.$store.commit('message/setContent', {
                    content: 'コメントを投稿しました',
                    timeout: 4000
                })
            },
            async deleteComment(comment, index) {
                const response = await axios.delete(`/api/spots/${ this.spot.id }/comments/${ comment }`)

                this.spot.count_spot_comments -= 1

                this.spot.spot_comments.splice(index, 1)

                this.$store.commit('message/setContent', {
                    content: 'コメントを削除しました',
                    timeout: 4000
                })
            },
            // 文字数
            changeTrue:function () {
                this.isActive = true
            },
            changeFalse:function () {
                this.isActive = false
            },
            onFileChange (event) {
                if (event.target.files.length === 0) {
                    this.commentImageMessage = ""
                    this.preview = null
                    return false
                }
                if (! event.target.files[0].type.match('image.*')) {
                    this.commentImageMessage = "画像ファイルを選択して下さい"
                    this.preview = null
                    return false
                }
                const reader = new FileReader()
                reader.onload = e => {
                    this.preview = e.target.result
                }
                reader.readAsDataURL(event.target.files[0])
                this.commentImage = event.target.files[0]
                this.commentImageMessage = ""
            },
        },
    }
</script>