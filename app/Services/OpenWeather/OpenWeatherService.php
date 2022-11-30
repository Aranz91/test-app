<?php

namespace App\Services\OpenWeather;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class OpenWeatherService
{
    public const APP_ID = '0cbee165e3d46745e76ed63c113135e9';
    public const BASE_URL = 'https://api.openweathermap.org/data/2.5/weather?';
    public const WEATHER_PARAMS = ['temp', 'pressure', 'humidity', 'temp_min', 'temp_max'];

    private function buildUrl($lat, $lon)
    {
        return $this::BASE_URL . 'lat=' . $lat . '&lon=' . $lon . '&units=metric&appid=' . $this::APP_ID;
    }

    public function getWeather($lat, $lon)
    {
        if (!$this->hasCache($lat, $lon)) {
            $this->requestToApi($lat, $lon);
        }
        return $this->getFromCache($lat, $lon);
    }

    private function requestToApi($lat, $lon)
    {
        $apiURL = $this->buildUrl($lat, $lon);
        $response = Http::get($apiURL);
        $value = $response->json();
        $this->setToCache($lat, $lon, $value);
    }

    private function hasCache($lat, $lon)
    {
        $hashKay = $this->hashKay($lat, $lon);
        if (Redis::get($hashKay . '.temp')) {
            return true;
        }
        return false;
    }

    private function hashKay($lat, $lon)
    {
        $hash = hash('md5', date('d-m-Y') . '*' . $lat . '*' . $lon);
        return $hash;
    }

    private function getFromCache($lat, $lon)
    {
        $return = [];
        $hashKay = $this->hashKay($lat, $lon);
        foreach ($this::WEATHER_PARAMS as $param) {

            $return[$param] = Redis::get($hashKay . '.' . $param);
        }
        return $return;
    }

    private function setToCache($lat, $lon, $value)
    {
        $hashKay = $this->hashKay($lat, $lon);
        foreach ($this::WEATHER_PARAMS as $param) {
            Redis::set($hashKay . '.' . $param, $value['main'][$param]);
        }
    }
}
