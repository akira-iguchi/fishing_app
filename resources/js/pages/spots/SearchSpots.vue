<template>
    <div class="container">
        <SearchForm
            :fishingTypeNames="allFishingTypeNames"
            :tagNames="tagNames"
        />

        <h2 class="search-result">
            <span v-if="searchWord.length > 0 || searchFishingTypes.lrngth > 0">
                <span v-if="searchWord.length > 0">{{ searchWord }}</span>

                <span v-if="searchFishingTypes.lrngth > 0">
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
            @endif
        </h2>

        <p class="search_count" v-if="searchWord.length > 0 || searchFishingTypes.lrngth > 0">
            {{ spots.length }} 件
        </p>
        <p class="search_count" v-else>
            すべての投稿
        </p>

        <br>

        <div class="row" v-if="spots.length > 0">
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
    export default {
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
                const response = await axios.get('/api/spots/search')

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
            },
        },

    }
</script>