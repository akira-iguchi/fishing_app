require('./bootstrap');

window.Vue = require('vue').default;

import Vue from 'vue'
import router from './router'
import store from './store'
import App from './App.vue'

const app = new Vue({
    el: '#app',
    router,
    components: { App },
    template: '<App />'
});
