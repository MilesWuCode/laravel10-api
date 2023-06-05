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

it('寄Email驗證信/驗證Email代碼', function () {
    // Prepare
    $user = User::factory()
        ->unverified()
        ->create();

    $form = ['email' => $user->email];

    // Act
    $response = $this->post('/api/auth/send-verify-email', $form);

    // Assert
    $response->assertStatus(200);

    $verify = $user->verifies()
        ->where('expires', '>=', now())
        ->first();

    $form = ['email' => $user->email, 'code' => $verify->code];

    $response = $this->post('/api/auth/verify-email', $form);

    $response->assertStatus(200);
});

it('忘記密碼/變更密碼', function () {
    // Prepare
    $user = User::factory()->create();

    $form = ['email' => $user->email];

    // Act
    $response = $this->post('/api/auth/forgot-password', $form);

    // Assert
    $response->assertStatus(200);

    $verify = $user->verifies()
        ->where('expires', '>=', now())
        ->first();

    $form = [
        'email' => $user->email,
        'password' => 'password',
        'comfirm_password' => 'password',
        'code' => $verify->code,
    ];

    $response = $this->post('/api/auth/reset-password', $form);

    $response->assertStatus(200);
});
