<template>
    <div class="container">
        <div class="row">
            <div class="mx-auto d-block col-lg-10 col-md-11 spot_form">
                <div v-show="loading" class="panel">
                    <Loader />
                </div>

                <SpotForm
                    v-if="spotDataLoaded"
                    :tagNames="allTagNames"
                    :fishingTypeNames="allFishingTypeNames"
                    :intialSpotValue="spot"
                    :errors="errors"
                    @spotData="createSpot"
                />

            </div>
        </div>
    </div>
</template>

<script>
    import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../../util'
    import SpotForm from "../../components/spots/SpotForm.vue"
    import Loader from '../../components/commons/Loader.vue'

    export default {
        components: {
            SpotForm,
            Loader,
        },
        data () {
            return {
                loading: false,
                allTagNames: [],
                allFishingTypeNames: [],
                spot: {},
                errors: null,
                spotDataLoaded: false,
            }
        },
        watch: {
            $route: {
                async handler () {
                    await this.fetchCreateSpot()
                },
                immediate: true
            }
        },
        methods: {
            async fetchCreateSpot () {
                this.loading = true
                const response = await axios.get('/api/spots/create')

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.loading = false

                this.allTagNames = response.data[0]
                this.allFishingTypeNames = response.data[1]

                this.spotDataLoaded = true
            },
            async createSpot (data) {
                window.scrollTo(0, 0)
                this.loading = true

                const formData = new FormData()
                formData.append('latitude', data[0])
                formData.append('longitude', data[1])
                formData.append('spot_name', data[2])
                formData.append('address', data[3])
                formData.append('tags', data[4])
                formData.append('fishing_types', data[5])
                formData.append('explanation', data[6])
                formData.append('spot_image_first', data[7])
                formData.append('spot_image_second', data[8])
                formData.append('spot_image_third', data[9])
                const response = await axios.post('/api/spots', formData)

                this.loading = false

                if (response.status === UNPROCESSABLE_ENTITY) {
                    this.errors = response.data.errors
                    return false
                }

                if (response.status !== CREATED) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.$emit('input', false)

                this.$store.commit('message/setContent', {
                    content: '釣りスポットを投稿しました',
                    timeout: 5000
                })

                this.$router.push(`/spots/${response.data.id}`)
            }
        },
    }
</script>