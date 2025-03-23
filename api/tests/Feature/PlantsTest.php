<?php

namespace Tests\Feature;

use App\Models\User;
use App\Policies\PlantPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PlantsTest extends TestCase
{

    public function test_user_can_see_all_plants()
    {
        $response = $this->get("/api/plants");
        $response->assertStatus(200);
    }

    public function test_admin_can_create_new_plant()
    {
        $this->mock(PlantPolicy::class, function ($mock) {
            $mock->shouldReceive('create')->andReturn(true);
        });

        $admin = User::factory()->create([
            "fullname" => fake()->userName(),
            "email" => fake()->email(),
            "password" => bcrypt("password"),
            "role_id" => 1
        ]);

        $response = $this->postJson("/api/plants", [
            "name" => fake()->userName(),
            "description" => fake()->paragraph(),
            "image" => fake()->imageUrl(),
            "price" => fake()->numberBetween(10, 1000),
            "category_id" => fake()->numberBetween(1, 3),
        ]);

        $response->assertStatus(201);
    }
}
