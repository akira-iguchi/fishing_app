<template>
    <div class="container">
        <SearchForm
            :fishingTypeNames="allFishingTypeNames"
            :tagNames="tagNames"
            :parentName="parentName"
            @getsearchData="getSearchSpots"
        />

        <!-- 入力したワード、釣り方名一覧 -->
        <h2 class="search-result">
            <span
                v-if="searchWord && searchWord.length > 0
                || searchFishingTypes && searchFishingTypes.length > 0"
            >
                <span v-if="searchWord && searchWord.length > 0">{{ searchWord }}</span>

                <span v-if="searchFishingTypes && searchFishingTypes.length > 0">
                    <span
                        v-for="(fishinTypeName, index) in fishingTypeNames"
                        :key="index"
                    >
                        {{ fishinTypeName }}
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
            v-if="searchWord && searchWord.length > 0
            || searchFishingTypes && searchFishingTypes.length > 0"
        >
            {{ spots.length }} 件
        </p>
        <p class="search_count" v-else>
            すべての投稿
        </p>

        <div v-show="loading" class="mt-5 mb-5">
            <Loader />
        </div>

        <div class="row" v-if="spots && spots.length > 0">
            <SpotCard
                v-for="spot in spots"
                :key="spot.id"
                :spot="spot"
                :isRanking="false"
            />
        </div>

        <!-- ページネーション -->
        <Pagination
            :current-page="currentPage"
            :last-page="lastPage"
        />

    </div>
</template>

<script>
    import { OK } from '../../util'
    import Loader from '../../components/commons/Loader.vue'
    import SpotCard from '../../components/spots/cards/SpotCard.vue'
    import SearchForm from '../../components/spots/searches/SearchForm.vue'
    import Pagination from '../../components/Pagination.vue'

    export default {
        components: {
            Loader,
            SpotCard,
            SearchForm,
            Pagination,
        },
        props: {
            page: {
                type: Number,
                required: false,
                default: 1
            },
        },
        data () {
            return {
                loading: true,
                parentName: 'search',
                searchWord: "",
                allFishingTypeNames: [],
                tagNames: [],
                searchFishingTypes: [],
                spots: [],
                fishingTypeNames: [],
                currentPage: 0,
                lastPage: 0,
                params: this.$route.params
            }
        },
        watch: {
            $route: {
                async handler () {
                    await this.fetchSearchSpots()
                },
                deep: true,
                immediate: true
            },
        },
        methods: {
            async fetchSearchSpots () {
                this.loading = true
                const response = await axios.get(`/api/spots/search?page=${ this.$route.query.page }`, {
                    params: {
                        searchWord: this.$route.params.searchWord,
                        fishingTypes: this.$route.params.fishingTypes
                    }
                })

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.loading = false

                this.allFishingTypeNames = response.data[0][0]
                this.tagNames = response.data[0][1]
                this.searchWord = response.data[0][2]
                this.searchFishingTypes = response.data[0][3]
                this.spots = response.data[1].data
                this.fishingTypeNames = response.data[2]

                this.currentPage = response.data[1].current_page
                this.lastPage = response.data[1].last_page
            },
            // ページネーション
            getSearchSpots (data) {
                this.$route.query.page = "1"
                this.$route.params.searchWord = data[0]
                this.$route.params.fishingTypes = data[1]

                this.fetchSearchSpots()
            }
        },
    }
</script>