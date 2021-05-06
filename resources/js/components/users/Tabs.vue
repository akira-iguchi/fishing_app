<template>
    <div class="user-tabs">
        <ul class="nav nav-tabs nav-justified mt-3">
            <li class="nav-item" @click="getUserSpots">
                <span class="nav-link text-muted" :class="{'active': tab === 'spotsTab' }">
                    釣りスポット <span class="badge badge-secondary">{{ user.spots.length }}</span>
                </span>
            </li>
            <li class="nav-item" @click="getUserFavoriteSpots">
                <span class="nav-link text-muted" :class="{'active': tab === 'favoriteSpotsTab' }">
                    お気に入り <span class="badge badge-secondary">{{ user.favorite_spots.length }}</span>
                </span>
            </li>
            <li class="nav-item" @click="getUserFollowings">
                <span class="nav-link text-muted" :class="{'active': tab === 'followingsTab' }">
                    フォロー <span class="badge badge-secondary">{{ user.followings.length }}</span>
                </span>
            </li>
            <li class="nav-item" @click="getUserFollowers">
                <span class="nav-link text-muted" :class="{'active': tab === 'followersTab' }">
                    フォロワー <span class="badge badge-secondary">{{ user.followers.length }}</span>
                </span>
            </li>
        </ul>

        <div v-show="tab === 'spotsTab'">

            <div v-show="loading" class="mt-3">
                <Loader />
            </div>

            <div class="row">
                <SpotCard
                    v-for="spot in userSpotsList"
                    :key="spot.id"
                    :spot="spot"
                    :isRanking="false"
                    @favorite="plusFavoriteCount"
                    @unfavorite="minusFavoriteCount"
                />
                <div class="tabItem_none" v-if="userSpots.length <= 0">投稿していません</div>
            </div>

            <div class="text-center">
                <button
                    class="btn see_more"
                    @click="seeMoreSpots"
                    v-if="(userSpots.length - userSpotsCount) > 0"
                >
                    <i class="fa fa-chevron-down"></i>&nbsp;続きを見る
                </button>
            </div>
        </div>

        <div v-show="tab === 'favoriteSpotsTab'">

            <div v-show="loading" class="mt-3">
                <Loader />
            </div>

            <div class="row">
                <SpotCard
                    v-for="spot in userFavoriteSpotsList"
                    :key="spot.id"
                    :spot="spot"
                    :isRanking="false"
                    @favorite="plusFavoriteCount"
                    @unfavorite="minusFavoriteCount"
                />
                <div
                    class="tabItem_none"
                    v-if="(userFavoriteSpots.length) <= 0"
                >お気に入りしていません</div>
            </div>

            <div class="text-center">
                <button
                    class="btn see_more"
                    @click="seeMoreFavoriteSpots"
                    v-if="(userFavoriteSpots.length - userFavoriteSpotsCount) > 0"
                >
                    <i class="fa fa-chevron-down"></i>&nbsp;続きを見る
                </button>
            </div>
        </div>

        <div v-show="tab === 'followingsTab'">

            <div v-show="loading" class="mt-3">
                <Loader />
            </div>

            <div class="row">
                <div
                    class="mx-auto d-block col-xl-3 col-lg-4 col-md-6 mt-4 mb-5 text-center"
                    v-for="user in userFollowingsList"
                    :key="user.id"
                >
                    <div class="profile_image">
                        <img :src="`${user.user_image}`" alt="ユーザーの画像">
                    </div>

                    <div class="profile_content">
                        <RouterLink :to="`/users/${user.id}`">
                            <p class="followings_user_name"><strong>{{ user.user_name }}</strong></p>
                        </RouterLink>

                        <div>
                            <FollowButton
                                :user="user"
                                :initialIsFollowedBy="followersId(user).includes(AuthUser.id)"
                                @follow="plusFollowCount"
                                @unfollow="minusFollowCount"
                            />
                        </div>
                    </div>
                </div>

                <div class="tabItem_none" v-if="(userFollowings.length) <= 0">フォローしていません</div>
            </div>

            <div class="text-center">
                <button
                    class="btn see_more"
                    @click="seeMoreFollowings"
                    v-if="(userFollowings.length - userFollowingsCount) > 0"
                >
                    <i class="fa fa-chevron-down"></i>&nbsp;続きを見る
                </button>
            </div>
        </div>

        <div v-show="tab === 'followersTab'">

            <div v-show="loading" class="mt-3">
                <Loader />
            </div>

            <div class="row">
                <div
                    class="mx-auto d-block col-xl-3 col-lg-4 col-md-6 mt-4 mb-5 text-center"
                    v-for="user in userFollowersList"
                    :key="user.id"
                >
                    <div class="profile_image">
                        <img :src="`${user.user_image}`" alt="ユーザーの画像">
                    </div>

                    <div class="profile_content">
                        <RouterLink :to="`/users/${user.id}`">
                            <p class="followings_user_name"><strong>{{ user.user_name }}</strong></p>
                        </RouterLink>

                        <div>
                            <FollowButton
                                :user="user"
                                :initialIsFollowedBy="followersId(user).includes(AuthUser.id)"
                                @follow="plusFollowCount"
                                @unfollow="minusFollowCount"
                            />
                        </div>
                    </div>
                </div>

                <div
                    class="tabItem_none"
                    v-if="(userFollowers.length) <= 0"
                >フォローされていません</div>
            </div>

            <div class="text-center">
                <button
                    class="btn see_more"
                    @click="seeMoreFollowers"
                    v-if="(userFollowers.length - userFollowersCount) > 0"
                >
                    <i class="fa fa-chevron-down"></i>&nbsp;続きを見る
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import { OK } from '../../util'
    import Loader from '../commons/Loader.vue'
    import SpotCard from '../spots/cards/SpotCard.vue'
    import FollowButton from './FollowButton.vue'
    import moment from 'moment';

    export default {
        components: {
            Loader,
            SpotCard,
            FollowButton,
        },
        props: {
            user: {
                type: Object,
                required: true,
            },
        },
        filters: {
            moment: function (date) {
                return moment(date).format('MM/DD');
            }
        },
        data() {
            return {
                loading: true,
                tab: 'spotsTab',
                followersId: [],
                userSpots: [],
                userFavoriteSpots: [],
                userFollowings: [],
                userFollowers: [],
                userSpotsCount: 1,
                userFavoriteSpotsCount: 1,
                userFollowingsCount: 1,
                userFollowersCount: 1,
            }
        },
        computed: {
            AuthUser () {
                return this.$store.getters['auth/AuthUser']
            },
            userSpotsList () {
                return this.userSpots.slice(0, this.userSpotsCount)
            },
            userFavoriteSpotsList () {
                return  this.userFavoriteSpots.slice(0, this.userFavoriteSpotsCount)
            },
            userFollowingsList () {
                return this.userFollowings.slice(0, this.userFollowingsCount)
            },
            userFollowersList () {
                return this.userFollowers.slice(0, this.userFollowersCount)
            },
        },
        mounted: function() {
            this.getUserSpots();
        },
        watch: {
            user (newUser) {
                this.loading = true
                this.getUserSpots(newUser)
                this.userSpotsCount = 1
                this.userFavortieSpotsCount = 1
                this.userFollowingsCount = 1
                this.userFollowersCount = 1
            },
            tab () {
                this.loading = true
            }
        },
        methods: {
            // ユーザーの釣りスポット一覧
            async getUserSpots () {
                this.tab = 'spotsTab';
                const response = await axios.get(`/api/users/${this.user.id}/spots`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }


                this.loading = false

                this.userSpots = response.data
            },
            // ユーザーのお気に入り釣りスポット一覧
            async getUserFavoriteSpots () {
                this.tab = 'favoriteSpotsTab';
                const response = await axios.get(`/api/users/${this.user.id}/favoriteSpots`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.loading = false

                this.userFavoriteSpots = response.data
            },

            // ユーザーフォロー一覧
            async getUserFollowings () {
                this.tab = 'followingsTab';
                this.userFollowings = []
                const response = await axios.get(`/api/users/${this.user.id}/followings`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.loading = false

                this.userFollowings = response.data
                this.followersId = function (user) {
                    return user.followers.map(function (user) {
                        return user.id
                    })
                }
            },

            // ユーザーフォロワー一覧
            async getUserFollowers () {
                this.tab = 'followersTab';
                this.userFollowers = []
                const response = await axios.get(`/api/users/${this.user.id}/followers`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.loading = false

                this.userFollowers = response.data
                this.followersId = function (user) {
                    return user.followers.map(function (user) {
                        return user.id
                    })
                }
            },
            seeMoreSpots () {
                this.userSpotsCount += 2
            },
            seeMoreFavoriteSpots () {
                this.userFavoriteSpotsCount += 2
            },
            seeMoreFollowings () {
                this.userFollowingsCount += 2
            },
            seeMoreFollowers () {
                this.userFollowersCount += 2
            },
            plusFavoriteCount () {
                this.user.favorite_spots.length += 1
            },
            minusFavoriteCount () {
                this.user.favorite_spots.length -= 1
            },
            // ログインユーザーならフォロー数追加、それ以外ならフォロワー数追加
            plusFollowCount () {
                if (this.user.id === this.AuthUser.id) {
                    this.user.followings.length += 1
                }
            },
            minusFollowCount () {
                if (this.user.id === this.AuthUser.id) {
                    this.user.followings.length -= 1
                }
            },
            changeFollowerCount () {
                this.user.followers.length = this.user.followers.length
                this.user.followings.length = this.user.followings.length
            },
        },
    }
</script>