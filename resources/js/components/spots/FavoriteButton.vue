<template>
    <span>
        <button
            class="btn m-0 p-0 shadow-none"
            :class="{ 'text-danger' : spot.liked_by_user, 'animated heartBeat fast' : this.gotToLike }"
            @click="onFavoriteClick"
        >
            <i class="fas fa-heart"></i>
        </button>
        {{ spot.count_spot_favorites }}
    </span>
</template>

<script>
    import { OK } from '../../util'

    export default {
        props: {
            spot: {
                type: Object,
                required: true,
            },
        },
        data() {
            return {
                gotToLike: false,
            }
        },
        methods: {
            onFavoriteClick () {
                if (this.spot.liked_by_user) {
                    this.unfavorite()
                } else {
                    this.favorite()
                }
            },
            async favorite () {
                const response = await axios.put(`/api/spots/${this.spot.id}/favorite`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.gotToLike = true
                this.spot.count_spot_favorites += 1
                this.spot.liked_by_user = true
            },
            async unfavorite () {
                const response = await axios.delete(`/api/spots/${this.spot.id}/favorite`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.gotToLike = false
                this.spot.count_spot_favorites -= 1
                this.spot.liked_by_user = false
            },
        }
    }
</script>