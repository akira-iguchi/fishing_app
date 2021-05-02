<template>
    <div class="user-tabs">
        <ul class="nav nav-tabs nav-justified mt-3">
            <li class="nav-item" @click="tab = 'spotsTab'">
                <span class="nav-link text-muted" :class="{'active': tab === 'spotsTab' }">
                    釣りスポット <span class="badge badge-secondary">{{ user.count_spots }}</span>
                </span>
            </li>
            <li class="nav-item" @click="getUserFavoriteSpots">
                <span class="nav-link text-muted" :class="{'active': tab === 'favoriteSpotsTab' }">
                    お気に入り <span class="badge badge-secondary">{{ user.count_favorite_spots }}</span>
                </span>
            </li>
            <li class="nav-item" @click="getUserFollowings">
                <span class="nav-link text-muted" :class="{'active': tab === 'followingsTab' }">
                    フォロー <span class="badge badge-secondary">{{ user.count_followings }}</span>
                </span>
            </li>
            <li class="nav-item" @click="getUserFollowers">
                <span class="nav-link text-muted" :class="{'active': tab === 'followersTab' }">
                    フォロワー <span class="badge badge-secondary">{{ user.count_followers }}</span>
                </span>
            </li>
        </ul>

        <div v-show="tab === 'spotsTab'">
            <div class="row">
                <SpotCard
                    v-for="spot in userSpotsList"
                    :key="spot.id"
                    :spot="spot"
                />
                <div class="tabItem_none" v-if="userSpots.length <= 0">投稿していません</div>
            </div>

            <div class="text-center">
                <button
                    class="btn see_more"
                    @click="seeMoreSpots"
                    v-if="(userSpots.length - userSpotsCount) >= 0"
                >
                    <i class="fa fa-chevron-down"></i>&nbsp;続きを見る
                </button>
            </div>
        </div>

        <div v-show="tab === 'favoriteSpotsTab'">
            <div class="row">
                <SpotCard
                    v-for="spot in userFavoriteSpotsList"
                    :key="spot.id"
                    :spot="spot"
                />
                <div class="tabItem_none" v-if="(userFavoriteSpots.length) <= 0">お気に入りしていません</div>
            </div>

            <div class="text-center">
                <button
                    class="btn see_more"
                    @click="seeMoreFavoriteSpots"
                    v-if="(userFavoriteSpots.length - userFavoriteSpotsCount) >= 0"
                >
                    <i class="fa fa-chevron-down"></i>&nbsp;続きを見る
                </button>
            </div>
        </div>

        <div v-show="tab === 'followingsTab'">
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
                    v-if="(userFollowings.length - userFollowingsCount) >= 0"
                >
                    <i class="fa fa-chevron-down"></i>&nbsp;続きを見る
                </button>
            </div>
        </div>

        <div v-show="tab === 'followersTab'">
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
                            />
                        </div>
                    </div>
                </div>

                <div class="tabItem_none" v-if="(userFollowers.length) <= 0">フォローされていません</div>
            </div>

            <div class="text-center">
                <button
                    class="btn see_more"
                    @click="seeMoreFollowers"
                    v-if="(userFollowers.length - userFollowersCount) >= 0"
                >
                    <i class="fa fa-chevron-down"></i>&nbsp;続きを見る
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import { OK } from '../../util'
    import SpotCard from '../spots/cards/SpotCard.vue'
    import FollowButton from './FollowButton.vue'
    import moment from 'moment';

    export default {
        components: {
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
                tab: 'spotsTab',
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
            userSpotsList () {
                const list = this.userSpots
                return list.slice(0, this.userSpotsCount)
            },
            userFavoriteSpotsList () {
                const list = this.userFavoriteSpots
                return list.slice(0, this.userFavoriteSpotsCount)
            },
            userFollowingsList () {
                const list = this.userFollowings
                return list.slice(0, this.userFollowingsCount)
            },
            userFollowersList () {
                const list = this.userFollowers
                return list.slice(0, this.userFollowersCount)
            },
        },
        mounted: function() {
            this.getUserSpots();
        },
        watch: {
            user (newUser) {
                this.getUserSpots(newUser)
                this.userSpotsCount = 1
                this.userFavortieSpotsCount = 1
                this.userFollowingsCount = 1
                this.userFollowersCount = 1
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

                this.userSpots = response.data;
            },
            // ユーザーのお気に入り釣りスポット一覧
            async getUserFavoriteSpots () {
                this.tab = 'favoriteSpotsTab';
                const response = await axios.get(`/api/users/${this.user.id}/favoriteSpots`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.userFavoriteSpots = response.data;
            },

            // ユーザーフォロー一覧
            async getUserFollowings () {
                this.tab = 'followingsTab';
                const response = await axios.get(`/api/users/${this.user.id}/followings`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.userFollowings = response.data;
            },

            // ユーザーフォロワー一覧
            async getUserFollowers () {
                this.tab = 'followersTab';
                const response = await axios.get(`/api/users/${this.user.id}/followers`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.userFollowers = response.data;
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
        },
    }
</script>