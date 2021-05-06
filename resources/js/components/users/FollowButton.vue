<template>
    <div>
        <span>{{ user.followings.length }} フォロー</span>
        <span>{{ user.followers.length }} フォロワー</span>

        <button
            class="btn-sm shadow-none border border-primary p-2"
            v-if="AuthUser.id !== user.id"
            :class="[
                isFollowedBy ? 'bg-primary text-white' : 'bg-white'
            ]"
            @click="onFollowClick"
        >
        <i
            class="mr-1"
            :class="[
                isFollowedBy ? 'fas fa-user-check' : 'fas fa-user-plus'
            ]"
        ></i>
        {{ isFollowedBy ? 'フォロー中' : 'フォロー' }}
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
            initialIsFollowedBy: {
                type: Boolean,
                required: true,
            },

        },
        data () {
            return {
                isFollowedBy: this.initialIsFollowedBy,
            }
        },
        computed: {
            AuthUser () {
                return this.$store.getters['auth/AuthUser']
            },
        },
        watch: {
            initialIsFollowedBy (newFollowedBy) {
                this.isFollowedBy = newFollowedBy
            },
        },
        methods: {
            onFollowClick () {
                if (this.isFollowedBy) {
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

                this.user.followers.length += 1
                this.isFollowedBy = true

                this.$emit('follow')
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

                this.user.followers.length -= 1
                this.isFollowedBy = false

                this.$emit('unfollow')
            },
        },
    }
</script>
