import './bootstrap'
import Vue from 'vue'

import SpotFavorite from './components/SpotFavorite'
import SpotTagsInput from './components/SpotTagsInput'
import FollowButton from './components/FollowButton'
import Comments from './components/Comments'
import UserTabs from './components/UserTabs'

const app = new Vue({
    el: '#app',
    components: {
        SpotFavorite,
        SpotTagsInput,
        FollowButton,
        Comments,
        UserTabs,
    }
})