<template>
    <div>
        <select id="js-prefectures">
            <!-- @include('weathers.prefecture') -->
        </select>

        <div class="entire_weather">
            <div id="city-name"></div>
            <div class="weather-report">
                <!-- <img class="weather-icon" :src="`http://openweathermap.org/img/w/${icon}.png`"> -->
                <div class="weather-date">  {{ day }}  </div>
                <div class="weather-main"> {{ main }}  </div>
                <!-- <div class="weather-temp">  {{ Math.round(data.list[i].main.temp) }} ℃ </div> -->
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        components: {

        },
        data () {
            return {
                day: "",
                main: "",
            }
        },
        mounted: function () {
            axios.get('https://api.openweathermap.org/data/2.5/weather?q=Osaka,jp&units=metric&appid=', {
                headers: {
                            'Access-Control-Allow-Origin': 'http://localhost',
                            'Access-Control-Allow-Headers': 'Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With',
                            'Access-Control-Allow-Methods': 'GET',
                            'Access-Control-Allow-Credentials': true,
                            'HTTP/1.1 200 OK': true,
                        }
            })
            .then(function(response){
                console.log(response.data)
            }.bind(this))
            .catch(function(error){
                console.log(error)
            })
            axios.defaults.withCredentials = true;
        },
    }
</script>