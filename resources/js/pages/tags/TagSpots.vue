<template>
    <div class="container">
        <SearchForm
            :fishingTypeNames="fishingTypeNames"
            :tagNames="tagNames"
        />

        <h2 class="search-result"><span>{{ tag.hashtag }}</span>の検索結果</h2>

        <p class="search_count">{{ tag.count_spots }}件</p>

        <div class="row">
            <SpotCard
                v-for="spot in tag.spots"
                :key="spot.id"
                :item="spot"
            />
        </div>
    </div>
</template>

<script>
    import { OK } from '../../util'
    import SpotCard from '../../components/spots/cards/SpotCard.vue'
    import SearchForm from '../../components/spots/searches/SearchForm.vue'

    export default {
        components: {
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
                tag: [],
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
                const response = await axios.get(`/api/tags/${this.name}`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.fishingTypeNames = response.data[0][0]
                this.tagNames = response.data[0][3]
                this.tag = response.data[1]
            },
        },
    }
</script>