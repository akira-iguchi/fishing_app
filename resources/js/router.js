import Vue from 'vue'
import VueRouter from 'vue-router'

import TopPage from './pages/spots/TopPage.vue'
import ContactForm from './pages/contacts/ContactForm.vue'
import ConfirmContact from './pages/contacts/ConfirmContact.vue'
import ThanksContact from './pages/contacts/ThanksContact.vue'
import CreateSpot from './pages/spots/CreateSpot.vue'
import SearchSpots from './pages/spots/SearchSpots.vue'
import SpotDetail from './pages/spots/SpotDetail.vue'
import EditSpot from './pages/spots/EditSpot.vue'
import UserProfile from './pages/users/UserProfile.vue'
import EditUserProfile from './pages/users/EditUserProfile.vue'
import EventCalendar from './pages/events/EventCalendar.vue'
import EditEvent from './pages/events/EditEvent.vue'
import TagSpots from './pages/tags/TagSpots.vue'
import FishingTypes from './pages/fishingTypes/FishingTypes.vue'
import Login from './pages/auth/Login.vue'
import Register from './pages/auth/Register.vue'
import SystemError from './pages/errors/System.vue'
import NotFound from './pages/errors/NotFound.vue'

import store from './store'

Vue.use(VueRouter)

const routes = [
    {
        path: '/',
        component: TopPage
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
        path: '/contact',
        name: 'contact',
        component: ContactForm,
    },
    {
        path: '/contact/confirm',
        name: 'contact.confirm',
        component: ConfirmContact,
    },
    {
        path: '/contact/thanks',
        component: ThanksContact,
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
        name: 'search',
        component: SearchSpots,
        props: true,
        props: route => {
            const page = route.query.page
            return { page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1 }
        },
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
        path: '/users/:id/edit',
        component: EditUserProfile,
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
        path: '/users/:id/events',
        component: EventCalendar,
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
        path: '/users/:userId/events/:eventId/edit',
        component: EditEvent,
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
    // URL 文字列中のハッシュの変化では画面遷移が発生しないブラウザの仕様
    // → デフォルトだとURLに「＃」がついてしまう（hash モード）ため、historyモードを使用して本来の URL の形を再現
    mode: 'history',
    scrollBehavior () {
        return { x: 0, y: 0 }
    },
    routes
})

export default router