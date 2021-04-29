<template>
    <div class="mini_card">
        <RouterLink :to="`/spots/${spot.id}`">
            <div class="mini_card_img">
                <img :src="`${spot.first_spot_image}`" alt="釣りスポットの画像">
            </div>
        </RouterLink>

        <div class="mini_card_content">
            <div class="card_spot_name">
                {{ spot.spot_name }}
            </div>

            <div class="mini_card_detail">

                <div class="card_item mr-1">
                    <FavoriteButton
                        :spot="spot"
                    />
                </div>

                <div class="card_item">
                    <i class="fa fa-comment ml-1 mr-1"></i>{{ spot.spot_comments.length }}
                </div>

                <RouterLink :to="`/users/${spot.user_id}`">
                    <img :src="`${spot.user.user_image}`" alt="釣りスポット投稿者の画像">
                </RouterLink>
            </div>
        </div>
    </div>
</template>

<script>
    import FavoriteButton from '../FavoriteButton.vue'

    export default {
        components: {
            FavoriteButton,
        },
        props: {
            spot: {
                type: Object,
                required: true
            },
        },
    }
</script>