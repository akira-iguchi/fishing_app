<template>
    <header><RouterLink class="nav-title" to="/"><i class="fas fa-fish"></i>&thinsp;Fishing Spot</RouterLink>
        <nav class="nav-container">
            <nav class="nav-bg"></nav>
            <nav class="nav-button" tabindex="0">
                <span class="nav-icon-bar"></span>
                <span class="nav-icon-bar"></span>
                <span class="nav-icon-bar"></span>
            </nav>

            <nav class="nav-content" tabindex="0">
                <ul v-if="isLogin">
                    <li><span @click="logout"><i class="fas fa-sign-in-alt"></i>&thinsp;ログアウト</span></li>
                    <li><RouterLink to="/spots/create">投稿</RouterLink></li>
                </ul>
                <ul v-else>
                    <li><RouterLink to="/signup"><i class="fas fa-user-plus"></i>&thinsp;新規登録</RouterLink></li>
                    <li><RouterLink to="/login"><i class="fas fa-sign-in-alt"></i>&thinsp;ログイン</RouterLink></li>
                    <li><span @click="guestLogin"><i class="fas fa-sign-in-alt"></i>&thinsp;ゲストログイン</span></li>
                </ul>
            </nav>
        </nav>
    </header>
</template>

<script>
export default {
    computed: {
        isLogin () {
            return this.$store.getters['auth/check']
        },
        username () {
            return this.$store.getters['auth/username']
        }
    },
    methods: {
        async logout () {
            await this.$store.dispatch('auth/logout')

            this.$router.push('/', () => {})
        },
        async guestLogin () {
            await this.$store.dispatch('auth/guestLogin')

            this.$router.push('/', () => {})
        }
    },
}
</script>