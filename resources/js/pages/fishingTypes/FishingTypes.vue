<template>
    <div class="container fishing_type_body">
        <h1 class="fishing_type_title">釣り方一覧</h1>

        <div v-show="loading" class="mt-5">
            <Loader />
        </div>

        <div class="row">
            <div
                class="mx-auto d-block col-lg-5 fishing_type_card"
                v-for="fishingType in fishingTypes"
                :key="fishingType.id"
            >
                <span class="fishing_type_card_title">
                    {{ fishingType.fishing_type_name }}
                </span>

                {{ fishingType.content }}

                <p>
                    <img
                        :id="fishingType.id"
                        @click="openImageByFullScreen(fishingType.id)"
                        :src="fishingType.fishing_type_image"
                        alt="釣り場方の画像"
                    >
                </p>
                <small>画像クリックで拡大！</small>
                <hr>
                <h5>おすすめの釣り場</h5>
                    <ul class="fishing_type-spot"
                        v-for="spot in fishingType.spots"
                        :key="spot.id"
                    >
                        <li>
                            <RouterLink :to="`/spots/${spot.id}`">
                                {{ spot.spot_name }}
                            </RouterLink>
                        </li>
                    </ul>
            </div>
        </div>
    </div>
</template>

<script>
    import { OK } from '../../util'
    import Loader from '../../components/commons/Loader.vue'

    export default {
        components: {
            Loader,
        },
        data () {
            return {
                loading: true,
                fishingTypes: [],
                fullScreen: false,
            }
        },
        watch: {
            $route: {
                async handler () {
                    await this.fetchFishingTypes()
                },
                immediate: true
            }
        },
        methods: {
            async fetchFishingTypes () {
                const response = await axios.get('/api/fishing_types')

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.loading = false

                this.fishingTypes = response.data
            },
            // クリックで全画面表示
            openImageByFullScreen (fishingTypeId) {
                const fishingTypeImage = document.getElementById(fishingTypeId)
                if (fishingTypeImage.requestFullscreen) {
                    fishingTypeImage.requestFullscreen();
                } else if (fishingTypeImage.mozRequestFullScreen) { /* Firefox */
                    fishingTypeImage.mozRequestFullScreen();
                } else if (fishingTypeImage.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
                    fishingTypeImage.webkitRequestFullscreen();
                } else if (fishingTypeImage.msRequestFullscreen) { /* IE/Edge */
                    fishingTypeImage.msRequestFullscreen();
                }
            }
        },
    }
</script>