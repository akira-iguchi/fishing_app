<template>
    <div>
        <div v-if="isLogin">
            <div class="container">
                <!-- @include('spots.searches.search_form') -->

                <div class="text-center">
                    <li><RouterLink to="/spots/create" class="btn create_btn">釣りスポットを投稿</RouterLink></li>
                </div>

                <div class="toppage_under">
                    <div class="w-100">
                        <h2 class="toppage_heading">人気の釣りスポット<i class="fas fa-crown"></i></h2>
                        <div class="row">
                            <!-- カードの大きさが違うため直書き -->
                        </div>
                    </div>

                    <aside class="aside_hidden">
                        <select id="js-prefectures">
                            <!-- @include('weathers.prefecture') -->
                        </select>

                        <div class="entire_weather">
                            <div id="city-name"></div>
                            <div id="weather"></div>
                        </div>
                    </aside>
                </div>

                <hr>

                <div v-if="followUserSpots && followUserSpots.length > 0">
                    <h2 class="toppage_heading">フォローしたユーザーの投稿</h2>
                    <div class="row">
                        <SpotCard
                            v-for="spot in followUserSpots"
                            :key="spot.id"
                            :item="spot"
                        />
                    </div>
                    <hr>
                </div>

                <h2 class="toppage_heading">最近の投稿</h2>
                <div class="row">
                    <SpotCard
                        v-for="spot in recentSpots"
                        :key="spot.id"
                        :item="spot"
                    />
                </div>

            </div>
        </div>

        <div v-else>
            <div v-bind:class="{ 'js-loaded': isActive }" id="js-loading">
                <div class="js-spinner"></div>
            </div>

            <div class="top text-center">
                <h1 class="top-title">Fishing Spot</h1>
                <p>釣り場を投稿して、<span>共有し合おう！</span></p>
                <button class="top_login_button"><RouterLink to="/login"><span><i class="fas fa-user-plus mr-1"></i>ログイン</span></RouterLink></button>
                <button class="top_signup_button"><RouterLink to="/signup"><span><i class="fas fa-sign-in-alt mr-1"></i>新規登録</span></RouterLink></button>
                <br>
                <button @click="guestLogin" class="top_guest_login_button"><span><i class="fas fa-sign-in-alt mr-1"></i>ゲストログイン</span></button>
            </div>

            <div class="top-slider">
                <div class="spot-intro_image">
                    <img src="/images/fishing_boat_man.png" alt="釣り画像">
                </div>

                <div class="spot-intro_expla">
                    <p>Fishing Spotとは？</p>
                    <p>
                        &emsp;Fishing Spotとは、釣り場を投稿し、釣り場にコメントして釣果などを共有するアプリです。また、釣り場におすすめの釣り方を選択することもできます。
                        さらに、カレンダーで釣りの予定、記録をすることができ、このアプリ１つで満足できます。
                        <br>
                        &emsp;最近は、釣りの技術が進み、釣りを始める人も多くなっています。そこで、釣り初心者の方でもこのアプリ1つで釣りを知り、楽しんでもらえるように、このアプリを作成しました。
                    </p>
                </div>
            </div>

            <div class="top-slider">
                <div class="self-intro_expla">
                    <p>自己紹介</p>
                    <img src="/images/akira.jpeg" alt="自己紹介の画像">
                    <p>
                        &emsp;井口 晶。19歳。プログラミングに励む、田舎好きな大阪生まれ育ちの都会男子です。関西大学第一高等学校入学。そのまま関西大学法学部へ進学(現在1年生)。
                        <br>
                        &emsp;趣味は、釣りと筋トレ。釣りで自然と戯れつつ、筋トレで自分を追い込んでます。
                    </p>
                </div>

                <div class="self-intro_image">
                    <img src="/images/akira.jpeg" alt="自己紹介の画像">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { OK } from '../../util'
    import SpotCard from '../../components/spots/cards/SpotCard.vue'

    export default {
        components: {
            SpotCard
        },
        data () {
            return {
                followUserSpots: [],
                recentSpots: [],
                isActive: false,
            }
        },
        computed: {
            isLogin () {
                return this.$store.getters['auth/check']
            },
            username () {
                return this.$store.getters['auth/username']
            }
        },
        created() {
            window.addEventListener("scroll", this.handleScroll);
        },
        mounted: function() {
            this.isActive = true
        },
        watch: {
            $route: {
                async handler () {
                    await this.fetchSpots()
                },
                immediate: true
            }
        },
        methods: {
            async fetchSpots () {
                const response = await axios.get('/api/')

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.followUserSpots = response.data[2]
                this.recentSpots = response.data[1]
            },
            async guestLogin () {
                await this.$store.dispatch('auth/guestLogin')

                this.$router.push('/', () => {})
            },
            handleScroll() {
                const targetElement = this.$el.querySelectorAll('.top-slider') || null
                if (targetElement !== null) {
                    for(let i = 0; i < targetElement.length; i++) {
                        const getElementDistance = targetElement[i].getBoundingClientRect().top
                        + targetElement[i].clientHeight * .6
                        if (window.innerHeight > getElementDistance) {
                            targetElement[i].classList.add("show");
                        }
                    }
                }
            },
        },
    }
</script>