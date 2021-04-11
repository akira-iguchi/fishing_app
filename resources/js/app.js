require('./bootstrap');

import Vue from 'vue'
import router from './router'
import store from './store'
import App from './App.vue'
import * as VueGoogleMaps from 'vue2-google-maps'

Vue.use(VueGoogleMaps, {
    load: {
        key: googleMapApiKey,
    }
})

const createApp = async () => {
    await store.dispatch('auth/currentUser')

    new Vue({
        el: '#app',
        router,
        store,
        components: { App },
        template: '<App />'
    })
}

createApp()
