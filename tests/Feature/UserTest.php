<?php

use App\Models\User;

it('註冊', function () {
    // Prepare
    $user = User::factory()->make();

    $form = [
        'name' => $user->name,
        'email' => $user->email,
        'password' => 'password',
        'comfirm_password' => 'password',
    ];

    // Act
    $response = $this->post('/api/auth/register', $form);

    // Assert
    $response->assertStatus(200);
});

it('登入', function () {
    // Prepare
    $user = User::factory()->create();

    $form = ['email' => $user->email, 'password' => 'password'];

    // Act
    $response = $this->post('/api/auth/login', $form);

    // Assert
    $response->assertStatus(200);
});
