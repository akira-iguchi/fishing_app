import Vue from 'vue'
import VueRouter from 'vue-router'

import Spots from './pages/Spots.vue'
import Login from './pages/auth/Login.vue'
import Register from './pages/auth/Register.vue'

import store from './store'

Vue.use(VueRouter)

const routes = [
    {
        path: '/',
        component: Spots
    },
    {
        path: '/login',
        component: Login,
        beforeEnter (to, from, next) {
            if (store.getters['auth/check']) {
                next('/')
            } else {
                next()
            }
        }
    },
    {
        path: '/signup',
        component: Register
    },
]

const router = new VueRouter({
    mode: 'history',
    routes
})

export default router