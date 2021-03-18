// 天気予報
(() => {
    const element = document.getElementById("js-prefectures");
    let prefecture = element.value;
    let number = element.selectedIndex;
    let prefectureTitle = element.options[number].text;

    const weather = function() {
        $(function() {
            let apikey = process.env.MIX_OPEN_WEATHER_API;
            let city = prefecture;
            let url = '//api.openweathermap.org/data/2.5/forecast?q=' + city + ',jp&lang=ja&units=metric&APPID=' + apikey;
            $.ajax({
                url: url,
                dataType: "json",
                type: 'GET',
            })
            .done(function(data) {
                let insertHTML = "";
                let cityName = '<h2>' + prefectureTitle + 'の天気' + '</h2>';
                $('#city-name').html(cityName);
                for (let i = 0; i <= 8; i = i + 2) {
                insertHTML += buildHTML(data, i);
                }
                $('#weather').html(insertHTML);
            })
            .fail(function(data) {
                console.log(this.data);
            });
        });

        function weatherJavaneseConversion(name) {
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

        function buildHTML(data, i) {
            let Week = new Array("（日）","（月）","（火）","（水）","（木）","（金）","（土）");
            let date = new Date (data.list[i].dt_txt);
            date.setHours(date.getHours() + 9);
            let month = date.getMonth()+1;
            let day = month + "月" + date.getDate() + "日" + Week[date.getDay()] + date.getHours() + "：00";
            let icon = data.list[i].weather[0].icon;
            let main = weatherJavaneseConversion(data.list[i].weather[0].main);
            let html =
            '<div class="weather-report">' +
                '<img class="weather-icon" src="http://openweathermap.org/img/w/' + icon + '.png">' +
                '<div class="weather-date">' + day + '</div>' +
                '<div class="weather-main">'+ main + '</div>' +
                '<div class="weather-temp">' + Math.round(data.list[i].main.temp) + '℃</div>' +
            '</div>'
            return html
        }
    }

    weather();

    element.addEventListener('change', function(){
        prefecture = element.value;
        number = element.selectedIndex;
        prefectureTitle = element.options[number].text;
        weather();
    });
})();