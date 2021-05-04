<template>
    <div class="search_group">
        <div class="spotIndex_search_form">

            <form class="spot_search_top" @submit.prevent="getsearchData">
                <input
                    type="text"
                    class="spotIndex_search_text"
                    placeholder="キーワードを入力"
                    v-model="searchWord"
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
            parentName: {
                type: String,
                required: true
            },
        },
        data () {
            return {
                parent: this.parentName,
                searchWord: "",
                fishingTypes: [],
            }
        },
        methods: {
            async getsearchData () {
                if (this.parent === 'toppage' || this.parent === 'tag') {
                    this.$router.push({
                        name: 'search',
                        params: {
                            searchWord: this.searchWord,
                            fishingTypes: this.fishingTypes
                        }
                    })
                } else {
                    this.$emit("getsearchData", [
                        this.searchWord,
                        this.fishingTypes
                    ])
                }
            },
        }
    }
</script>