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

                <div v-show="loading" class="mt-2">
                    <Loader />
                </div>

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
                                :initialIsFollowedBy="isFollowedBy"
                                @follow="follow"
                                v-if="userDataLoaded"
                            />
                        </div>
                    </div>

                    <RouterLink
                        class="btn btn-primary profile_edit_link"
                        v-if="user.id === AuthUser.id && AuthUser.id !== 1"
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

            <!-- ユーザーのタブ一覧（フォロー、お気に入りボタン含む） -->
            <Tabs
                ref="child"
                :user="user"
                v-if="userDataLoaded"
            >
            </Tabs>
        </div>
    </div>
</template>

<script>
    import { OK } from '../../util'
    import Loader from '../../components/commons/Loader.vue'
    import FollowButton from '../../components/users/FollowButton.vue'
    import Tabs from '../../components/users/Tabs.vue'

    export default {
        components: {
            Loader,
            FollowButton,
            Tabs
        },
        props: {
            id: {
                type: String,
                required: true
            }
        },
        data () {
            return {
                loading: true,
                user: {},
                userDataLoaded: false,
            }
        },
        computed: {
            AuthUser () {
                return this.$store.getters['auth/AuthUser']
            },
            isFollowedBy () {
                const followersId = this.user.followers.map(function (user) {
                    return user.id
                })

                return followersId.includes(this.AuthUser.id)
            },
        },
        watch: {
            $route: {
                async handler () {
                    await this.fetchUser()
                },
                immediate: true
            }
        },
        methods: {
            async fetchUser () {
                this.loading = true
                const response = await axios.get(`/api/users/${this.id}`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.loading = false

                this.user = response.data

                this.userDataLoaded = true
            },
            // ユーザータブ(child)のchangeFollowerCountメソッドを引き出す
            follow () {
                this.$refs.child.changeFollowerCount()
            },
        },
    }
</script>