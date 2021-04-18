<template>
    <div class="container">
        <div class="row user-container">
            <span class="user_title">
                <span v-if="user.id === AuthUser.id">
                    <i class="fa fa-user" aria-hidden="true"></i>マイプロフィール
                </span>
                <span v-else><i class="fa fa-user" aria-hidden="true"></i>ユーザープロフィール</span>
            </span>

            <div class="profile">
                <div class="profile_top">
                    <div class="profile_image">
                        <img :src="`${user.user_image}`" alt="ユーザーの画像">
                    </div>

                    <div class="profile_content">
                        <div class="profile_name">{{ user.user_name }}</div>

                        <!-- フォロー／アンフォローボタン -->
                        <div class="follow_btn">
                            <FollowButton
                                :user="user"
                            />
                        </div>
                    </div>

                    <RouterLink
                        class="btn btn-primary profile_edit_link"
                        v-if="AuthUser && AuthUser.id !== 1"
                        :to="`/users/${user.id}/edit`"
                    >
                        プロフィールの編集
                    </RouterLink>
                </div>

                <div v-html="user.introduction.replace(/\n/g,'<br/>')"
                    v-if="user.introduction && user.introduction.length > 0"
                    class="profile_under"
                >
                    <p>{{ user.introduction }}</p>
                </div>
            </div>

            <!-- ユーザーのタブ一覧（フォロー、お気に入りボタン含む）
            <user-tabs
                :initial-count-user-spots='@json(user.count_spots)'
                :initial-count-user-favorite-spots='@json(user.count_favorite_spots)'
                :initial-count-user-followings='@json(user.count_followings)'
                :initial-count-user-followers='@json(user.count_followers)'
                user-id="{{ user.id }}"
                auth-user="{{ auth()->user() }}"
            >
            </user-tabs> -->
        </div>
    </div>
</template>

<script>
    import { OK } from '../../util'
    import FollowButton from '../../components/users/FollowButton.vue'

    export default {
        components: {
            FollowButton
        },
        props: {
            id: {
                type: String,
                required: true
            }
        },
        data () {
            return {
                user: {},
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
            async fetchUser () {
                const response = await axios.get(`/api/users/${this.id}`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.user = response.data
            },
        },
        watch: {
            $route: {
                async handler () {
                    await this.fetchUser()
                },
                immediate: true
            }
        }
    }
</script>