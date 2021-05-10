<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;

class WeatherController extends Controller
{
    public function __invoke(String $city)
    {
        // Guzzle使用
        $client = new \GuzzleHttp\Client();
        $weatherUrl = 'https://api.openweathermap.org/data/2.5/forecast?q=';
        $apiKey = ',jp&lang=ja&units=metric&APPID=' . config('services.weather.apikey');
        $weatherResponse = $client->request('GET', $weatherUrl . $city . $apiKey);
        $weatherResponseBody = $weatherResponse->getBody();
        $weatherResponseBody = json_decode($weatherResponseBody, true);

        return $weatherResponseBody;
    }
}
