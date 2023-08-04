<?php

use App\Models\User;

// 3A:Arrange-Act-Assert

it('註冊+登入', function () {
    // Prepare
    $user = User::factory()
        ->unverified()
        ->make();

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

    // Prepare
    $form = ['email' => $user->email, 'password' => 'password'];

    // Act
    $response = $this->post('/api/auth/login', $form);

    // Assert
    $response->assertStatus(200);
});

it('寄Email驗證信+驗證Email代碼', function () {
    // Prepare
    $user = User::factory()
        ->unverified()
        ->create();

    // 登入
    // Sanctum::actingAs($user);
    $this->actingAs($user);

    // 另一種登入方式
    // \Laravel\Sanctum\Sanctum::actingAs($user);

    $form = ['email' => $user->email];

    // Act
    $response = $this->post('/api/auth/send-verify-email', $form);

    // Assert
    $response->assertStatus(200);

    // 回傳的json資料
    $content = json_decode($response->getContent());

    $form = ['email' => $user->email, 'code' => $content->code];

    $response = $this->post('/api/auth/verify-email', $form);

    $response->assertStatus(200);
});

it('忘記密碼+變更密碼', function () {
    // Prepare
    $user = User::factory()->create();

    $form = ['email' => $user->email];

    // Act
    $response = $this->post('/api/auth/forgot-password', $form);

    // Assert
    $response->assertStatus(200);

    $content = json_decode($response->getContent());

    $form = [
        'email' => $user->email,
        'password' => 'password',
        'comfirm_password' => 'password',
        'code' => $content->code,
    ];

    $response = $this->post('/api/auth/reset-password', $form);

    $response->assertStatus(200);
});
