<?php

namespace App\Services\OpenWeather;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class OpenWeatherService
{
    public const BASE_URL = 'https://api.openweathermap.org/data/2.5/weather?';
    public const WEATHER_PARAMS = ['temp', 'pressure', 'humidity', 'temp_min', 'temp_max'];

    /**
     * @param $lat
     * @param $lon
     * @return string
     */
    private function buildUrl($lat, $lon)
    {
        return self::BASE_URL . 'lat=' . $lat . '&lon=' . $lon . '&units=metric&appid=' . env('APP_ID');
    }

    /**
     * @param $lat
     * @param $lon
     * @return array
     */
    public function getWeather($lat, $lon)
    {
        if (!$this->hasCache($lat, $lon)) {
            $this->requestToApi($lat, $lon);
        }
        return $this->getFromCache($lat, $lon);
    }

    /**
     * @param $lat
     * @param $lon
     */
    private function requestToApi($lat, $lon)
    {
        $apiURL = $this->buildUrl($lat, $lon);
        $response = Http::get($apiURL);
        $value = $response->json();
        $this->setToCache($lat, $lon, $value);
    }

    /**
     * @param $lat
     * @param $lon
     * @return bool
     */
    private function hasCache($lat, $lon)
    {
        $hashKay = $this->hashKay($lat, $lon);
        if (Redis::get($hashKay . '.temp')) {
            return true;
        }
        return false;
    }

    /**
     * @param $lat
     * @param $lon
     * @return string
     */
    private function hashKay($lat, $lon)
    {
        $hash = hash('md5', date('d-m-Y') . '*' . $lat . '*' . $lon);
        return $hash;
    }

    /**
     * @param $lat
     * @param $lon
     * @return array
     */
    private function getFromCache($lat, $lon)
    {
        $return = [];
        $hashKay = $this->hashKay($lat, $lon);
        foreach (self::WEATHER_PARAMS as $param) {

            $return[$param] = Redis::get($hashKay . '.' . $param);
        }
        return $return;
    }

    /**
     * @param $lat
     * @param $lon
     * @param $value
     */
    private function setToCache($lat, $lon, $value)
    {
        $hashKay = $this->hashKay($lat, $lon);
        foreach (self::WEATHER_PARAMS as $param) {
            Redis::set($hashKay . '.' . $param, $value['main'][$param]);
        }
    }
}
