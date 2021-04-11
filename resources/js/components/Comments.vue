<template>
    <div>
        <h2 class="mt-3">コメント一覧</h2>
        <i class="fa fa-comment mr-1"></i>{{ countComments }}

        <div class="comment_index">
            <div v-for="comment in comments" :key="comment.id">
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

                    <div v-if="comment.comment_image && comment.comment_image.length > 0" class="comment_img">
                        <img :src="`${comment.comment_image}`" alt="釣り場コメントの画像" />
                    </div>

                    <div class="comment_delete">
                        <button
                            v-if="comment.user_id == user_id"
                            @click.prevent="deleteComment(comment.id)"
                            type="button"
                            onclick="return confirm('本当に削除しますか？')"
                        >
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <input type="hidden" class="form-control" id="user_id" name="user_id">

            <div class="form-group">
                <div v-if="0 > wordCount" v-on="changeTrue()"></div>
                <div v-else-if="0 <= wordCount" v-on="changeFalse()"></div>
                <textarea rows="4" class="form-control mt-4" v-model="comment" placeholder="コメントしよう！"></textarea>
                残り<span v-bind:class="{ 'text-danger':isActive }">{{ wordCount }}</span>文字
            </div>

            <div class="form-group">
                <label for="comment_image">画像</label>
                <input id="comment_image" @change="confirmImage" type="file" v-if="view">
            </div>

            <p v-if="confirmedImage">
                <img class="commentImg" :src="confirmedImage" />
            </p>

            <span class="error_msg">
                <p>{{ message }}</p>
            </span>

            <button @click="uploadComment" class="spot-create-edit-button"><i class="fas fa-pencil-alt"></i>&thinsp;コメント</button>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';

    export default {
        props: {
            initialCountComments: {
                type: Number,
                default: 0,
            },

            userId: {
                type: String,
                default: 0,
            },

            spotId: {
                type: String,
                default: 0,
            },
        },

        filters: {
            moment: function (date) {
                return moment(date).format('YYYY/MM/DD');
            }
        },

        data() {
            return {
                message: "",
                comment: "",
                comment_image: "",
                comments: {},
                view: true,
                confirmedImage: "",
                spot_id: this.spotId,
                user_id: this.userId,
                wordLimit: 150,
                isActive: false,
                countComments: this.initialCountComments,
            }
        },

        computed: {
            wordCount(){
                return this.wordLimit - this.comment.length
            }
        },

        created: function() {
            this.getComment();
        },

        methods: {
            // コメント一覧
            getComment() {
                const id = this.spot_id
                const array = ["/spots/",id,"/comments"];
                const path = array.join('')

                axios
                    .get(path)
                    .then(response => {
                        this.comments = response.data;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },

            // 文字数
            changeTrue:function(){
                this.isActive = true
            },
            changeFalse:function(){
                this.isActive = false
            },

            // 画像確認
            confirmImage(e) {
                this.message = "";
                this.comment_image = e.target.files[0];
                if (!this.comment_image.type.match("image.*")) {
                    this.message = "画像ファイルを選択して下さい";
                    this.confirmedImage = "";
                    return;
                }
                this.createImage(this.comment_image);
            },

            // 画像プレビュー
            createImage(comment_image) {
                let reader = new FileReader();
                reader.readAsDataURL(comment_image);
                reader.onload = e => {
                    this.confirmedImage = e.target.result;
                };
            },

            // コメント作成
            uploadComment() {
                let formData = new FormData();
                formData.append('user_id', this.user_id);
                formData.append('comment', this.comment);
                formData.append('comment_image', this.comment_image);

                const id = this.spot_id
                const array = ["/spots/",id,"/comments"];
                const path = array.join('')

                axios
                    .post(path, formData, {
                        headers: {
                            'X-HTTP-Method-Override': 'POST'
                        }
                    })
                    .then(response => {
                        this.getComment();
                        this.confirmedImage = "";
                        this.comment = "";
                        this.comment_image = "";
                        this.message = "コメントしました";
                        this.wordLimit = 150;
                        this.countComments += 1;

                        //ファイルを選択のクリア
                        this.view = false;
                        this.$nextTick(function() {
                            this.view = true;
                        });
                    })
                    .catch(err => {
                        this.message = err.response.data.errors.comment;
                    });
            },

            // コメント削除
            deleteComment(comment) {
                const id = this.spot_id
                const array = ["/spots/",id,"/comments/", comment];
                const path = array.join('')
                axios.delete(path).then(response => {
                    this.message = "コメントを削除しました";
                    this.getComment();
                    this.countComments -= 1;
                }).catch(function(err) {
                console.log(err)
                })
            },
        },
    }
</script>

<style>
    .commentImg {
        width: 10em;
        border-radius: 10px;
    }
</style>