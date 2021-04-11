<template>
    <div v-if="isLogin"  class="container">
        <div class="row">
            <div class="mx-auto d-block col-lg-10 col-md-11 spot_form">

                <h1>釣りスポット作成</h1>

                <input class="spot_search" id="address" type="text" v-model="mapAddress" placeholder="所在地を入力"/>
                <button @click="searchAddress" class="spot_search_button"><i class="fas fa-search"></i></button>

                <!-- <GmapMap :center="mapLocation" :zoom="15" map-type-id="terrain" id="map" @click="updateLocation">
                    <GmapMarker :animation="2" :position="mapLocation" :clickable="true" :draggable="true" @dragend="updateLocation" />
                </GmapMap> -->
                <p>マーカーの移動も可能だよ！</p>

                <form>
                    <input id="spot_latitude" type="number" :value="latitude">
                    <input id="spot_longitude" type="number" :value="longitude">

                    <div class="form-group">
                        <label for="spot_name" class="required">釣りスポット名</label>
                        <input id="spot_name" type="text" class="form-control" placeholder="例） 〇〇釣り公園" required>
                    </div>

                    <div class="form-group">
                        <label for="spot_address">所在地</label>
                        <input id="spot_address" type="text" class="form-control" placeholder="例） 〇〇県〇〇市〇〇区〇〇町1-1-1">
                    </div>

                    <div class="form-group">
                        <label>画像（３つまで）</label><br>
                        <input id="image1" type="file" @change="confirmImage">
                        <p v-if="confirmedImage">
                            <img id="file1-preview" :src="confirmedImage" />
                        </p>
                    </div>

                    <span class="error_msg">
                        <p>{{ message }}</p>
                    </span>

                    <div class="form-group" id="image2_hidden">
                        <input id="image2" type="file">
                        <p class="text-danger" id="file2_hidden">画像ファイルを選択してください</p>
                        <p><img id="file2-preview"></p>
                    </div>

                    <div class="form-group" id="image3_hidden">
                        <input id="image3" type="file">
                        <p class="text-danger" id="file3_hidden">画像ファイルを選択してください</p>
                        <p><img id="file3-preview"></p>
                    </div>

                    <div class="form-group">
                        <div v-if="0 > wordCount" v-on="changeTrue()"></div>
                        <div v-else-if="0 <= wordCount" v-on="changeFalse()"></div>
                        <label for="textAreaExplanation" class="required">説明</label>
                        <textarea rows="6" id="textAreaExplanation" class="form-control" v-model="explanation" v-on:keydown.enter="$event.stopPropagation()" placeholder="例） 風が弱くて釣りやすい釣り場です。" required></textarea>
                        <p>残り<span v-bind:class="{ 'text-danger':isActive }">{{ wordCount }}</span>文字</p>
                    </div>

                    <button class="spot-create-edit-button"><i class="fas fa-pencil-alt"></i>&thinsp;投稿</button>

                    <button type="button" class="back_button" onclick="history.back()">戻る</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import SpotForm from "../../components/spots/SpotForm";

    export default {
        components: { SpotForm },
        data(){
            return {
                message: "",
                mapLocation: {
                    lat: 35.6594666,
                    lng: 139.7005536,
                },
                mapAddress: "",
                explanation: "",
                latitude: 35.6594666,
                longitude: 139.7005536,
                wordLimit: 300,
                spot_image: "",
                confirmedImage: "",
            }
        },
        computed: {
            isLogin () {
                return this.$store.getters['auth/check']
            },
            username () {
                return this.$store.getters['auth/username']
            },
            wordCount(){
                return this.wordLimit - this.explanation.length
            },
        },
        methods: {
            updateLocation(location) {
                this.latitude = location.latLng.lat()
                this.longitude = location.latLng.lng()
                this.mapLocation.lat = location.latLng.lat()
                this.mapLocation.lng = location.latLng.lng()
            },
            searchAddress() {
                const geocoder = new google.maps.Geocoder()
                const that = this
                geocoder.geocode( { 'address': this.mapAddress}, function(results, status) {
                    if (status === 'OK') {
                        let lat = results[0].geometry.location.lat();
                        let lng = results[0].geometry.location.lng();

                        that.latitude = lat
                        that.longitude = lng
                        that.mapLocation.lat = lat
                        that.mapLocation.lng = lng
                    } else {
                        alert('該当する結果がありませんでした');
                    }
                });
            },
            // 文字数
            changeTrue: function() {
                this.isActive = true
            },
            changeFalse: function() {
                this.isActive = false
            },
            // 画像確認
            confirmImage(e) {
                this.message = "";
                this.spot_image = e.target.files[0];
                if (!this.spot_image.type.match("image.*")) {
                    this.message = "画像ファイルを選択して下さい";
                    this.confirmedImage = "";
                    return;
                }
                this.createImage(this.spot_image);
            },

            // 画像プレビュー
            createImage(spot_image) {
                let reader = new FileReader();
                reader.readAsDataURL(spot_image);
                reader.onload = e => {
                    this.confirmedImage = e.target.result;
                };
            },
        },
    }
</script>