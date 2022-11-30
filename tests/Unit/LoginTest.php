<?php

namespace Tests\Unit;

use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function testGoogleLogin()
    {
        Socialite::shouldReceive('driver')
            ->with('google')
            ->once();
        $this->call('GET', '/redirect')->isRedirection();
    }
    
    public function testHandleProviderCallback()
    {
        $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser
            ->shouldReceive('getId')
            ->andReturn('114888439190723120745')
            ->shouldReceive('getName')
            ->andReturn('Assd Asdadsa')
            ->shouldReceive('getEmail')
            ->andReturn('asdadasdad@gmail.com')
            ->shouldReceive('getAvatar')
            ->andReturn('https://en.gravatar.com/userimage');

        Socialite::shouldReceive('driver->stateless->user')->andReturn($abstractUser);
        
        $this->json('GET', '/callback')
            ->assertStatus(200)
            ->assertJsonStructure(['token']);
    }
}
