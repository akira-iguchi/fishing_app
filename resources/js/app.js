import './bootstrap'
import Vue from 'vue'
import SpotFavorite from './components/SpotFavorite'
import FollowButton from './components/FollowButton'

const app = new Vue({
    el: '#app',
    components: {
        SpotFavorite,
        FollowButton,
    }
})