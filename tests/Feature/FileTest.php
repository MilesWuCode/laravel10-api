<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;

test('上傳檔案到暫存區', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $response = $this->post('/api/file/temporary', [
        'file' => UploadedFile::fake()->image('test.jpg'),
    ]);

    $response->assertStatus(200);
});
