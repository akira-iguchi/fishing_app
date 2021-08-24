<template>
    <div>
        <div v-if="isLogin">
            <div class="container">
                <SearchForm
                    :fishingTypeNames="fishingTypeNames"
                    :tagNames="tagNames"
                    :parentName="parentName"
                />

                <div class="text-center">
                    <span>
                        <RouterLink to="/spots/create" class="btn create_btn">釣りスポットを投稿</RouterLink>
                    </span>
                </div>

                <div class="toppage_under">
                    <div class="w-100">
                        <h2 class="toppage_heading">人気の釣りスポット<i class="fas fa-crown"></i></h2>

                        <div v-show="circleLoading" class="mt-5">
                            <Loader />
                        </div>

                        <div class="row">
                            <SpotCard
                                v-for="spot in rankingSpots"
                                :key="spot.id"
                                :spot="spot"
                                :isRanking="true"
                            />
                        </div>
                    </div>

                    <aside class="aside_hidden">
                        <!-- 天気予報 -->
                        <WeatherForecast />
                    </aside>
                </div>

                <hr>

                <div v-if="followUserSpots && followUserSpots.length > 0">
                    <h2 class="toppage_heading">フォローしたユーザーの投稿</h2>

                    <div v-show="circleLoading" class="mt-3">
                        <Loader />
                    </div>

                    <div class="row">
                        <SpotCard
                            v-for="spot in followUserSpots"
                            :key="spot.id"
                            :spot="spot"
                            :isRanking="false"
                        />
                    </div>
                    <hr>
                </div>

                <h2 class="toppage_heading">最近の投稿</h2>

                <div v-show="circleLoading" class="mt-3">
                    <Loader />
                </div>

                <div class="row">
                    <SpotCard
                        v-for="spot in recentSpots"
                        :key="spot.id"
                        :spot="spot"
                        :isRanking="false"
                    />
                </div>

            </div>
        </div>

        <div v-else>
            <div :class="{ 'toppage_loaded': isLoaded }" class="toppage_loading">
                <div class="spinner"></div>
            </div>

            <div v-show="isLoaded">
                <div class="top text-center">
                    <h1 class="top_title">Fishing App</h1>
                    <p>釣り場を投稿して、<span>共有し合おう！</span></p>
                    <button class="top_login_button">
                        <RouterLink to="/login"><span><i class="fas fa-sign-in-alt mr-1"></i>ログイン</span></RouterLink>
                    </button>
                    <button class="top_signup_button">
                        <RouterLink to="/signup"><span><i class="fas fa-user-plus mr-1"></i>新規登録</span></RouterLink>
                    </button>
                    <br>
                    <button @click="guestLogin" class="top_guest_login_button">
                        <span><i class="fas fa-sign-in-alt mr-1"></i>ゲストログイン</span>
                    </button>
                </div>

                <div class="top_slider">
                    <div class="spot_intro_image">
                        <img src="/images/fishing_boat_man.png" alt="釣り画像">
                    </div>

                    <div class="spot_intro_expla">
                        <p>Fishing Appとは？</p>
                        <p>
                            &emsp;Fishing Appとは、釣り場を投稿し、釣り場にコメントして釣果などを共有するアプリです。
                            また、釣り場におすすめの釣り方を選択することもできます。
                            さらに、カレンダーで釣りの予定、記録をすることができ、このアプリ１つで満足できます。
                            <br>
                            &emsp;最近は、釣りの技術が進み、釣りを始める人も多くなっています。
                            そこで、釣り初心者の方でもこのアプリ1つで釣りを知り、楽しんでもらえるように、このアプリを作成しました。
                        </p>
                    </div>
                </div>

                <div class="top_slider">
                    <div class="self_intro_expla">
                        <p>自己紹介</p>
                        <img src="/images/akira.jpeg" alt="自己紹介の画像">
                        <p>
                            &emsp;井口 晶。20歳。プログラミングに励む、田舎好きな大阪生まれ育ちの都会男子。
                            関西大学法学部所属(現在2回生)。毎日、法学やプログラミングの知識を取り入れています。
                            <br>
                            &emsp;釣りと筋トレが趣味。釣りで自然と戯れつつ、筋トレで自分を追い込んでいます。
                        </p>
                    </div>

                    <div class="self_intro_image">
                        <img src="/images/akira.jpeg" alt="自己紹介の画像">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { OK } from '../../util'
    import Loader from '../../components/commons/Loader.vue'
    import SpotCard from '../../components/spots/cards/SpotCard.vue'
    import SearchForm from '../../components/spots/searches/SearchForm.vue'
    import WeatherForecast from '../../components/weathers/WeatherForecast.vue'

    export default {
        components: {
            Loader,
            SpotCard,
            SearchForm,
            WeatherForecast,
        },
        data () {
            return {
                parentName: 'toppage',
                fishingTypeNames: [],
                followUserSpots: [],
                recentSpots: [],
                rankingSpots: [],
                tagNames: [],
                isLoaded: false,
                circleLoading: true
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
            this.circleLoading = true
            this.isLoaded = false
            window.setTimeout(() => {
                this.isLoaded = true
            }, 300);
        },
        watch: {
            $route: {
                async handler () {
                    await this.fetchSpots()
                },
                immediate: true
            },
            isLogin () {
                this.circleLoading = true
                this.fetchSpots()
            }
        },
        methods: {
            async guestLogin () {
                await this.$store.dispatch('auth/guestLogin', {
                    email: 'guest@example.com',
                    password: 'guest123'
                })

                this.$router.push('/', () => {})

                this.$store.commit('message/setContent', {
                    content: 'ゲストユーザーでログインしました',
                    timeout: 5000
                })

            },
            async fetchSpots () {
                this.fishingTypeNames = []
                this.tagNames = []
                this.recentSpots = []
                this.followUserSpots = []
                this.rankingSpots = []

                const response = await axios.get('/api')

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.circleLoading = false

                // ログインしている場合、データ読み込み
                if (this.isLogin === true) {
                    this.fishingTypeNames = response.data[0][0]
                    this.tagNames = response.data[0][1]
                    this.recentSpots = response.data[1]
                    this.followUserSpots = response.data[2]
                    this.rankingSpots = response.data[3]
                }
            },
            handleScroll() {
                // 要素の6割ほどの高さが出たら表示
                const targetElement = this.$el.querySelectorAll('.top_slider') || null
                if (targetElement !== null) {
                    for (let i = 0; i < targetElement.length; i++) {
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