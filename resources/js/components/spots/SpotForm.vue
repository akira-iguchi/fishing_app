<template>
    <div>
        <h1 v-if="isEdit">釣りスポット更新</h1>
        <h1 v-else>釣りスポット作成</h1>

        <div class="d-flex">
            <input
                class="spot_search"
                id="address"
                type="text"
                v-model="mapAddress"
                placeholder="所在地を入力"
            >
            <button @click="searchAddress" class="spot_search_button"><i class="fas fa-search"></i></button>
        </div>

        <div class="google_map_form" ref="googleMap">
            <GoogleMapMarker
                :position="mapLocation.center"
                :google="google"
                :map="map"
                @dragendMarker="updateLocation"
                v-if="googleMapLoaded"
            />
        </div>
        <p>マーカーの移動も可能だよ！</p>

        <form @submit.prevent="spotData">
            <input id="spot_latitude" type="hidden" v-model="latitude">
            <input id="spot_longitude" type="hidden" v-model="longitude">

            <div class="form-group">
                <label for="spot_name" class="required">釣りスポット名</label>
                <input
                    id="spot_name"
                    type="text"
                    class="form-control"
                    placeholder="例） 〇〇釣り公園"
                    v-model="spotName"
                    required
                >
            </div>
            <div v-if="errors">
                <ul class="spot_errors" v-if="errors.spot_name">
                    <li class="text-danger" v-for="msg in errors.spot_name" :key="msg">{{ msg }}</li>
                </ul>
            </div>

            <div class="form-group">
                <label for="spot_address">所在地</label>
                <input
                    id="spot_address"
                    type="text"
                    class="form-control"
                    placeholder="例） 〇〇県〇〇市〇〇区〇〇町1-1-1"
                    v-model="address"
                >
            </div>
            <div v-if="errors">
                <ul class="spot_errors" v-if="errors.address">
                    <li class="text-danger" v-for="msg in errors.address" :key="msg">{{ msg }}</li>
                </ul>
            </div>

            <div class="form-group">
                <label for="tags">タグ（５つまで）</label>
                <SpotTagsInput
                    :initialTags="intialSpotTags"
                    :autocomplete-items="tagNames"
                    @tagsInput="getTag"
                />
            </div>
            <div v-if="errors">
                <ul class="spot_errors mt-3" v-if="errors.tags">
                    <li class="text-danger" v-for="msg in errors.tags" :key="msg">{{ msg }}</li>
                </ul>
            </div>

            <div class="form-group">
                <label>おすすめの釣り方</label><br>

                <div class="fishing_type_form">
                    <span
                        class="mr-2"
                        v-for="fishingType in fishingTypeNames"
                        :key="fishingType.id"
                    >
                        <label :for="`${ fishingType.id }`">
                            <input
                                type="checkbox"
                                :id="`${ fishingType.id }`"
                                :value="`${ fishingType.id }`"
                                v-model="fishingTypes"
                            > {{ fishingType.fishing_type_name }}
                        </label>
                    </span>
                </div>

                <div v-if="errors">
                    <ul class="spot_errors" v-if="errors.fishing_types">
                        <li class="text-danger" v-for="msg in errors.fishing_types" :key="msg">{{ msg }}</li>
                    </ul>
                </div>
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
            <div v-if="errors">
                <ul class="spot_errors" v-if="errors.spotImage1">
                    <li class="text-danger" v-for="msg in errors.spotImage1" :key="msg">{{ msg }}</li>
                </ul>
            </div>
            <div v-if="errors">
                <ul class="spot_errors" v-if="errors.spotImage2">
                    <li class="text-danger" v-for="msg in errors.spotImage2" :key="msg">{{ msg }}</li>
                </ul>
            </div>
            <div v-if="errors">
                <ul class="spot_errors" v-if="errors.spotImage3">
                    <li class="text-danger" v-for="msg in errors.spotImage3" :key="msg">{{ msg }}</li>
                </ul>
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
                <div v-if="300 < wordCount" v-on="changeTrue()"></div>
                <div v-else-if="300 >= wordCount" v-on="changeFalse()"></div>
                <label for="textAreaExplanation" class="required">説明</label>
                <textarea
                    rows="6"
                    id="textAreaExplanation"
                    class="form-control"
                    v-model="explanation"
                    v-on:keydown.enter="$event.stopPropagation()"
                    placeholder="例） 風が弱くて釣りやすい釣り場です。"
                    required>
                </textarea>
                <p class="text_limit">
                    <span
                        v-bind:class="{ 'text-danger':isActive }"
                    >{{ wordCount }}
                    </span>/300
                </p>
            </div>
            <div v-if="errors">
                <ul class="spot_errors" v-if="errors.explanation">
                    <li class="text-danger" v-for="msg in errors.explanation" :key="msg">{{ msg }}</li>
                </ul>
            </div>

            <button class="spot-create-edit-button" v-if="isEdit">
                <i class="fas fa-pencil-alt"></i>&thinsp;更新
            </button>

            <button class="spot-create-edit-button" v-else>
                <i class="fas fa-pencil-alt"></i>&thinsp;投稿
            </button>

            <button type="button" class="back_button" onclick="history.back()">戻る</button>
        </form>
    </div>
</template>

<script>
    import SpotTagsInput from '../tags/SpotTagsInput.vue'
    import GoogleMapsApiLoader from 'google-maps-api-loader'
    import GoogleMapMarker from './googleMaps/GoogleMapMarker.vue'

    export default {
        components: {
            SpotTagsInput,
            GoogleMapMarker,
        },
        props: {
            tagNames: {
                type: Array,
                required: true,
                default: () => [],
            },
            fishingTypeNames: {
                type: Array,
                required: true,
                default: () => [],
            },
            intialSpotValue: {
                type: Object,
                required: true,
                default: () => {},
            },
            intialspotFishingTypes: {
                type: Array,
                required: false,
            },
            intialSpotTags: {
                type: Array,
                required: false,
            },
            googleMapApiKey: {
                type: String,
                required: true,
            },
            errors: {
                type: Object,
                required: false,
            },
        },
        data () {
            return {
                spot: this.intialSpotValue,
                google: null,
                map: null,
                mapLocation: {
                    center: {
                        lat: 35.6594666,
                        lng: 139.7005536,
                    },
                    zoom: 15
                },
                mapAddress: "",
                latitude: 35.6594666,
                longitude: 139.7005536,
                image2: false,
                image3: false,
                spotImage1Message: "",
                spotImage2Message: "",
                spotImage3Message: "",
                preview1: null,
                preview2: null,
                preview3: null,
                spotName: "",
                address: "",
                explanation: "",
                spotImage1: "",
                spotImage2: "",
                spotImage3: "",
                fishingTypes: [],
                allFishingTypeNames: this.fishingTypeNames,
                allTagNames: this.tagNames,
                spotTags: this.intialSpotTags,
                tags: [],
                isEdit: false,
                googleMapLoaded: false
            }
        },
        computed: {
            wordCount () {
                return this.explanation.length
            },
        },
        async mounted () {
            if (Object.keys(this.spot).length > 0) {
                this.latitude = this.spot.latitude
                this.longitude = this.spot.longitude
                this.mapLocation.center.lat = this.spot.latitude
                this.mapLocation.center.lng = this.spot.longitude
                this.spotName = this.spot.spot_name
                this.address = this.spot.address
                this.fishingTypes = this.intialspotFishingTypes
                this.tags = JSON.stringify(this.spotTags)
                this.explanation = this.spot.explanation

                this.isEdit = true
            }

            this.google = await GoogleMapsApiLoader({
                apiKey: this.googleMapApiKey
            })
            this.initializeMap()
        },
        methods: {
            initializeMap () {
                this.googleMapLoaded = false;

                this.map = new this.google.maps.Map(this.$refs.googleMap, this.mapLocation)
                this.$nextTick(() => (this.googleMapLoaded = true));
            },
            updateLocation (location) {
                this.latitude = location.latLng.lat()
                this.longitude = location.latLng.lng()
                this.mapLocation.center.lat = location.latLng.lat()
                this.mapLocation.center.lng = location.latLng.lng()
                this.initializeMap()
            },
            searchAddress () {
                const geocoder = new google.maps.Geocoder()
                const self = this
                geocoder.geocode( { 'address': this.mapAddress}, function(results, status) {
                    if (status === 'OK') {
                        let lat = results[0].geometry.location.lat();
                        let lng = results[0].geometry.location.lng();

                        self.latitude = lat
                        self.longitude = lng
                        self.mapLocation.center.lat = lat
                        self.mapLocation.center.lng = lng
                        self.initializeMap()
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
            // 画像ファイルをプレビュー、エラーメッセージ処理（３つ）
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
            getTag (value) {
                this.tags = value || []
            },
            spotData () {
                this.$emit('spotData', [
                    this.latitude,
                    this.longitude,
                    this.spotName,
                    this.address,
                    this.tags,
                    this.fishingTypes,
                    this.explanation,
                    this.spotImage1,
                    this.spotImage2,
                    this.spotImage3,
                ])
            }
        },
    }
</script>