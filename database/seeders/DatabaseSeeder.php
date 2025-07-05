<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TenantForge\Security\Database\Seeders\UserSeeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            UserSeeder::class,
        ]);

    }
}
