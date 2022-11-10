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
        Role::create([
            'name' => 'Default'
        ]);

        $sa = Role::create([
            'name' => 'Super Admin'
        ]);

        Role::create([
            'name' => 'Supplier',
        ]);

        $permissions = [
            ['name' => 'user.manage'],
            ['name' => 'template.manage'],
            ['name' => 'supplier.manage'],
            ['name' => 'supplier.approve'],
            ['name' => 'role.manage'],
            ['name' => 'template.approve']
        ];
        foreach($permissions as $p){
            Permission::create($p);
        }

        $sa_permission = Permission::all();
        $sa->syncPermissions($sa_permission);
        User::find(1)->assignRole('Super Admin');
    }
}
