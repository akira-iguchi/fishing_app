<template>
    <div>
        <span>{{ user.count_followings }} フォロー</span>
        <span>{{ user.count_followers }} フォロワー</span>

        <button
            class="btn-sm shadow-none border border-primary p-2"
            v-if="AuthUser.id !== user.id"
            :class="buttonColor"
            @click="onFollowClick"
        >
        <i
            class="mr-1"
            :class="buttonIcon"
        ></i>
        {{ buttonText }}
        </button>
    </div>
    </template>

<script>
    import { OK } from '../../util'

    export default {
        props: {
            user: {
                type: Object,
                required: true,
            },

        },
        computed: {
            AuthUser () {
                return this.$store.getters['auth/AuthUser']
            },
            buttonColor () {
                return this.user.followed_by
                ? 'bg-primary text-white'
                : 'bg-white'
            },
            buttonIcon () {
                return this.user.followed_by
                ? 'fas fa-user-check'
                : 'fas fa-user-plus'
            },
            buttonText () {
                return this.user.followed_by
                ? 'フォロー中'
                : 'フォロー'
            },
        },

        methods: {
            onFollowClick () {
                if (this.user.followed_by) {
                    this.unfollow()
                } else {
                    this.follow()
                }
            },
            async follow () {
                const response = await axios.put(`/api/users/${this.user.id}/follow`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                if (this.user.id == this.AuthUser.id) {
                    return false
                }

                this.user.count_followers += 1
                this.user.followed_by = true
            },
            async unfollow () {
                const response = await axios.delete(`/api/users/${this.user.id}/follow`)

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                if (this.user.id == this.AuthUser.id) {
                    return false
                }

                this.user.count_followers -= 1
                this.user.followed_by = false
            },
        },
    }
</script>
