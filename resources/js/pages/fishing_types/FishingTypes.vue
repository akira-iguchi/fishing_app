<template>
    <div class="container fishing_type_body">
        <h1 class="fishing_type_title">釣り方一覧</h1>
        <div class="row">
            <div
                class="mx-auto d-block col-lg-5 fishing_type_card"
                v-for="fishing_type in fishingTypes"
                :key="fishing_type.id"
            >
                <span class="fishing_type_card_title">
                    {{ fishing_type.fishing_type_name }}
                </span>

                {{ fishing_type.content }}

                <p
                    @click="openImageByFullScreen"
                    :class="{ 'full_screen_wrapper' : fullScreen }"
                >
                    <!-- <img
                        @click="openImageByFullScreen"
                        :src="`${fishing_type.fishing_type_image}`"
                        alt="釣り場方の画像"
                    > -->
                    <img
                        :class="{ 'full_screen_image' : fullScreen }"
                        src="/images/akira.jpeg"
                        alt="釣り場方の画像"
                    >
                </p>
                <small>画像クリックで拡大！</small>
                <hr>
                <h5>おすすめの釣り場</h5>
                    <ul class="fishing_type-spot"
                        v-for="spot in fishing_type.spots"
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

    export default {
        data () {
            return {
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

                this.fishingTypes = response.data
            },
            openImageByFullScreen() {
                this.fullScreen = !this.fullScreen
            },
        },
    }
</script>