<template>
    <div class="user-tabs">
        <ul class="nav nav-tabs nav-justified mt-3">
            <li class="nav-item" @click="tab = 'spotsTab'">
                <span class="nav-link text-muted" :class="{'active': tab === 'spotsTab' }">
                    釣りスポット <span class="badge badge-secondary">{{ countUserSpots }}</span>
                </span>
            </li>
            <li class="nav-item" @click="getUserFavoriteSpots">
                <span class="nav-link text-muted" :class="{'active': tab === 'favoriteSpotsTab' }">
                    お気に入り <span class="badge badge-secondary">{{ countUserFavoriteSpots }}</span>
                </span>
            </li>
            <li class="nav-item" @click="getUserFollowings">
                <span class="nav-link text-muted" :class="{'active': tab === 'followingsTab' }">
                    フォロー <span class="badge badge-secondary">{{ countUserFollowings }}</span>
                </span>
            </li>
            <li class="nav-item" @click="getUserFollowers">
                <span class="nav-link text-muted" :class="{'active': tab === 'followersTab' }">
                    フォロワー <span class="badge badge-secondary">{{ countUserFollowers }}</span>
                </span>
            </li>
        </ul>

        <div class="row" v-show="tab === 'spotsTab'">
            <div v-for="spot in spots" :key="spot.id" class="mx-auto d-block col-lg-4 col-md-6 col-11">
                <a v-bind:href="`/spots/${spot.id}`">
                    <div class="spot_card">
                            <div class="spot_card_img">
                                <img :src="`${spot.spot_images[0].spot_image}`" alt="釣りスポットの画像">
                            </div>

                        <div class="spot_card_content">
                            <div class="card_spot_name">
                                {{ spot.spot_name }}
                            </div>

                            <div class="card_detail">
                                <div class="card_item">
                                    <i class="fas fa-heart heart_text"></i>
                                    {{ spot.spot_favorites.length }}
                                </div>

                                <div class="card_item ml-2 mr-3">
                                    <i class="fa fa-comment mr-1"></i>{{ countSpotComments }}
                                </div>

                                <div class="card_item">
                                    <i class="fas fa-clock mr-1"></i>&thinsp;{{ spot.created_at | moment }}
                                </div>

                                <a v-bind:href="`/users/${spot.user_id}`">
                                    <img :src="`${spot.user.user_image}`" alt="釣りスポット投稿者の画像">
                                </a>
                            </div>

                            <p v-if="spot.address && spot.address.length > 0">{{ spot.address }}</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="w-100 text-center">
                <button
                    v-if="(spots.length - countSpots) >= 0"
                    @click="seeMoreSpots" class="btn seeMore"
                >
                    <i class="fa fa-chevron-down"></i>&nbsp;もっと見る
                </button>
            </div>

            <div class="tabItem_none" v-if="(spots.length) <= 0">投稿していません</div>
        </div>

        <div class="row" v-show="tab === 'favoriteSpotsTab'">
            <div v-for="spot in favoriteSpots" :key="spot.id" class="mx-auto d-block col-lg-4 col-md-6 col-11">
                <a v-bind:href="`/spots/${spot.id}`">
                    <div class="spot_card">
                            <div class="spot_card_img">
                                <img :src="`${spot.spot_images[0].spot_image}`" alt="釣りスポットの画像">
                            </div>

                        <div class="spot_card_content">
                            <div class="card_spot_name">
                                {{ spot.spot_name }}
                            </div>

                            <div class="card_detail">
                                <div class="card_item">
                                    <!-- お気に入りボタン -->
                                    <i class="fas fa-heart heart_text"></i>
                                    {{ spot.spot_favorites.length }}
                                </div>

                                <div class="card_item ml-2 mr-3">
                                    <i class="fa fa-comment mr-1"></i>{{ countSpotComments }}
                                </div>

                                <div class="card_item">
                                    <i class="fas fa-clock mr-1"></i>&thinsp;{{ spot.created_at | moment }}
                                </div>

                                <a v-bind:href="`/users/${spot.user_id}`">
                                    <img :src="`${spot.user.user_image}`" alt="釣りスポット投稿者の画像">
                                </a>
                            </div>

                            <p v-if="spot.address && spot.address.length > 0">{{ spot.address }}</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="w-100 text-center">
                <button
                    v-if="(favoriteSpots.length - countFavoriteSpots) >= 0"
                    @click="seeMoreFavoriteSpots" class="btn seeMore"
                >
                    <i class="fa fa-chevron-down"></i>&nbsp;もっと見る
                </button>
            </div>

            <div class="tabItem_none" v-if="(favoriteSpots.length) <= 0">お気に入りしていません</div>
        </div>

        <div class="row" v-show="tab === 'followingsTab'">
            <div v-for="user in followings" :key="user.id" class="mx-auto d-block col-xl-3 col-lg-4 col-md-6 mt-4 mb-5 text-center">
                <div class="profile_image">
                    <img :src="`${user.user_image}`" alt="ユーザーの画像">
                </div>

                <div class="profile_content">
                    <a v-bind:href="`/users/${user.id}`">
                        <p class="followings_user_name"><strong>{{ user.user_name }}</strong></p>
                    </a>

                    <div>
                        <span>{{ user.followings.length }} フォロー</span>
                        <span class="mr-4">{{ user.followers.length }} フォロワー</span>
                    </div>
                </div>
            </div>

            <div class="w-100 text-center">
                <button
                    v-if="(followings.length - countFollowings) >= 0"
                    @click="seeMoreFollowings" class="btn seeMore"
                >
                    <i class="fa fa-chevron-down"></i>&nbsp;もっと見る
                </button>
            </div>

            <div class="tabItem_none" v-if="(followings.length) <= 0">フォローしていません</div>
        </div>

        <div class="row" v-show="tab === 'followersTab'">
            <div v-for="user in followers" :key="user.id" class="mx-auto d-block col-lg-4 col-md-6 mt-4 mb-5 text-center">
                <div class="profile_image">
                    <img :src="`${user.user_image}`" alt="ユーザーの画像">
                </div>

                <div class="profile_content">
                    <a v-bind:href="`/users/${user.id}`">
                        <p class="followings_user_name"><strong>{{ user.user_name }}</strong></p>
                    </a>
                </div>
            </div>

            <div class="w-100 text-center">
                <button
                    v-if="(followers.length - countFollowers) >= 0"
                    @click="seeMoreFollowers" class="btn seeMore"
                >
                    <i class="fa fa-chevron-down"></i>&nbsp;もっと見る
                </button>
            </div>

            <div class="tabItem_none" v-if="(followers.length) <= 0">フォローされていません</div>
        </div>
    </div>
</template>

<script>
    import SpotFavorite from './SpotFavorite';
    import moment from 'moment';

    export default {
        components: {
            'spot-favorite': SpotFavorite,
        },

        props: {
            initialCountUserSpots: {
                type: Number,
                default: 0,
            },

            initialCountUserFavoriteSpots: {
                type: Number,
                default: 0,
            },

            initialCountUserFollowings: {
                type: Number,
                default: 0,
            },

            initialCountUserFollowers: {
                type: Number,
                default: 0,
            },

            initialCountSpotComments: {
                type: Number,
                default: 0,
            },

            userId: {
                type: String,
                default: "",
            },

            authUser: {
                default: {},
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
                countSpots: 1,
                countFavoriteSpots: 1,
                countFollowings: 1,
                countFollowers: 1,
                userSpots: [],
                userFavoriteSpots: [],
                userFollowings: [],
                userFollowers: [],
                user_id: this.userId,
                auth_user: this.authUser,
                countUserSpots: this.initialCountUserSpots,
                countUserFavoriteSpots: this.initialCountUserFavoriteSpots,
                countUserFollowings: this.initialCountUserFollowings,
                countUserFollowers: this.initialCountUserFollowers,
                countSpotComments: this.initialCountSpotComments,
            }
        },

        created: function() {
            this.getUserSpots();
        },

        computed: {
            spots() {
                const spotsList = this.userSpots
                return Array.prototype.slice.call(spotsList, 0, this.countSpots).reverse()
            },

            favoriteSpots() {
                const favoriteSpotsList = this.userFavoriteSpots
                return Array.prototype.slice.call(favoriteSpotsList, 0, this.countFavoriteSpots).reverse()
            },

            followings() {
                const followingsList = this.userFollowings
                return Array.prototype.slice.call(followingsList, 0, this.countFollowings).reverse()
            },

            followers() {
                const followersList = this.userFollowers
                return Array.prototype.slice.call(followersList, 0, this.countFollowers).reverse()
            },
        },

        methods: {
            // ユーザーの釣りスポット一覧
            getUserSpots() {
                const id = this.user_id
                const array = ["/users/",id,"/spots"];
                const path = array.join('')

                axios
                    .get(path)
                    .then(response => {
                        this.userSpots = response.data;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },

            // ユーザーのお気に入り釣りスポット一覧
            getUserFavoriteSpots() {
                this.tab = 'favoriteSpotsTab';
                const id = this.user_id
                const array = ["/users/",id,"/favoriteSpots"];
                const path = array.join('')

                axios
                    .get(path)
                    .then(response => {
                        this.userFavoriteSpots = response.data;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },

            // ユーザーフォロー一覧
            getUserFollowings() {
                this.tab = 'followingsTab';
                const id = this.user_id
                const array = ["/users/",id,"/followings"];
                const path = array.join('')

                axios
                    .get(path)
                    .then(response => {
                        this.userFollowings = response.data;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },

            // ユーザーフォロワー一覧
            getUserFollowers() {
                this.tab = 'followersTab';
                const id = this.user_id
                const array = ["/users/",id,"/followers"];
                const path = array.join('')

                axios
                    .get(path)
                    .then(response => {
                        this.userFollowers = response.data;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },

            seeMoreSpots() {
                this.countSpots += 2
            },

            seeMoreFavoriteSpots() {
                this.countFavoriteSpots += 2
            },

            seeMoreFollowings() {
                this.countFollowings += 2
            },

            seeMoreFollowers() {
                this.countFollowers += 2
            },
        },
    }
</script>