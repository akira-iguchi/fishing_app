<template>
    <div class="container">
        <div class="row spot_body">
            <div class="mx-auto d-block col-lg-8 spot_container">
                <h1 class="spot_name">{{ spot.spot_name }}</h1>

                    <div class="card-body pt-3 pb-4 pl-3">
                        <div class="card-text line-height">
                            <span v-for="tag in spot.tags" :key="tag.id">
                                <RouterLink class="spot_tag" :to="`/tags/${tag.tag_name}`">
                                    {{ tag.hashtag }}
                                </RouterLink>
                            </span>
                        </div>
                    </div>

                <hooper
                    class="hooper-container"
                    :autoPlay="true"
                    :wheelControl="false"
                    :playSpeed="4000"
                    :infiniteScroll="true"
                >
                    <slide class="hooper-slide">
                        <div id="show_map"></div>
                        <!-- <GmapMap id="show_map" :center="spotPosition" :zoom="15" map-type-id="terrain">
                            <GmapMarker :animation="2" :position="spotPosition" />
                        </GmapMap> -->
                    </slide>
                    <slide
                        class="hooper-slide"
                        v-for="image in spot.spot_images"
                        :key="image.id"
                    >
                        <img :src="`${ image.spot_image }`" alt="釣りスポットの画像">
                    </slide>
                    <hooper-navigation slot="hooper-addons"></hooper-navigation>
                    <hooper-pagination slot="hooper-addons"></hooper-pagination>
                </hooper>

                <div class="d-flex">
                    <div class="mr-2">
                        <FavoriteButton
                            :spot="spot"
                        />
                        <i class="fas fa-clock ml-2 mt-1"></i>&nbsp;{{ spot.created_at | moment }}
                    </div>
                </div>

                <table>
                    <tbody>
                        <tr v-if="spot.address && spot.address.length > 0">
                            <th>所在地</th>
                            <td><span>{{ spot.address }}</span></td>
                        </tr>
                        <tr v-if="spot.fishing_types && spot.fishing_types.length > 0">
                            <th>
                                <RouterLink to="/fishing_types">
                                    おすすめの釣り方
                                </RouterLink>
                            </th>
                            <td>
                                <ul class="spot-fishing_type">
                                    <li v-for="fishing_type in spot.fishing_types"
                                        :key="fishing_type.id"
                                    >
                                        {{ fishing_type.fishing_type_name }}
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <th>説明</th>
                            <td><span>{{ spot.explanation }}</span></td>
                        </tr>
                    </tbody>
                </table>

                <!-- @include('spots.private') -->

                <h2 class="mt-3">コメント一覧</h2>
                <i class="fa fa-comment mr-1"></i>{{ spot.count_spot_comments }}

                <div
                    class="comment_index"
                    v-if="spot.spot_comments && spot.spot_comments.length > 0"
                    >
                    <div v-for="(comment, index) in spot.spot_comments" :key="comment.id">
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
                                    v-if="comment.user_id == AuthUser.id"
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

                <form @submit.prevent="addComment">
                    <div class="form-group">
                        <div v-if="0 > wordCount" v-on="changeTrue()"></div>
                        <div v-else-if="0 <= wordCount" v-on="changeFalse()"></div>
                        <textarea
                            rows="4"
                            class="form-control mt-4"
                            placeholder="コメントしよう！"
                            v-model="commentContent"
                        ></textarea>
                        残り<span v-bind:class="{ 'text-danger':isActive }">{{ wordCount }}</span>文字
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

                        <input id="comment_image" type="file" @change="onFileChange">
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

            <div class="mx-auto d-block col-lg-4">
                <div class="spot_creater">
                    <span>作成者</span><br>
                    <RouterLink :to="`/users/${spot.user_id}`">
                        <img :src="`${user.user_image}`" alt="釣りスポット投稿者の画像">
                        <p class="spot_creater_name">{{ user.user_name }}</p>
                    </RouterLink>

                    <span>
                        <strong>{{ user.count_followings }}</strong>フォロー
                        <strong>{{ user.count_followers }}</strong>フォロワー
                    </span>
                </div>

                <div class="other-spot">
                    <hr>
                    <h3 class="text-center mt-1">他の釣りスポット</h3>

                    <div class="mini_card"  v-for="otherSpot in otherSpots" :key="otherSpot.id">
                        <RouterLink :to="`/spots/${otherSpot.id}`">
                            <div class="mini_card_img">
                                <img :src="`${otherSpot.first_spot_image}`" alt="釣りスポットの画像">
                            </div>
                        </RouterLink>

                        <div class="mini_card_content">
                            <div class="card_spot_name">
                                {{ otherSpot.spot_name }}
                            </div>

                            <div class="mini_card_detail">

                                <div class="card_item mr-1">
                                    <FavoriteButton
                                        :spot="otherSpot"
                                    />
                                </div>

                                <div class="card_item">
                                    <i class="fa fa-comment ml-1 mr-1"></i>{{ otherSpot.spot_comments.length }}
                                </div>

                                <RouterLink :to="`/users/${otherSpot.user_id}`">
                                    <img :src="`${otherSpot.user.user_image}`" alt="釣りスポット投稿者の画像">
                                </RouterLink>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../../util'
    import FavoriteButton from '../../components/spots/FavoriteButton.vue'
    import moment from 'moment';
    import {Hooper, Slide, Pagination as HooperPagination, Navigation as HooperNavigation} from 'hooper';
    import 'hooper/dist/hooper.css';

    export default {
        components: {
            FavoriteButton,
            Hooper,
            Slide,
            HooperPagination,
            HooperNavigation,
        },
        props: {
            id: {
                type: String,
                required: true
            }
        },
        data () {
            return {
                spot: {},
                user: {},
                otherSpots: {},
                spotPosition: {},
                commentContent: "",
                commentImage: "",
                isActive: false,
                wordLimit: 150,
                commentImageMessage: "",
                preview: null,
                commentErrors: null,
            }
        },
        filters: {
            moment: function (date) {
                return moment(date).format('YYYY/MM/DD');
            }
        },
        computed: {
            wordCount(){
                return this.wordLimit - this.commentContent.length
            },
            AuthUser () {
                return this.$store.getters['auth/AuthUser']
            },
        },
        methods: {
            async fetchSpot () {
                const response = await axios.get(`/api/spots/${this.id}`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.spot = response.data[0]
                this.user = this.spot.user
                this.spotPosition = {lat: this.spot.latitude, lng: this.spot.longitude}
                this.otherSpots = response.data[1]
            },
            async addComment () {
                const formData = new FormData()
                formData.append('comment', this.commentContent)
                formData.append('comment_image', this.commentImage)
                const response = await axios.post(`/api/spots/${this.id}/comments`, formData)

                if (response.status === UNPROCESSABLE_ENTITY) {
                    this.commentErrors = response.data.errors
                    return false
                }

                this.spot.count_spot_comments += 1
                this.commentImageMessage = ""
                this.preview = null
                this.commentContent = ''
                this.commentErrors = null

                this.spot.spot_comments = [
                    response.data,
                    ...this.spot.spot_comments
                ]

                if (response.status !== CREATED) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.$store.commit('message/setContent', {
                    content: 'コメントを投稿しました',
                    timeout: 6000
                })
            },
            // コメント削除
            async deleteComment(comment, index) {
                const response = await axios.delete(`/api/spots/${this.id}/comments/${comment}`)

                this.spot.count_spot_comments -= 1

                this.spot.spot_comments.splice(index, 1)

                this.$store.commit('message/setContent', {
                    content: 'コメントを削除しました',
                    timeout: 5000
                })
            },
            // 文字数
            changeTrue:function(){
                this.isActive = true
            },
            changeFalse:function(){
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
        watch: {
            $route: {
                async handler () {
                    await this.fetchSpot()
                },
                immediate: true
            }
        }
    }
</script>

<style lang="scss">
    .hooper {
        height: auto;

        &:focus-within {
            outline: none;
        }
    }

    .hooper-prev,
    .hooper-next {
        &:focus-within {
            outline: none;
        }
    }

    .hooper-prev{
        transition: .1s;
        transform: translateX(-2.2rem);
    }

    .hooper-next {
        transition: .1s;
        transform: translateX(2.2rem);
    }

    .hooper-indicator {
        background-color: #aaa;
    }
</style>