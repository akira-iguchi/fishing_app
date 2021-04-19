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

        <div class="row" v-show="tab === 'spotsTab'">
            <SpotCard
                v-for="spot in userSpots"
                :key="spot.id"
                :item="spot"
            />
            <div class="tabItem_none" v-if="(userSpots.length) <= 0">投稿していません</div>
        </div>

        <div class="row" v-show="tab === 'favoriteSpotsTab'">
            <SpotCard
                v-for="spot in userFavoriteSpots"
                :key="spot.id"
                :item="spot"
            />
            <div class="tabItem_none" v-if="(userFavoriteSpots.length) <= 0">お気に入りしていません</div>
        </div>

        <div class="row" v-show="tab === 'followingsTab'">
            <div
                class="mx-auto d-block col-xl-3 col-lg-4 col-md-6 mt-4 mb-5 text-center"
                v-for="user in followings"
                :key="user.id"
            >
                <div class="profile_image">
                    <img :src="`${user.user_image}`" alt="ユーザーの画像">
                </div>

                <div class="profile_content">
                    <a v-bind:href="`/users/${user.id}`">
                        <p class="followings_user_name"><strong>{{ user.user_name }}</strong></p>
                    </a>
                    <RouterLink :to="`/users/${user_id}`">
                        <p class="followings_user_name"><strong>{{ user.user_name }}</strong></p>
                    </RouterLink>

                    <div>
                        <FollowButton
                            :user="user"
                        />
                    </div>
                </div>
            </div>

            <div class="tabItem_none" v-if="(followings.length) <= 0">フォローしていません</div>
        </div>

        <div class="row" v-show="tab === 'followersTab'">
            <div
                class="mx-auto d-block col-lg-4 col-md-6 mt-4 mb-5 text-center"
                v-for="user in followers"
                :key="user.id"
            >
                <div class="profile_image">
                    <img :src="`${user.user_image}`" alt="ユーザーの画像">
                </div>

                <div class="profile_content">
                    <RouterLink :to="`/users/${user_id}`">
                        <p class="followings_user_name"><strong>{{ user.user_name }}</strong></p>
                    </RouterLink>

                    <div>
                        <FollowButton
                            :user="user"
                        />
                    </div>
                </div>
            </div>
            <div class="tabItem_none" v-if="(followers.length) <= 0">フォローされていません</div>
        </div>
    </div>
</template>

<script>
    import { OK } from '../../util'
    import SpotCard from '../spots/cards/SpotCard.vue'
    import moment from 'moment';

    export default {
        components: {
            SpotCard
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
            }
        },
        mounted: function() {
            this.getUserSpots();
        },
        methods: {
            // ユーザーの釣りスポット一覧
            getUserSpots() {
                // this.tab = 'spotsTab';
                // const response = await axios.get(`/api/users/${this.user.id}/spots`)

                // if (response.status !== OK) {
                //     this.$store.commit('error/setCode', response.status)
                //     return false
                // }

                // console.log(this.user)
                // this.userSpots = response.data;

                // const id = this.user.id
                // const array = ["/users/",id,"/spots"];
                // const path = array.join('')

                // axios
                //     .get(path)
                //     .then(response => {
                //         this.userSpots = response.data;
                //     })
                //     .catch(err => {
                //         console.log(err);
                //     });
            },

            // ユーザーのお気に入り釣りスポット一覧
            async getUserFavoriteSpots() {
                this.tab = 'favoriteSpotsTab';
                const response = await axios.get(`/api/users/${this.user.id}/favoriteSpots`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.userFavoriteSpots = response.data;
            },

            // ユーザーフォロー一覧
            async getUserFollowings() {
                this.tab = 'followings';
                const response = await axios.get(`/api/users/${this.user.id}/followings`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.userFollowings = response.data;
            },

            // ユーザーフォロワー一覧
            async getUserFollowers() {
                this.tab = 'followersTab';
                const response = await axios.get(`/api/users/${this.user.id}/followers`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.userFollowers = response.data;
            },
        },
    }
</script>