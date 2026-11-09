<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = collect(['kelola-nilai', 'kelola-tugas'])
            ->mapWithKeys(fn (string $name) => [
                $name => Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']),
            ]);

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions($permissions->values());

        $asistenDosen = Role::firstOrCreate(['name' => 'asisten-dosen', 'guard_name' => 'web']);
        $asistenDosen->syncPermissions($permissions['kelola-nilai']);

        Role::firstOrCreate(['name' => 'mahasiswa', 'guard_name' => 'web']);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
