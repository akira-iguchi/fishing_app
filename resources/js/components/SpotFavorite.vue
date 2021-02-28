<template>
    <div>
        <button
            type="button"
            class="btn m-0 p-1 shadow-none"
            :class="{'text-danger':this.isLikedBy}"
            @click="clickFavorite"
        >
            <i class="fas fa-heart mr-1" />
        </button>
        {{ countSpotFavorites }}
    </div>
</template>

<script>
    export default {
        props: {
            initialIsLikedBy: {
                type: Boolean,
                default: false,
            },
            initialCountSpotFavorites: {
                type: Number,
                default: 0,
            },
            authorized: {
                type: Boolean,
                default: false,
            },
            endpoint: {
                type: String,
            },
        },

        data() {
            return {
                isLikedBy: this.initialIsLikedBy,
                countSpotFavorites: this.initialCountSpotFavorites,
                gotToFavorite: false,
            }
        },

        methods: {
            clickFavorite() {
                if (!this.authorized) {
                    alert('お気に入り機能はログイン中のみ使用できます')
                    return
                }

                this.isLikedBy
                    ? this.unfavorite()
                    : this.favorite()
            },

            async favorite() {
                const response = await axios.put(this.endpoint)

                this.isLikedBy = true
                this.countSpotFavorites = response.data.countSpotFavorites
                this.gotToFavorite = true
            },

            async unfavorite() {
                const response = await axios.delete(this.endpoint)

                this.isLikedBy = false
                this.countSpotFavorites = response.data.countSpotFavorites
                this.gotToFavorite = false
            },
        },
    }
</script>