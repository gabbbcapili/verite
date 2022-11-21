<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default = Role::create([
            'name' => 'Default'
        ]);

        $sa = Role::create([
            'name' => 'Super Admin'
        ]);

        $supplier = Role::create([
            'name' => 'Supplier',
        ]);

        $client = Role::create([
            'name' => 'Client',
        ]);

        $permissions = [
            ['name' => 'user.manage'],
            ['name' => 'template.manage'],
            ['name' => 'supplier.manage'],
            ['name' => 'client.manage'],
            ['name' => 'spaf.manage'],
            ['name' => 'spaf.approve'],
            ['name' => 'role.manage'],
            ['name' => 'template.approve'],
            ['name' => 'dashboard.default'],
            ['name' => 'dashboard.supplier'],
            ['name' => 'dashboard.client'],
        ];
        foreach($permissions as $p){
            Permission::create($p);
        }

        $sa_permission = Permission::all();
        $sa->syncPermissions(['user.manage', 'spaf.manage','template.manage', 'supplier.manage', 'client.manage', 'spaf.approve', 'role.manage', 'template.approve', 'dashboard.default']);
        $default->syncPermissions(['dashboard.default']);
        $supplier->syncPermissions(['dashboard.supplier']);
        $client->syncPermissions(['dashboard.client']);
        User::find(1)->assignRole('Super Admin');
    }
}
