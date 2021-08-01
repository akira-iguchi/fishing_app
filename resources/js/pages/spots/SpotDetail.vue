<template>
    <div class="container">
        <div class="row spot_body">
            <div class="mx-auto d-block col-lg-8 spot_container">

                <div v-show="loading" class="mt-3">
                    <Loader />
                </div>

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
                    :playSpeed="8000"
                    :infiniteScroll="true"
                >
                    <slide
                        class="hooper-slide"
                        v-for="image in spot.spot_images"
                        :key="image.id"
                    >
                        <img
                            :src="`${ image.spot_image }`"
                            :id="image.id"
                            @click="openImageByFullScreen(image.id)"
                            alt="釣りスポットの画像"
                        >
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

                <table v-if="spotDataLoaded">
                    <tbody>
                        <tr v-if="spot.address && spot.address.length > 0">
                            <th>所在地</th>
                            <td>{{ spot.address }}</td>
                        </tr>
                        <tr v-if="spot.fishing_types && spot.fishing_types.length > 0">
                            <th>
                                <RouterLink to="/fishing_types">
                                    おすすめの釣り方
                                </RouterLink>
                            </th>
                            <td>
                                <ul class="spot-fishing_type">
                                    <li v-for="fishingType in spot.fishing_types"
                                        :key="fishingType.id"
                                    >
                                        {{ fishingType.fishing_type_name }}
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <th>説明</th>
                            <td>
                                <span v-html="spot.explanation.replace(/\n/g,'<br/>')">
                                    {{ spot.explanation }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="spot_user_private" v-if="spot.user_id === AuthUser.id">

                    <button>
                        <RouterLink class="edit_link_button" :to="`/spots/${ spot.id }/edit`">
                            編集
                        </RouterLink>
                    </button>
                    <button class="delete_button" @click="deleteSpot">
                        削除
                    </button>
                </div>

                <div class="show_map" ref="googleMap">
                    <GoogleMapMarker
                        :position="mapLocation.center"
                        :google="google"
                        :map="map"
                        v-if="spotDataLoaded"
                    />
                </div>

                <SpotComments
                    v-if="spotDataLoaded"
                    :spotData="spot"
                />
            </div>

            <div class="mx-auto d-block col-lg-4">
                <div class="spot_creater">
                    <span>作成者</span><br>
                    <RouterLink :to="`/users/${spot.user_id}`">
                        <img :src="`${user.user_image}`" alt="釣りスポット投稿者の画像">
                        <p class="spot_creater_name">{{ user.user_name }}</p>
                    </RouterLink>

                    <span>
                        <strong v-if="user.followings">{{ user.followings.length }}</strong>フォロー
                        <strong v-if="user.followers">{{ user.followers.length }}</strong>フォロワー
                    </span>
                </div>

                <div class="other-spot">
                    <hr>
                    <h3 class="text-center mt-1">他の釣りスポット</h3>

                    <div v-show="loading" class="mt-5">
                        <Loader />
                    </div>

                    <SpotMiniCard
                        v-for="spot in otherSpots"
                        :key="spot.id"
                        :spot="spot"
                        @click="updateSpotComments"
                    />
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    import { OK } from '../../util'
    import Loader from '../../components/commons/Loader.vue'
    import FavoriteButton from '../../components/spots/FavoriteButton.vue'
    import SpotMiniCard from '../../components/spots/cards/SpotMiniCard.vue'
    import SpotComments from '../../components/spots/comments/SpotComments.vue'
    import moment from 'moment';
    import GoogleMapsApiLoader from 'google-maps-api-loader'
    import GoogleMapMarker from '../../components/spots/googleMaps/GoogleMapMarker.vue'
    import {Hooper, Slide, Pagination as HooperPagination, Navigation as HooperNavigation} from 'hooper';
    import 'hooper/dist/hooper.css';

    export default {
        components: {
            Loader,
            FavoriteButton,
            SpotMiniCard,
            SpotComments,
            GoogleMapMarker,
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
                loading: true,
                spot: {},
                user: {},
                otherSpots: {},
                google: null,
                map: null,
                mapLocation: {
                    center: {
                        lat: 35.6594666,
                        lng: 139.7005536,
                    },
                    zoom: 15
                },
                googleMapApiKey: "",
                loading: true,
                spotDataLoaded: false,
            }
        },
        filters: {
            moment: function (date) {
                return moment(date).format('YYYY/MM/DD');
            }
        },
        computed: {
            AuthUser () {
                return this.$store.getters['auth/AuthUser']
            },
        },
        watch: {
            $route: {
                async handler () {
                    await this.fetchSpot()
                },
                immediate: true
            }
        },
        methods: {
            async fetchSpot () {
                this.loading = true
                const response = await axios.get(`/api/spots/${ this.id }`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.loading = false

                this.spot = response.data[0]
                this.user = this.spot.user
                this.otherSpots = response.data[1]
                this.googleMapApiKey = response.data[2]

                this.google = await GoogleMapsApiLoader({
                    apiKey: this.googleMapApiKey
                })
                this.spotGoogleMap()
            },
            spotGoogleMap () {
                this.mapLocation.center.lat = this.spot.latitude
                this.mapLocation.center.lng = this.spot.longitude

                this.spotDataLoaded = false
                this.map = new this.google.maps.Map(this.$refs.googleMap, this.mapLocation)

                this.$nextTick(() => (this.spotDataLoaded = true))
            },
            // 釣りスポット削除
            async deleteSpot() {
                if (confirm('本当に削除しますか？')) {
                    const response = await axios.delete(`/api/spots/${ this.id }`)
                    this.$store.commit('message/setContent', {
                        content: '釣りスポットを削除しました',
                        timeout: 4000
                    })

                    this.$router.push('/')
                }
            },
            updateSpotComments () {
                this.spot = response.data[0]
            },
            // クリックで全画面表示
            openImageByFullScreen (imageId) {
                console.log("🚀 ~ file: SpotDetail.vue ~ line 257 ~ openImageByFullScreen ~ imageId", imageId)
                const SpotImage = document.getElementById(imageId)
                if (SpotImage.requestFullscreen) {
                    SpotImage.requestFullscreen();
                } else if (SpotImage.mozRequestFullScreen) { /* Firefox */
                    SpotImage.mozRequestFullScreen();
                } else if (SpotImage.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
                    SpotImage.webkitRequestFullscreen();
                } else if (SpotImage.msRequestFullscreen) { /* IE/Edge */
                    SpotImage.msRequestFullscreen();
                }
            }
        },
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