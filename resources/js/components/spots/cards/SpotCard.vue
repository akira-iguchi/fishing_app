<template>
    <div class="mx-auto d-block col-xl-3 col-lg-4 col-md-6 col-11">
        <div class="spot_card">
            <RouterLink :to="`/spots/${item.id}`">
                <div class="spot_card_img">
                    <img :src="`${item.first_spot_image}`" alt="釣りスポットの画像">
                </div>
            </RouterLink>

            <div class="spot_card_content">
                <div class="card_spot_name">
                    {{ item.spot_name }}
                </div>

                <div class="card_detail">

                    <div class="card_item mr-2">
                        <FavoriteButton
                            :spot="item"
                        />
                    </div>

                    <div class="card_item mr-2">
                        <i class="fa fa-comment mr-1"></i>{{ item.count_spot_comments }}
                    </div>

                    <div class="card_item">
                        <i class="fas fa-clock mr-1"></i>{{ item.created_at | moment }}
                    </div>

                    <RouterLink :to="`/users/${item.user_id}`">
                        <img :src="`${item.user.user_image}`" alt="釣りスポット投稿者の画像">
                    </RouterLink>
                </div>

                <p v-if="item.address && item.address.length > 0">
                    {{ item.address }}
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
            item: {
                type: Object,
                required: true
            },
        },
        filters: {
            moment: function (date) {
                return moment(date).format('MM/DD');
            }
        },
        methods: {
            favorite () {
                this.$emit('favorite', {
                    id: this.item.id,
                    liked: this.item.liked_by_user,
                })
            }
        }
    }
</script>