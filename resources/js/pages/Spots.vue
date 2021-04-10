<template>
    <div>
        <div v-if="isLogin" class="signup_body">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="login-signup">
                        <div class="login-signup-header">
                            <h1>トップページ</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else>
            <!-- <div id="js-loading">
                <div class="js-spinner"></div>
            </div> -->

            <div class="top text-center">
                <h1 class="top-title">Fishing Spot</h1>
                <p>釣り場を投稿して、<span>共有し合おう！</span></p>
                <RouterLink to="/login" class="top-login-button"><span><i class="fas fa-user-plus mr-1"></i>ログイン</span></RouterLink>
                <RouterLink to="/signup" class="top-signup-button"><span><i class="fas fa-sign-in-alt mr-1"></i>新規登録</span></RouterLink>
                <br>
                <span class="top-guest_login-button"><i class="fas fa-sign-in-alt mr-1"></i>ゲストログイン</span>
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
    export default {
        computed: {
            isLogin () {
                return this.$store.getters['auth/check']
            },
            username () {
                return this.$store.getters['auth/username']
            }
        },
    }
</script>