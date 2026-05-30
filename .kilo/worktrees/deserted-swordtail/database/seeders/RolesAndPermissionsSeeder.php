<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cached permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]
            ->forgetCachedPermissions();

        // ── All Permissions ─────────────────────────
        $permissions = [

            // POS & Sales
            'pos.sell',
            'pos.refund.request',
            'pos.refund.approve.small',   // ≤ ₦5,000
            'pos.refund.approve.any',     // any amount
            'pos.void',
            'pos.discount.small',         // up to 10%
            'pos.discount.any',           // any amount
            'pos.credit',                 // record credit sale

            // Inventory
            'inventory.view',
            'inventory.create',
            'inventory.adjust',
            'inventory.transfer',
            'inventory.restock.request',

            // Till
            'till.open',
            'till.close',
            'till.reconcile',
            'till.view.own',
            'till.view.all',

            // Kitchen / KOT
            'kot.create',
            'kot.modify',
            'kot.cancel',
            'kot.monitor',
            'kot.dispatch',

            // Tables
            'tables.view',
            'tables.assign',
            'tables.manage',

            // HR
            'hr.staff.view',
            'hr.staff.create',
            'hr.attendance.view',
            'hr.attendance.manage',
            'hr.shifts.manage',
            'hr.leave.approve',
            'hr.payroll.run',
            'hr.payroll.view',
            'hr.disciplinary',

            // Staff management (ops)
            'staff.suspend',
            'staff.send.home',

            // Finance
            'finance.expense.create',
            'finance.expense.approve',
            'finance.pl.branch',
            'finance.pl.all',
            'finance.export',

            // CRM
            'crm.view',
            'crm.manage',
            'crm.debt.record',

            // Reports
            'reports.branch',
            'reports.all',

            // System
            'system.shops.manage',
            'system.users.manage',
            'system.roles.manage',
            'system.logs.all',
            'system.impersonate',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ── SITE ADMIN ───────────────────────────────
        $siteAdmin = Role::firstOrCreate(['name' => 'site-admin']);
        $siteAdmin->givePermissionTo(Permission::all());

        // ── OWNER ────────────────────────────────────
        $owner = Role::firstOrCreate(['name' => 'owner']);
        $owner->givePermissionTo([
            'pos.sell',
            'pos.refund.approve.any',
            'pos.void',
            'pos.discount.any',
            'pos.credit',
            'inventory.view',
            'inventory.create',
            'inventory.adjust',
            'inventory.transfer',
            'till.view.all',
            'till.reconcile',
            'hr.staff.view',
            'hr.staff.create',
            'hr.attendance.view',
            'hr.payroll.run',
            'hr.payroll.view',
            'hr.disciplinary',
            'staff.suspend',
            'finance.expense.approve',
            'finance.pl.branch',
            'finance.pl.all',
            'finance.export',
            'crm.view',
            'crm.manage',
            'crm.debt.record',
            'reports.branch',
            'reports.all',
            'tables.manage',
            'system.shops.manage',
            'system.users.manage',
            'system.roles.manage',
            'system.logs.all',
        ]);

        // ── MANAGER ──────────────────────────────────
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $manager->givePermissionTo([
            'pos.sell',
            'pos.refund.approve.any',
            'pos.void',
            'pos.discount.any',
            'pos.credit',
            'inventory.view',
            'inventory.create',
            'inventory.adjust',
            'inventory.transfer',
            'till.view.all',
            'till.reconcile',
            'hr.staff.view',
            'hr.attendance.view',
            'hr.attendance.manage',
            'hr.disciplinary',
            'staff.suspend',
            'staff.send.home',
            'finance.expense.create',
            'finance.expense.approve',
            'finance.pl.branch',
            'finance.export',
            'crm.view',
            'crm.manage',
            'crm.debt.record',
            'reports.branch',
            'kot.cancel',
            'kot.monitor',
            'tables.view',
            'tables.assign',
            'tables.manage',
        ]);

        // ── HR OFFICER ───────────────────────────────
        $hr = Role::firstOrCreate(['name' => 'hr']);
        $hr->givePermissionTo([
            'hr.staff.view',
            'hr.staff.create',
            'hr.attendance.view',
            'hr.attendance.manage',
            'hr.shifts.manage',
            'hr.leave.approve',
            'hr.payroll.run',
            'hr.payroll.view',
            'hr.disciplinary',
            'reports.branch',   // staff-level only
            'finance.export',   // payroll export only
        ]);

        // ── SUPERVISOR ───────────────────────────────
        $supervisor = Role::firstOrCreate(['name' => 'supervisor']);
        $supervisor->givePermissionTo([
            'pos.sell',
            'pos.refund.request',
            'pos.refund.approve.small',
            'pos.void',
            'pos.discount.small',
            'pos.credit',
            'inventory.view',
            'inventory.adjust',
            'inventory.restock.request',
            'till.open',
            'till.close',
            'till.reconcile',
            'till.view.own',
            'hr.staff.view',
            'hr.attendance.view',
            'hr.attendance.manage',
            'staff.send.home',
            'finance.expense.create',
            'crm.view',
            'crm.debt.record',
            'reports.branch',
            'kot.cancel',
            'kot.monitor',
            'kot.dispatch',
            'tables.view',
            'tables.assign',
            'tables.manage',
        ]);

        // ── CASHIER ──────────────────────────────────
        $cashier = Role::firstOrCreate(['name' => 'cashier']);
        $cashier->givePermissionTo([
            'pos.sell',
            'pos.refund.request',
            'pos.credit',
            'till.open',
            'till.close',
            'till.view.own',
            'crm.view',
            'crm.debt.record',
            'inventory.view',
        ]);

        // ── POS ATTENDANT ────────────────────────────
        $posAttendant = Role::firstOrCreate(['name' => 'pos-attendant']);
        $posAttendant->givePermissionTo([
            'pos.sell',
            'pos.refund.request',
            'kot.create',
            'kot.modify',
            'tables.view',
            'tables.assign',
            'inventory.restock.request',
        ]);

        $this->command->info('✅ All roles and permissions seeded successfully.');
        $this->command->table(
            ['Role', 'Permissions Count'],
            [
                ['site-admin',    Permission::count()],
                ['owner',         $owner->permissions->count()],
                ['manager',       $manager->permissions->count()],
                ['hr',            $hr->permissions->count()],
                ['supervisor',    $supervisor->permissions->count()],
                ['cashier',       $cashier->permissions->count()],
                ['pos-attendant', $posAttendant->permissions->count()],
            ]
        );
    }
}