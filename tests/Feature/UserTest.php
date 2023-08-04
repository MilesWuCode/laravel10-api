<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use function Pest\Faker\fake;

it('User CRUD', function () {
    // Prepare
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    // Act
    $response = $this->get('/api/me');

    // Assert
    $response->assertStatus(200);

    $response = $this->put('/api/me', ['name' => fake()->name]);

    // Assert
    $response->assertStatus(200);
});
