<template>
    <div class="page_body">
        <Navbar />
        <main>
            <Message />
            <!-- URL によって HTML 部品が切り替わる（紙芝居のような感じ） -->
            <RouterView />
        </main>
        <Footer />
    </div>
</template>

<script>
import { NOT_FOUND, UNAUTHORIZED, INTERNAL_SERVER_ERROR } from './util'
import Message from './components/commons/Message.vue'
import Navbar from './components/commons/Navbar.vue'
import Footer from './components/commons/Footer.vue'

export default {
    components: {
        Message,
        Navbar,
        Footer,
    },
    computed: {
        errorCode () {
        return this.$store.state.error.code
        }
    },
    watch: {
        errorCode: {
            async handler (val) {
                if (val === INTERNAL_SERVER_ERROR) {
                    this.$router.push('/500')
                } else if (val === UNAUTHORIZED) {
                    // トークンをリフレッシュ
                    await axios.get('/api/refresh-token')
                    // ストアのuserをクリア
                    this.$store.commit('auth/setUser', null)
                    this.$router.push('/login')
                } else if (val === NOT_FOUND) {
                    this.$router.push('/not-found')
                }
            },
            immediate: true
        },
        $route () {
            this.$store.commit('error/setCode', null)
        }
    },
}
</script>