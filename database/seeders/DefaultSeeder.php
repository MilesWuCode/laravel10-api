<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();
        \App\Models\User::factory(10)->unverified()->create();
        \App\Models\Banner::factory(20)->create();
        \App\Models\Post::factory(20)->create();
    }
}
