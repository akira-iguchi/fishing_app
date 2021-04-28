import Vue from 'vue'
import VueRouter from 'vue-router'

import Spots from './pages/spots/Spots.vue'
import CreateSpot from './pages/spots/CreateSpot.vue'
import SearchSpots from './pages/spots/SearchSpots.vue'
import SpotDetail from './pages/spots/SpotDetail.vue'
import EditSpot from './pages/spots/EditSpot.vue'
import UserProfile from './pages/users/UserProfile.vue'
import TagSpots from './pages/tags/TagSpots.vue'
import FishingTypes from './pages/fishing_types/FishingTypes.vue'
import Login from './pages/auth/Login.vue'
import Register from './pages/auth/Register.vue'
import SystemError from './pages/errors/System.vue'
import NotFound from './pages/errors/NotFound.vue'

import store from './store'

Vue.use(VueRouter)

const routes = [
    {
        path: '/',
        component: Spots
    },
    {
        path: '/500',
        component: SystemError
    },
    {
        path: '*',
        component: NotFound
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
        component: Register,
        beforeEnter (to, from, next) {
            if (store.getters['auth/check']) {
                next('/')
            } else {
                next()
            }
        }
    },
    {
        path: '/spots/create',
        component: CreateSpot,
        beforeEnter (to, from, next) {
            if (store.getters['auth/check']) {
                next()
            } else {
                next('/')
            }
        }
    },
    {
        path: '/spots/search',
        component: SearchSpots,
        props: true,
        beforeEnter (to, from, next) {
            if (store.getters['auth/check']) {
                next()
            } else {
                next('/')
            }
        }
    },
    {
        path: '/spots/:id',
        component: SpotDetail,
        props: true,
        beforeEnter (to, from, next) {
            if (store.getters['auth/check']) {
                next()
            } else {
                next('/')
            }
        }
    },
    {
        path: '/spots/:id/edit',
        component: EditSpot,
        props: true,
        beforeEnter (to, from, next) {
            if (store.getters['auth/check']) {
                next()
            } else {
                next('/')
            }
        }
    },
    {
        path: '/users/:id',
        component: UserProfile,
        props: true,
        beforeEnter (to, from, next) {
            if (store.getters['auth/check']) {
                next()
            } else {
                next('/')
            }
        }
    },
    {
        path: '/tags/:name',
        component: TagSpots,
        props: true,
        beforeEnter (to, from, next) {
            if (store.getters['auth/check']) {
                next()
            } else {
                next('/')
            }
        }
    },
    {
        path: '/fishing_types',
        component: FishingTypes,
        props: true,
        beforeEnter (to, from, next) {
            if (store.getters['auth/check']) {
                next()
            } else {
                next('/')
            }
        }
    },
]

const router = new VueRouter({
    mode: 'history',
    routes
})

export default router