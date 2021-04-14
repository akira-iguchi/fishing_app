<template>
    <div class="container">
        <div class="row spot_body">
            <div class="mx-auto d-block col-lg-8 spot_container">
                <h1 class="spot_name">{{ spot.spot_name }}</h1>

                <!-- @foreach($spot->tags as $tag)
                    @if($loop->first)
                    <div class="card-body pt-3 pb-4 pl-3">
                        <div class="card-text line-height">
                    @endif
                        <a href="{{ route('tags', ['name' => $tag->tag_name]) }}" class="spot_tag">
                            {{ $tag->hashtag }}
                        </a>
                    @if($loop->last)
                        </div>
                    </div>
                    @endif
                @endforeach -->

                <hooper :autoPlay="true" :playSpeed="4000" :infiniteScroll="true" class="hooper-container">
                    <slide class="hooper-slide">
                        <div id="show_map"></div>
                    </slide>
                    <slide class="hooper-slide" v-for="image in spot.spot_images" :key="image.id">
                        <img :src="`${ image.spot_image }`" alt="釣りスポットの画像">
                    </slide>
                    <hooper-navigation slot="hooper-addons"></hooper-navigation>
                    <hooper-pagination slot="hooper-addons"></hooper-pagination>
                </hooper>
                <!-- <div class="hooper-container mb-2">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div id="show_map"></div>
                            <GmapMap id="show_map" :center="spotPosition" :zoom="15" map-type-id="terrain">
                                <GmapMarker :animation="2" :position="spotPosition" />
                            </GmapMap>
                        </div>
                        <div v-for="image in spot.spot_images" :key="image.id" class="swiper-slide">
                            <img :src="`${ image.spot_image }`" alt="釣りスポットの画像">
                        </div>
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div> -->

                <div class="d-flex">
                    <!-- @include('favorites.favorite_button') -->
                    <i class="fas fa-clock ml-2 mt-1"></i>&nbsp;{{ spot.created_at | moment }}
                </div>

                <table>
                    <tbody>
                        <tr v-if="spot.address && spot.address.length > 0">
                            <th>所在地</th>
                            <td><span>{{ spot.address }}</span></td>
                        </tr>
                        <!-- @if(isset($spot->fishingTypes[0]))
                            <tr>
                                <th><a href="/fishing_types">おすすめの釣り方</a></th>
                                <td>
                                    @foreach($spot->fishingTypes as $fishing_type)
                                        <ul class="spot-fishing_type">
                                            <li>{{ $fishing_type->fishing_type_name }}</li>
                                        </ul>
                                    @endforeach
                                </td>
                            </tr>
                        @endif -->
                        <tr>
                            <th>説明</th>
                            <td><span>{{ spot.explanation }}</span></td>
                        </tr>
                    </tbody>
                </table>

                <!-- @include('spots.private') -->

                <!-- <comments
                    :initial-count-comments='@json($spot->count_spot_comments)'
                    spot-id="{{ $spot->id }}"
                    user-id="{{ Auth::id() }}"
                >
                </comments> -->
            </div>

            <div class="mx-auto d-block col-lg-4">
                <!-- <div class="spot_creater">
                    <span>作成者</span><br>
                    <RouterLink :to="`/users/${spot.user_id}`">
                        <img :src="`${spot.user.user_image}`" alt="釣りスポット投稿者の画像">
                        <p class="spot_creater_name">{{ spot.user.user_name }}</p>
                    </RouterLink>

                    <span><strong>{{ spot.user.followings.length }}</strong>フォロー  <strong>{{ spot.user.followers.length }}</strong>フォロワー</span> -->
                <!-- </div> -->

                <div class="other-spot">
                    <hr>
                    <h3 class="text-center mt-1">他の釣りスポット</h3>

                    <div class="mini_card"  v-for="spot in otherSpots" :key="spot.id">
                        <RouterLink :to="`/spots/${spot.id}`">
                            <div class="mini_card_img">
                                <img :src="`${spot.spot_images[0].spot_image}`" alt="釣りスポットの画像">
                            </div>
                        </RouterLink>

                        <div class="mini_card_content">
                            <div class="card_spot_name">
                                {{ spot.spot_name }}
                            </div>

                            <div class="mini_card_detail">

                                <div class="card_item">
                                    <!-- @include('favorites.favorite_button') -->
                                </div>

                                <div class="card_item">
                                    <i class="fa fa-comment mr-1"></i>{{ spot.spot_comments.length }}
                                </div>

                                <RouterLink :to="`/users/${spot.user_id}`">
                                    <img :src="`${spot.user.user_image}`" alt="釣りスポット投稿者の画像">
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
    import { OK } from '../../util'
    import moment from 'moment';
    import {Hooper, Slide, Pagination as HooperPagination, Navigation as HooperNavigation} from 'hooper';
    import 'hooper/dist/hooper.css';

    export default {
        components: {
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
                otherSpots: {},
                spotPosition: {},
                // swiperOption: {
                //     autoplay: {
                //         delay: 4000,
                //         reverseDirection: true,
                //     },
                //     navigation: {
                //         nextEl: '.swiper-button-next',
                //         prevEl: '.swiper-button-prev',
                //         clickable: true,
                //     },
                //     loop: true,
                //     pagination: {
                //         el: '.swiper-pagination',
                //         type: 'bullets',
                //         clickable: true,
                //     },
                // },
            }
        },
        filters: {
            moment: function (date) {
                return moment(date).format('YYYY/MM/DD');
            }
        },
        methods: {
            async fetchSpot () {
                const response = await axios.get(`/api/spots/${this.id}`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.spot = response.data[0]
                this.otherSpots = response.data[1]
                this.spotPosition = {lat: response.data[0].latitude, lng: response.data[0].longitude}
            }
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