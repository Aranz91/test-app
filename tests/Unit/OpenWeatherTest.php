<?php

namespace Tests\Unit;

use App\Services\OpenWeather\OpenWeatherService;
use Tests\TestCase;

class OpenWeatherTest extends TestCase
{
    public function testGetWeather()
    {
        /** @var  $mock */
        $mock = $this->createMock(OpenWeatherService::class);

        $mock->expects($this->once())
            ->method('getWeather')
            ->willReturn([]);

        $this->assertIsArray($mock->getWeather(50, 30));
    }
}
