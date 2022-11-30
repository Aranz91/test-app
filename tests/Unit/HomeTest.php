<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class HomeTest extends TestCase
{
    public function testSuccessful()
    {
        $user = User::factory()->create(['google_id' => '777','lat'=>50,'lon'=>30]);
        $this->actingAs($user, 'api');
        
        $this->json('GET', 'api/home', ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                 [
                    'id',
                    'name',
                    'google_id',
                    'email',
                    'created_at',
                    'updated_at',
                    'lat',
                    'lon',
                ],
                [
                    'temp',
                    'pressure',
                    'humidity',
                    'temp_min',
                    'temp_max'
                ]
            ]);

        $user->delete();
    }

    public function testUnauthenticated()
    {
        $this->json('GET', 'api/home', ['Accept' => 'application/json'])
            ->assertStatus(401)
            ->assertJson([
                'message' => "Unauthenticated."
            ]);
    }
}
