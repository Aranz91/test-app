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
     * @param float $lat
     * @param float $lon
     * @return array
     */
    public function getWeather(float $lat, float $lon)
    {
        if (!$this->hasCache($lat, $lon)) {
            $this->requestToApi($lat, $lon);
        }
        return $this->getFromCache($lat, $lon);
    }

    /**
     * @param float $lat
     * @param float $lon
     */
    private function requestToApi(float $lat, float $lon)
    {
        $apiURL = $this->buildUrl($lat, $lon);
        $response = Http::get($apiURL);
        $value = $response->json();
        $this->setToCache($lat, $lon, $value);
    }

    /**
     * @param float $lat
     * @param float $lon
     * @return bool
     */
    private function hasCache(float $lat, float $lon)
    {
        $hashKay = $this->hashKay($lat, $lon);
        if (Redis::get($hashKay . '.temp')) {
            return true;
        }
        return false;
    }

    /**
     * @param float $lat
     * @param float $lon
     * @return string
     */
    private function hashKay(float $lat, float $lon)
    {
        $hash = hash('md5', date('d-m-Y') . '*' . $lat . '*' . $lon);
        return $hash;
    }

    /**
     * @param float $lat
     * @param float $lon
     * @return array
     */
    private function getFromCache(float $lat, float $lon)
    {
        $return = [];
        $hashKay = $this->hashKay($lat, $lon);
        foreach (self::WEATHER_PARAMS as $param) {

            $return[$param] = Redis::get($hashKay . '.' . $param);
        }
        return $return;
    }

    /**
     * @param float $lat
     * @param float $lon
     * @param $value
     */
    private function setToCache(float $lat, float $lon, $value)
    {
        $hashKay = $this->hashKay($lat, $lon);
        foreach (self::WEATHER_PARAMS as $param) {
            Redis::set($hashKay . '.' . $param, $value['main'][$param]);
        }
    }
}
