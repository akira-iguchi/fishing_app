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
                </ul>
                <ul v-else>
                    <!-- ユーザ登録ページへのリンク -->
                    <li><RouterLink to="/signup"><i class="fas fa-user-plus"></i>&thinsp;新規登録</RouterLink></li>
                    <!-- ログインページへのリンク -->
                    <li ><RouterLink to="/login"><i class="fas fa-sign-in-alt"></i>&thinsp;ログイン</RouterLink></li>
                    <!-- ゲストログインへのリンク -->

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

            this.$router.push('/login')
        }
    }
}
</script>