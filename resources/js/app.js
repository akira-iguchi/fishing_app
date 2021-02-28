import './bootstrap'
import Vue from 'vue'
import SpotFavorite from './components/SpotFavorite'
import SpotTagsInput from './components/SpotTagsInput'
import FollowButton from './components/FollowButton'

const app = new Vue({
    el: '#app',
    components: {
        SpotFavorite,
        SpotTagsInput,
        FollowButton,
    }
})