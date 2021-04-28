<template>
    <div class="container">
        <SearchForm
            :fishingTypeNames="allFishingTypeNames"
            :tagNames="tagNames"
        />

        <h2 class="search-result">
            <span v-if="searchWord && searchWord.length > 0 || searchFishingTypes && searchFishingTypes.lrngth > 0">
                <span v-if="searchWord && searchWord.length > 0">{{ searchWord }}</span>

                <span v-if="searchFishingTypes && searchFishingTypes.length > 0">
                    <span
                        v-for="(fishinType, index) in searchFishingTypes"
                        :key="index"
                    >
                        {{ $fishing_type_name }}
                    </span>
                </span>
                の検索結果
            </span>
            <span v-else>
                検索結果
            </span>
        </h2>

        <p
            class="search_count"
            v-if="searchWord && searchWord.length > 0 || searchFishingTypes && searchFishingTypes.lrngth > 0"
        >
            {{ spots.length }} 件
        </p>
        <p class="search_count" v-else>
            すべての投稿
        </p>

        <br>

        <div class="row" v-if="spots && spots.length > 0">
            <SpotCard
                v-for="spot in spots"
                :key="spot.id"
                :item="spot"
            />
        </div>

        <!-- ページネーション -->
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
        data () {
            return {
                allFishingTypeNames: [],
                searchWord: "",
                searchFishingTypes: [],
                tagNames: [],
                spots: [],
                fishingTypeNames: [],
            }
        },
        watch: {
            $route: {
                async handler () {
                    await this.fetchSearchSpots()
                },
                immediate: true
            },
        },
        methods: {
            async fetchSearchSpots () {
                const response = await axios.get('/api/spots/search', {
                    searchWord: this.searchWord,
                    fishingTypes: this.fishingTypes
                })

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.allFishingTypeNames = response.data[0][0]
                this.searchWord = response.data[0][1]
                this.searchFishingTypes = response.data[0][2]
                this.tagNames = response.data[0][3]
                this.spots = response.data[1]
                this.fishingTypeNames = response.data[2]
                console.log(this.searchFishingTypes)
            },
        },

    }
</script>