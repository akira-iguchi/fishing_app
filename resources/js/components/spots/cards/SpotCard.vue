<template>
    <div class="mx-auto d-block col-xl-3 col-lg-4 col-md-6 col-11">
        <div class="spot_card">
            <RouterLink :to="`/spots/${spot.id}`">
                <div class="spot_card_img">
                    <img :src="`${spot.first_spot_image}`" alt="釣りスポットの画像">
                </div>
            </RouterLink>

            <div class="spot_card_content">
                <div class="card_spot_name">
                    {{ spot.spot_name }}
                </div>

                <div class="card_detail">

                    <div class="card_item mr-2">
                        <FavoriteButton
                            :spot="spot"
                        />
                    </div>

                    <div class="card_item mr-2">
                        <i class="fa fa-comment mr-1"></i>{{ spot.count_spot_comments }}
                    </div>

                    <div class="card_item">
                        <i class="fas fa-clock mr-1"></i>{{ spot.created_at | moment }}
                    </div>

                    <RouterLink :to="`/users/${spot.user_id}`">
                        <img :src="`${spot.user.user_image}`" alt="釣りスポット投稿者の画像">
                    </RouterLink>
                </div>

                <p v-if="spot.address && spot.address.length > 0">
                    {{ spot.address }}
                </p>
                <!-- <p>{{ spot.explanation }}</p> -->
            </div>
        </div>
    </div>
</template>

<script>
    import FavoriteButton from '../FavoriteButton.vue'
    import moment from 'moment';

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
        filters: {
            moment: function (date) {
                return moment(date).format('MM/DD');
            }
        },
    }
</script>