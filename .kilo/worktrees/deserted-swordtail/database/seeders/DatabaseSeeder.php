<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Order matters:
     *  1. RolesAndPermissionsSeeder — creates roles/permissions (owner role must
     *     exist before AdminUserSeeder tries to assign it).
     *  2. AdminUserSeeder           — creates the default shop, then the admin
     *     users and attaches them to their roles.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}