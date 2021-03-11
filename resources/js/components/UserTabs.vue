<template>
    <div class="user-tabs">
        <ul class="nav nav-tabs nav-justified mt-3">
            <li class="nav-item" @click="tab = 1">
                <span class="nav-link text-muted" :class="{'active': tab === 1 }">
                    釣りスポット <span class="badge badge-secondary">{{ countUserSpots }}</span>
                </span>
            </li>
            <li class="nav-item" @click="tab = 2">
                <span class="nav-link text-muted" :class="{'active': tab === 2 }">
                    いいね <span class="badge badge-secondary">{{ countUserFavoriteSpots }}</span>
                </span>
            </li>
        </ul>

        <div v-show="tab === 1">
            <div v-for="spot in spots" :key="spot.id" class="mx-auto d-block col-lg-4 col-md-6 col-11">
                <div class="spot_card">
                    <a v-bind:href="`/spots/${spot.id}`">
                        <div class="spot_card_img">
                            <img :src="`/storage/${spot.spot_image}`" alt="釣り場投稿者の画像">
                        </div>
                    </a>

                    <div class="spot_card_content">
                        <div class="card_spot_name">
                            {{ spot.spot_name }}
                        </div>

                        <div class="card_detail">
                            <div class="favorite_button">
                                aaa
                            </div>

                            <div class="card_comment">
                                <i class="fa fa-comment mr-1"></i>{{ countSpotComments }}
                            </div>

                            <a v-bind:href="`/users/${spot.user.id}`">
                                <img :src="`/storage/${spot.user.user_image}`" alt="釣り場投稿者の画像">
                            </a>
                        </div>

                        <p v-if="spot.address && spot.address.length > 0">{{ spot.address }}</p>
                        <p>{{ spot.explanation }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-100 text-center">
            <button
                v-if="(spots.length - countSpots) >= 0"
                @click="seeMore" class="btn seeMore"
            >
                <i class="fa fa-chevron-down"></i>&nbsp;続きを見る
            </button>
        </div>
    </div>
</template>

<script>
    import SpotFavorite from './SpotFavorite'

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

            initialCountSpotComments: {
                type: Number,
                default: 0,
            },

            initialCountSpotFavorites: {
                type: Number,
                default: 0,
            },

            userId: {
                type: String,
                default: 0,
            },
        },

        data() {
            return {
                tab: 1,
                spots: {},
                countSpots: 2,
                user_id: this.userId,
                countUserSpots: this.initialCountUserSpots,
                countUserFavoriteSpots: this.initialCountUserFavoriteSpots,
                countSpotFavorites: this.initialCountSpotFavorites,
                countSpotComments: this.initialCountSpotComments,
            }
        },

        computed: {
            listSpots() {
                const list = this.spots
                return list.slice(0, this.countSpots)
            }
        },

        created: function() {
            this.getUserSpots();
        },

        methods: {
            // ユーザーの釣りスポット一覧
            getUserSpots() {
                const id = this.user_id
                const array = ["/users/",id,"/tabs"];
                const path = array.join('')

                axios
                    .get(path)
                    .then(response => {
                        this.spots = response.data;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },

            seeMore() {
                this.countSpots += 2
            },
        },
    }
</script>