<template>
    <div class="search_group">
        <div class="spotIndex_search_form">

            <div class="spot_search_top">
                <input
                    type="text"
                    class="spotIndex_search_text"
                    placeholder="キーワードを入力"
                    v-model="searchWord"
                >
                <button type="submit" class="spotIndex_search_button" @click="searchSpots">
                    <i class="fas fa-search"></i>
                </button>
            </div>

            <div>
                <span v-for="fishingType in fishingTypeNames" :key="fishingType.id">
                    <input
                        class="search_check"
                        type="checkbox"
                        :id="`${ fishingType.id }`"
                        :value="`${ fishingType.id }`"
                        v-model="fishingTypes"
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
                searchWord: "",
                fishingTypes: [],
            }
        },
        methods: {
            async searchSpots () {
                const response = await axios.get('/api/spots/search', {
                    searchWord: this.searchWord,
                    fishingTypes: this.fishingTypes
                })

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }
                console.log(response.data)

                this.$router.push('/spots/search')
            },
        }
    }
</script>