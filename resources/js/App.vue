<template>
    <div>
        <Navbar />
        <main>
            <Message />
            <RouterView />
        </main>
        <Footer />
    </div>
</template>

<script>
import { INTERNAL_SERVER_ERROR } from './util'
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
            handler (val) {
                if (val === INTERNAL_SERVER_ERROR) {
                    this.$router.push('/500')
                }
            },
            immediate: true
        },
        $route () {
            this.$store.commit('error/setCode', null)
        }
    }
}
</script>