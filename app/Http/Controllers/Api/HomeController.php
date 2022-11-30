<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OpenWeather\OpenWeatherService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function getHome(Request $request)
    {
        $weatherService = new OpenWeatherService();
        $name = $request->user();

        return [$name, $weatherService->getWeather($request->user()->lat, $request->user()->lon)];
    }
}
