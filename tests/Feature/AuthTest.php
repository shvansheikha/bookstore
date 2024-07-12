<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_it_can_register()
    {
        $payload = [
            'name' => 'new user',
            'email' => 'newUser@gmail.com',
            'password' => 'password',
        ];

        $this->postJson(route('api.auth.register'), $payload)
            ->assertJsonStructure([
                'user' => ['name', 'email', 'created_at'],
                'authorisation' => ['token']
            ]);

        $this->assertDatabaseHas('users', [
            'name' => $payload['name'],
            'email' => $payload['email'],
        ]);
    }

    public function test_it_can_login()
    {
        $password = 'D4e$7t@Pz!kL';

        $user = User::factory()->create([
            'email' => $this->faker->email,
            'password' => $password
        ]);

        $payload = [
            'email' => $user->email,
            'password' => $password
        ];

        $this->postJson(route('api.auth.login'), $payload)
            ->assertJsonStructure(['token', 'expires_in', 'token_type']);

        $this->assertEquals(auth()->user()->email, $user->email);
    }
}
