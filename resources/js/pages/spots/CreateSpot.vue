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

                <form @submit.prevent="Createspot">
                    <input id="spot_latitude" type="number" :value="latitude">
                    <input id="spot_longitude" type="number" :value="longitude">

                    <div class="form-group">
                        <label for="spot_name" class="required">釣りスポット名</label>
                        <input id="spot_name" type="text" class="form-control" placeholder="例） 〇〇釣り公園" v-model="name" required>
                    </div>

                    <div class="form-group">
                        <label for="spot_address">所在地</label>
                        <input id="spot_address" type="text" class="form-control" placeholder="例） 〇〇県〇〇市〇〇区〇〇町1-1-1" v-model="address">
                    </div>

                    <div class="form-group">
                        <label>画像（３つまで）</label><br>

                        <input id="image1" type="file" @change="onFile1Change">
                        <p v-if="preview1">
                            <img class="file_preview" :src="preview1" alt="">
                        </p>
                        <span class="error_msg">
                            <p>{{ spotImage1Message }}</p>
                        </span>
                    </div>


                    <div class="form-group" v-show="image2">
                        <input id="image2" type="file" @change="onFile2Change">
                        <p v-if="preview2">
                            <img class="file_preview" :src="preview2" alt="">
                        </p>
                        <span class="error_msg">
                            <p>{{ spotImage2Message }}</p>
                        </span>
                    </div>

                    <div class="form-group" v-show="image3" @change="onFile3Change">
                        <input id="image3" type="file">
                        <p v-if="preview3">
                            <img class="file_preview" :src="preview3" alt="">
                        </p>
                        <span class="error_msg">
                            <p>{{ spotImage3Message }}</p>
                        </span>
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
                mapLocation: {
                    lat: 35.6594666,
                    lng: 139.7005536,
                },
                mapAddress: "",
                latitude: 35.6594666,
                longitude: 139.7005536,
                wordLimit: 300,
                image2: false,
                image3: false,
                spotImage1Message: "",
                spotImage2Message: "",
                spotImage3Message: "",
                preview1: null,
                preview2: null,
                preview3: null,
                name: "",
                address: "",
                explanation: "",
                spotImage1: null,
                spotImage2: null,
                spotImage3: null,
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
            onFile1Change (event) {
                if (event.target.files.length === 0) {
                    this.spotImage1Message = ""
                    this.preview1 = null
                    return false
                }
                if (! event.target.files[0].type.match('image.*')) {
                    this.spotImage1Message = "画像ファイルを選択して下さい"
                    this.preview1 = null
                    return false
                }
                const reader = new FileReader()
                reader.onload = e => {
                    this.preview1 = e.target.result
                }
                reader.readAsDataURL(event.target.files[0])
                this.spotImage1 = event.target.files[0]
                this.image2 = true
                this.spotImage1Message = ""
            },
            onFile2Change (event) {
                if (event.target.files.length === 0) {
                    this.spotImage2Message = ""
                    this.preview2 = null
                    return false
                }
                if (! event.target.files[0].type.match('image.*')) {
                    this.spotImage2Message = "画像ファイルを選択して下さい"
                    this.preview2 = null
                    return false
                }
                const reader = new FileReader()
                reader.onload = e => {
                    this.preview2 = e.target.result
                }
                reader.readAsDataURL(event.target.files[0])
                this.spotImage2 = event.target.files[0]
                this.image3 = true
                this.spotImage2Message = ""
            },
            onFile3Change (event) {
                if (event.target.files.length === 0) {
                    this.spotImage3Message = ""
                    this.preview3 = null
                    return false
                }
                if (! event.target.files[0].type.match('image.*')) {
                    this.spotImage3Message = "画像ファイルを選択して下さい"
                    this.preview3 = null
                    return false
                }
                const reader = new FileReader()
                reader.onload = e => {
                    this.preview3 = e.target.result
                }
                reader.readAsDataURL(event.target.files[0])
                this.spotImage3 = event.target.files[0]
                this.spotImage3Message = ""
            },
            async Createspot () {
                const formData = new FormData()
                formData.append('latitude', this.photo)
                formData.append('longitude', this.photo)
                formData.append('photo', this.photo)
                formData.append('photo', this.photo)
                formData.append('photo', this.photo)
                formData.append('photo', this.photo)
                formData.append('photo', this.photo)
                const response = await axios.post('/api/photos', formData)

                this.spotImage1Message = ""
                this.spotImage2Message = ""
                this.spotImage3Message = ""
                this.preview1 = null
                this.preview2 = null
                this.preview3 = null
                this.$emit('input', false)
                this.$router.push(`/spots/${response.data.id}`)
            }
        },
    }
</script>