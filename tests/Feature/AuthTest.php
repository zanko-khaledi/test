<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function register_user_test()
    {
        $requestData = [
            'first_name' => 'Zanko',
            'last_name' => 'Khaledi',
            'email' => 'zanko@gmail.com',
            'password' => '12345678'
        ];

        $response = $this->postJson(route('api.user.register'), $requestData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users',[
            'email' => $requestData['email']
        ]);

        $this->assertArrayHasKey('token',$response->json());
    }

    #[Test]
    public function login_test()
    {
        $user = User::factory()->create([
            'email' => 'zanko@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

        $credentials = [
            'email' => 'zanko@gmail.com',
            'password' => '12345678',
        ];

        $response = $this->postJson(route('api.user.login'), $credentials);

        $response->assertStatus(201);

        $this->assertArrayHasKey('token', $response->json());
    }
}
