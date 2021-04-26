<template>
    <div class="search_group">
        <div class="spotIndex_search_form">

            <div class="spot_search_top">
                <input type="text" class="spotIndex_search_text" placeholder="キーワードを入力">
                <button type="submit" class="spotIndex_search_button" @click="searchSpot"><i class="fas fa-search"></i></button>
            </div>

            <div>
                <span v-for="fishingType in fishingTypeNames" :key="fishingType.id">
                    <input class="search_check" type="checkbox" :id="`${ fishingType.id }`" :value="`${ fishingType.id }`">
                    <label :for="`${ fishingType.id }`">{{ fishingType.fishing_type_name }}</label>
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
        },
        methods: {
            async addComment () {
                const formData = new FormData()
                formData.append('comment', this.commentContent)
                formData.append('comment_image', this.commentImage)
                const response = await axios.post(`/api/spots/${this.id}/comments`, formData)

                if (response.status === UNPROCESSABLE_ENTITY) {
                    this.commentErrors = response.data.errors
                    return false
                }

                this.spot.count_spot_comments += 1
                this.commentImageMessage = ""
                this.preview = null
                this.commentContent = ''
                this.commentErrors = null
            },
        }
    }
</script>