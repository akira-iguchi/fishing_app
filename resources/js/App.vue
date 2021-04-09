<template>
    <div>
        <Navbar />
        <main>
            <RouterView />
        </main>
        <Footer />
    </div>
</template>

<script>
import Navbar from './components/commons/Navbar.vue'
import Footer from './components/commons/Footer.vue'

export default {
    components: {
        Navbar,
        Footer
    }
}
</script>