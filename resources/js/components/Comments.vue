<template>
    <div>
        <div class="form-group">
            <textarea rows="4" id="textAreaComment" class="form-control mt-4" v-model="comment" placeholder="コメントしよう！"></textarea>
            残り<span id="textLestComment">150</span>文字
            <p id="textAttentionComment" style="display:none; color:red;">入力文字数が多すぎます。</p>
        </div>

        <div class="form-group">
            <label for="comment_image">画像</label>
            <input id="comment_image" type="file" v-model="comment_image">
        </div>

        <button v-show="text != ''" @click.prevent="send()" class="spot-create-edit-button"><i class="fas fa-pencil-alt"></i>&thinsp;コメント</button>
    </div>
</template>

<script>
    export default {
        props: {
            'spot_id',
            'user_id'
        },

        data() {
            return {
                comment: '',
                comment_image: ''
            }
        },

        methods: {
            send() {
                const comment = {
                    comment: this.comment
                    comment_image: this.comment_image
                }

                const id = this.spot_id
                const array = ["/items/",id,"/comments"];
                const path = array.join('')
                            this.comment = ''
                            this.comment_image = ''

                axios.post(path, text).then(res => {
                    this.$store.dispatch('comment/get_comments', id)
                }).catch(function(err) {
                    console.log(err)
                })
            }
        }
    }
</script>