<template>
    <div>
        <Prefectures
            @selectPrefecture="getCityName"
        />
        <div class="entire_weather">
            <div class="city_name"> {{ city }} の天気 </div>
            <div
                class="weather-report"
                v-for="(weather, index) in weatherList"
                :key="index"
            >
                <img class="weather-icon" :src="`http://openweathermap.org/img/w/${weather.weather[0].icon}.png`">
                <div class="weather-date"> {{ day(weather.dt_txt) }} </div>
                <div class="weather-main"> {{ weatherJavaneseConversion(weather.weather[0].main) }}   </div>
                <div class="weather-temp">  {{ Math.round(weather.main.temp) }} ℃ </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { OK } from '../../util'
    import Prefectures from '../weathers/Prefectures.vue'

    export default {
        components: {
            Prefectures
        },
        data () {
            return {
                weatherData: {},
                weatherList: [],
                day: "",
                weatherJavaneseConversion: "",
                city: "",
                icon: "",
                day: "",
                mainWeather: "",
                temp: "",
            }
        },
        watch: {
            $route: {
                async handler () {
                    await this.fetchWeather()
                },
                immediate: true
            },
        },
        methods: {
            async fetchWeather () {
                const response = await axios.get('/api/weathers')

                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.weatherData = response.data

                this.day = function (weathrDay) {
                    const Week = new Array("（日）","（月）","（火）","（水）","（木）","（金）","（土）"),
                        date = new Date (weathrDay),
                        month = date.getMonth() + 1,
                        day = month + "月" + date.getDate() + "日" + Week[date.getDay()] + date.getHours() + "：00"
                    date.setHours(date.getHours() + 9)
                    return day
                }

                this.weatherJavaneseConversion = function (name) {
                    switch (name) {
                        case "Clear":
                        return "晴れ"
                        case 'Clouds':
                        return "曇り"
                        case "Rain":
                        return "雨"
                        case "Snow":
                        return "雪"
                        default:
                        return name
                    }
                }

                this.weatherList = this.weatherData.list.filter(function ( value, index, array ) {

                    //「index番号」が10未満でで偶数の時だけ返す
                    if ( index < 10 && index % 2 !== 0 ) {
                        return value
                    }

                });
            },
            async getCityName (val) {
                const number = val.selectedIndex;
                this.city = val.options[number].text

                const response = await axios.get('/weathers', {
                    prefectures: val.value
                })

                console.log(response.data)
            }
        },
    }
</script>