<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OpenWeather\OpenWeatherService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * @var OpenWeatherService
     */
    protected $openWeatherService;
    
    /**
     * HomeController constructor.
     *
     * @param OpenWeatherService $openWeatherService
     */
    public function __construct(OpenWeatherService $openWeatherService)
    {
        $this->openWeatherService = $openWeatherService;
    }
    /**
     * @param Request $request
     * @return array
     */
    public function home(Request $request)
    {
        return [
            $request->user(),
            $this->openWeatherService->getWeather($request->user()->lat, $request->user()->lon)
        ];
    }
}
