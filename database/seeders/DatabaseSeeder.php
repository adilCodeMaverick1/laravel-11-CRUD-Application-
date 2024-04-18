<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\blogs;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'tests@example.com',
            'id'=>2,
            'password' => bcrypt('test')
        ]);
        blogs::factory(100)->create();
    }
}
