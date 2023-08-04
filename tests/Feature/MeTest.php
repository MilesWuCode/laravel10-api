<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use function Pest\Faker\fake;

it('Me:Show/Update/Avatar', function () {
    // 用戶資料
    $user = User::factory()->create();

    // 登入
    Sanctum::actingAs($user);

    // 取得資料
    $response = $this->get('/api/me');

    // 檢查
    $response->assertStatus(200);

    // 更新資料
    $response = $this->put('/api/me', ['name' => fake()->name]);

    // 檢查
    $response->assertStatus(200);

    // 上傳檔案到暫存區
    $response = $this->post('/api/file/temporary', [
        'file' => UploadedFile::fake()->image('test.jpg'),
    ]);

    // 檢查
    $response->assertStatus(200);

    // 回傳資料
    $content = json_decode($response->getContent());

    // 更新資料
    $response = $this->put('/api/me/avatar', ['file' => $content->file]);

    // 檢查
    $response->assertStatus(200);
});
