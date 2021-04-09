import Vue from 'vue'
import VueRouter from 'vue-router'

import Login from './auth/Login.vue'
import Register from './auth/Register.vue'

Vue.use(VueRouter)

const routes = [
    {
        path: '/login',
        component: Login,
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