<template>
    <div class="search_group">
        <div class="spotIndex_search_form">

            <form class="spot_search_top" @submit.prevent="searchSpots">
                <input
                    type="text"
                    class="spotIndex_search_text"
                    placeholder="キーワードを入力"
                    v-model="searchForm.searchWord"
                >
                <button type="submit" class="spotIndex_search_button">
                    <i class="fas fa-search"></i>
                </button>
            </form>

            <div>
                <span v-for="fishingType in fishingTypeNames" :key="fishingType.id">
                    <input
                        class="search_check"
                        type="checkbox"
                        :id="`${ fishingType.id }`"
                        :value="`${ fishingType.id }`"
                        v-model="searchForm.fishingTypes"
                    >
                    <label :for="`${ fishingType.id }`">
                        {{ fishingType.fishing_type_name }}
                    </label>
                </span>
            </div>
        </div>

        <div class="card-body mt-1">
            <div class="card-text line-height">
                <span v-for="tag in tagNames" :key="tag.id">
                    <RouterLink class="spot_tag" :to="`/tags/${tag.tag_name}`">
                        {{ tag.hashtag }}
                    </RouterLink>
                </span>
            </div>
        </div>
    </div>
</template>

<script>
    import { OK } from '../../../util'

    export default {
        props: {
            fishingTypeNames: {
                type: Array,
                required: true
            },
            tagNames: {
                type: Array,
                required: true
            },
        },
        data () {
            return {
                searchForm: {
                    searchWord: "",
                    fishingTypes: [],
                }
            }
        },
        methods: {
            async searchSpots () {
                console.log(this.searchForm)
                this.$router.push('/spots/search')

                const response = await axios.get('/api/spots/search', {
                    searchWord: this.searchWord,
                    fishingTypes: this.fishingTypes
                })

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }
                console.log(response.data)

            },
        }
    }
</script>