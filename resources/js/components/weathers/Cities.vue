<template>
    <select
        class="cities"
        @change="changeCity"
    >
        <option disabled value="" selected>都道府県を選択してください</option>
        <option value="tokyo">東京</option>
        <option value="osaka">大阪</option>
        <option value="hokkaido">北海道</option>
        <option value="aomori">青森</option>
        <option value="iwate">岩手</option>
        <option value="miyagi">宮城</option>
        <option value="akita">秋田</option>
        <option value="yamagata">山形</option>
        <option value="fukushima">福島</option>
        <option value="ibaraki">茨城</option>
        <option value="tochigi">栃木</option>
        <option value="gunma">群馬</option>
        <option value="saitama">埼玉</option>
        <option value="chiba">千葉</option>
        <option value="kanagawa">神奈川</option>
        <option value="niigata">新潟</option>
        <option value="toyama">富山</option>
        <option value="ishikawa">石川</option>
        <option value="fukui">福井</option>
        <option value="yamanashi">山梨</option>
        <option value="nagano">長野</option>
        <option value="gifu">岐阜</option>
        <option value="sizuoka">静岡</option>
        <option value="aichi">愛知</option>
        <option value="mie">三重</option>
        <option value="siga">滋賀</option>
        <option value="kyoto">京都</option>
        <option value="hyogo">兵庫</option>
        <option value="nara">奈良</option>
        <option value="wakayama">和歌山</option>
        <option value="tottori">鳥取</option>
        <option value="shimane">島根</option>
        <option value="okayama">岡山</option>
        <option value="hiroshima">広島</option>
        <option value="yamaguchi">山口</option>
        <option value="tokushima">徳島</option>
        <option value="kagawa">香川</option>
        <option value="aichi">愛媛</option>
        <option value="kochi">高知</option>
        <option value="fukuoka">福岡</option>
        <option value="saga">佐賀</option>
        <option value="nagasaki">長崎</option>
        <option value="kumamoto">熊本</option>
        <option value="ooita">大分</option>
        <option value="miyagi">宮崎</option>
        <option value="kagoshima">鹿児島</option>
        <option value="okinawa">沖縄</option>
    </select>
</template>

<script>
    export default {
        methods: {
            changeCity (val) {
                this.$emit("selectCity", val.target)
            },
        }
    }
</script>