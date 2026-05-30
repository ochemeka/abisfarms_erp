<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Shop;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // ── Default Shop ─────────────────────────────
        // Must exist before owner is created so shop_id is never null.
        // firstOrCreate prevents duplicates on re-runs.
        $shop = Shop::firstOrCreate(
            ['name' => 'Butcherhut HQ'],
            [
                'address'   => 'Lagos, Nigeria',
                'phone'     => '08000000000',
                'currency'  => 'NGN',
                'is_active' => true,
            ]
        );

        // ── Owner / Business Admin ───────────────────
        // Owner is attached to the default shop so BelongsToShop
        // never returns null and category/product creation works.
        $owner = User::firstOrCreate(
            ['email' => 'admin@butcherhut.ng'],
            [
                'name'      => 'Butcherhut Admin',
                'password'  => Hash::make('Admin@1234'),
                'phone'     => '08000000001',
                'shop_id'   => $shop->id,
                'is_active' => true,
                'scope'     => 'all',
            ]
        );

        // If the user already existed without a shop_id, patch it now.
        if (is_null($owner->shop_id)) {
            $owner->update(['shop_id' => $shop->id]);
        }

        $owner->assignRole('owner');

        // ── Site Admin ───────────────────────────────
        // Site admin manages the whole platform; scope = all,
        // no specific shop required (shop_id stays null intentionally).
        $siteAdmin = User::firstOrCreate(
            ['email' => 'siteadmin@butcherhut.ng'],
            [
                'name'      => 'Site Administrator',
                'password'  => Hash::make('SiteAdmin@1234'),
                'phone'     => '08000000002',
                'shop_id'   => null,
                'is_active' => true,
                'scope'     => 'all',
            ]
        );
        $siteAdmin->assignRole('site-admin');

        $this->command->info('✅ Default shop and admin users created successfully.');
        $this->command->table(
            ['Resource', 'Detail'],
            [
                ['Shop Name', $shop->name],
                ['Shop ID',   $shop->id],
            ]
        );
        $this->command->table(
            ['Name', 'Email', 'Role', 'Shop ID', 'Password'],
            [
                [
                    'Butcherhut Admin',
                    'admin@butcherhut.ng',
                    'owner',
                    $shop->id,
                    'Admin@1234',
                ],
                [
                    'Site Administrator',
                    'siteadmin@butcherhut.ng',
                    'site-admin',
                    'null (global)',
                    'SiteAdmin@1234',
                ],
            ]
        );

        $this->command->warn(
            '⚠️  Change these passwords immediately after first login!'
        );
    }
}