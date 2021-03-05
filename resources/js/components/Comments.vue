<template>
    <div>
        <h2 class="mt-3">コメント一覧</h2>
        <i class="fa fa-comment mr-1"></i>{{ countComments }}

        <div v-for="comment in comments" :key="comment.id">
            <div class="comment">
                <div class="mt-2">
                    <div class="comment_created_at">{{ comment.created_at }}</div>

                </div>

                <div class="comment_content">
                    {{ comment.comment }}
                </div>
            </div>

        </div>

        <div>
            <input type="hidden" class="form-control" id="user_id" name="user_id">

            <div class="form-group">
                <textarea rows="4" id="textAreaComment" class="form-control mt-4" v-model="comment" placeholder="コメントしよう！"></textarea>
                残り<span id="textLestComment">150</span>文字
                <p id="textAttentionComment" style="display:none; color:red;">入力文字数が多すぎます。</p>
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
    export default {
        props: {
            initialCountComments: {
                type: Number,
                default: 0,
            },
        },

        props: ['userId', 'spotId'],

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
                countComments: this.initialCountComments,
            }
        },

        created: function() {
            this.getComment();
        },

        methods: {
            getComment() {
                axios
                    .get("/api/comments/")
                    .then(response => {
                        this.comments = response.data;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },

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

            createImage(comment_image) {
                let reader = new FileReader();
                reader.readAsDataURL(comment_image);
                reader.onload = e => {
                    this.confirmedImage = e.target.result;
                };
            },

            async uploadComment() {
                let formData = new FormData();
                formData.append('user_id', this.user_id);
                formData.append('comment', this.comment);
                formData.append('comment_image', this.comment_image);

                const id = this.spot_id
                const array = ["/api/spots/",id,"/comments"];
                const path = array.join('')

                await axios
                    .post(path, formData, {
                        headers: {
                            'X-HTTP-Method-Override': 'POST'
                        }
                    })
                    .then(response => {
                        this.getComment();
                        this.countComments = response.data.countComments
                        this.confirmedImage = "";
                        this.comment = "";
                        this.comment_image = "";

                        //ファイルを選択のクリア
                        this.view = false;
                        this.$nextTick(function() {
                            this.view = true;
                        });
                    })
                    .catch(err => {
                        this.message = err.response.data.errors;
                    });
            }
        },
    }
</script>

<style>
    .commentImg {
        width: 10em;
        border-radius: 10px;
    }
</style>