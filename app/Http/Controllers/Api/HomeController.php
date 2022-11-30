<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OpenWeather\OpenWeatherService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * @param Request $request
     * @return array
     */
    public function getHome(Request $request)
    {
        $weatherService = new OpenWeatherService();

        return [
            $request->user(),
            $weatherService->getWeather($request->user()->lat, $request->user()->lon)
        ];
    }
}
