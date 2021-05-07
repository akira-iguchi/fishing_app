<template>
    <div class="container">
        <SearchForm
            :fishingTypeNames="fishingTypeNames"
            :tagNames="tagNames"
            :parentName="parentName"
        />

        <h2 class="search-result"><span>{{ tag.hashtag }}</span>の検索結果</h2>

        <p class="search_count">{{ tag.count_spots }}件</p>

        <div v-show="loading" class="mt-5 mb-5">
            <Loader />
        </div>

        <div class="row">
            <SpotCard
                v-for="spot in tagSpots"
                :key="spot.id"
                :spot="spot"
                :isRanking="false"
            />
        </div>
    </div>
</template>

<script>
    import { OK } from '../../util'
    import Loader from '../../components/commons/Loader.vue'
    import SpotCard from '../../components/spots/cards/SpotCard.vue'
    import SearchForm from '../../components/spots/searches/SearchForm.vue'

    export default {
        components: {
            Loader,
            SpotCard,
            SearchForm,
        },
        props: {
            name: {
                type: String,
                required: true
            }
        },
        data () {
            return {
                loading: true,
                parentName: 'tag',
                tag: {},
                tagSpots: [],
                fishingTypeNames: [],
                tagNames: [],
            }
        },
        watch: {
            $route: {
                async handler () {
                    await this.fetchTagSpots()
                },
                immediate: true
            }
        },
        methods: {
            async fetchTagSpots () {
                this.loading = true
                const response = await axios.get(`/api/tags/${this.name}`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.loading = false

                this.fishingTypeNames = response.data[0][0]
                this.tagNames = response.data[0][1]
                this.tag = response.data[1]
                this.tagSpots = response.data[2]
            },
        },
    }
</script>