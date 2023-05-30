<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('user資料', function () {
    // Prepare
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    // Act
    $response = $this->get('/api/me');

    // Assert
    $response->assertStatus(200);
});
