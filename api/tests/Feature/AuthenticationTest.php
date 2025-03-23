<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            "fullname" => fake()->userName(),
            "email" => fake()->email(),
            "password" => bcrypt("password")
        ]);

        $response = $this->postJson("/api/login", [
            "email" => $user->email,
            "password" => "password"
        ]);

        $response->assertStatus(200);
    }

    public function test_user_cannot_login_with_wrong_credentials()
    {
        User::factory()->create([
            "fullname" => fake()->userName(),
            "email" => fake()->email(),
            "password" => bcrypt("password")
        ]);

        $response = $this->postJson("/api/login", [
            "email" => "email@not.exist",
            "password" => "wrong-password"
        ]);

        $response->assertStatus(404);
    }

    public function test_user_can_register_with_valid_data()
    {
        $response = $this->postJson("/api/register", [
            "fullname" => fake()->userName(),
            "email" => fake()->email(),
            "password" => "password",
            "password_confirmation" => "password"
        ]);

        $response->assertStatus(201);
    
    }
    public function test_user_cannot_register_with_no_valid_data()
    {
        $response = $this->postJson("/api/register", [
            "fullname" => "",
            "email" => "",
            "password" => "",
            "password_confirmation" => ""
        ]);

        $response->assertStatus(422);
    }
}
