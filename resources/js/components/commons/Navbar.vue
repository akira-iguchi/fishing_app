<template>
    <header>
        <span @click="closeNavContent">
            <RouterLink class="nav_title" to="/">
                <i class="fas fa-fish mr-1"></i>Fishing Spot
            </RouterLink
        ></span>
        <nav class="nav_container">
            <nav class="nav_button" @click="openNavContent">
                <span class="nav_icon_bar" :class="{ 'change_nav_icon_bar' : isNavContentOpen }"></span>
                <span class="nav_icon_bar" :class="{ 'change_nav_icon_bar' : isNavContentOpen }"></span>
                <span class="nav_icon_bar" :class="{ 'change_nav_icon_bar' : isNavContentOpen }"></span>
            </nav>
            <nav
                class="nav_background"
                :class="{ 'visible_nav_background' : isNavContentOpen }"
            ></nav>

            <nav class="nav_content" :class="{ 'open_nav_content' : isNavContentOpen }">
                <ul v-if="isLogin">
                    <li>
                        <span @click="logout"><i class="fas fa-sign-in-alt mr-1"></i>ログアウト</span>
                    </li>
                    <li @click="closeNavContent"><RouterLink :to="`/users/${ AuthUser.id }/events`">カレンダー</RouterLink></li>
                    <li @click="closeNavContent"><RouterLink to="/fishing_types">釣り方一覧</RouterLink></li>
                    <li @click="closeNavContent"><RouterLink to="/spots/create">投稿</RouterLink></li>
                    <li @click="closeNavContent"><RouterLink :to="`/users/${ AuthUser.id }`">{{ AuthUser.user_name }}</RouterLink></li>
                </ul>
                <ul v-else>
                    <li @click="closeNavContent"><RouterLink to="/signup"><i class="fas fa-user-plus mr-1"></i>新規登録</RouterLink></li>
                    <li><span @click="guestLogin">ゲストログイン</span></li>
                    <li @click="closeNavContent"><RouterLink to="/login">ログイン</RouterLink></li>
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
        AuthUser () {
            return this.$store.getters['auth/AuthUser']
        }
    },
    data () {
            return {
                isNavContentOpen: false
            }
        },
    methods: {
        openNavContent () {
            this.isNavContentOpen = !this.isNavContentOpen
        },
        closeNavContent () {
            this.isNavContentOpen = false
        },
        async logout () {
            await this.$store.dispatch('auth/logout')

            this.$router.push('/', () => {})

            this.$store.commit('message/setContent', {
                content: 'ログアウトしました',
                timeout: 4000
            })

            this.isNavContentOpen = false
        },
        async guestLogin () {
            await this.$store.dispatch('auth/guestLogin', {
                email: 'guest@example.com',
                password: 'guest123'
            })

            this.$router.push('/', () => {})

            this.$store.commit('message/setContent', {
                content: 'ゲストユーザーでログインしました',
                timeout: 4000
            })

            this.isNavContentOpen = false
        }
    },
}
</script>