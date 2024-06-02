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
            ['name' => 'user.manage', 'display' => 'Manage Users'],
            ['name' => 'template.manage', 'display' => 'Manage Template'],
            ['name' => 'supplier.manage', 'display' => 'Manage Suppliers'],
            ['name' => 'client.manage', 'display' => 'Manage Clients'],
            ['name' => 'spaf.manage', 'display' => 'Manage Assesment Forms'],
            ['name' => 'spaf.approve', 'display' => 'Approve Assesment Forms'],
            ['name' => 'role.manage', 'display' => 'Roles & Privileges'],
            ['name' => 'template.approve', 'display' => 'Approve Template'],
            ['name' => 'dashboard.default', 'display' => 'Dashboard Default'],
            ['name' => 'dashboard.supplier', 'display' => 'Dashboard Supplier'],
            ['name' => 'dashboard.client', 'display' => 'Dashboard Client'],
            ['name' => 'settings.email.manage', 'display' => 'Manage Email Settings'],
            ['name' => 'settings.schedule.manage', 'display' => 'Manage Schedule General Settings'],
            ['name' => 'settings.country.manage', 'display' => 'Manage Countries'],
            ['name' => 'settings.scheduleStatus.manage', 'display' => 'Manage Schedule Statuses'],
            ['name' => 'settings.auditModel.manage', 'display' => 'Manage Schedule Audit Models'],
            ['name' => 'settings.standard.manage', 'display' => 'Manage Audit Standards'],
            ['name' => 'schedule.manage', 'display' => 'Manage Schedule Calendar'],
            ['name' => 'schedule.selectableAuditor', 'display' => 'Selectable as Auditor'],
            ['name' => 'audit.manage', 'display' => 'Manage Audit'],
            ['name' => 'audit.approve', 'display' => 'Approve Audit'],
            ['name' => 'settings.audit.manage', 'display' => 'Manage Audit Settings'],
            ['name' => 'report.manage', 'display' => 'Manage Reports'],
            ['name' => 'audit.auditform', 'display' => 'Auditor Acess to audit forms'],
            ['name' => 'audit.wif', 'display' => 'Auditor Acess to workder interviewer forms'],
            ['name' => 'audit.drf', 'display' => 'Auditor Access to document review forms'],
            ['name' => 'audit.mif', 'display' => 'Auditor Access to management interview forms'],
            ['name' => 'audit.all_records', 'display' => 'Audit access to all records'],

            ['name' => 'auditForm.saveandcontinue', 'display' => 'Audit Form Access to Save And Continue Button'],
            ['name' => 'auditForm.saveandsubmit', 'display' => 'Audit Form Access to Save And Submit Button'],
            ['name' => 'auditForm.saveandapprove', 'display' => 'Audit Form Access to Save And Approve Button'],
            ['name' => 'auditForm.view', 'display' => 'Audit Form access to view records'],
            ['name' => 'auditForm.edit', 'display' => 'Audit Form access to edit records'],
            ['name' => 'auditForm.delete', 'display' => 'Audit Form access to delete records'],
            ['name' => 'auditForm.review', 'display' => 'Audit Form access as reviewer'],
            ['name' => 'auditForm.modifyForms', 'display' => 'Audit Form access to modify forms'],
            
            ['name' => 'report.manage_assigned_resource', 'display' => 'Manager Reports only to Schedule entry assigned as resource'],
            ['name' => 'report.view', 'display' => 'Report List View Button'],
            ['name' => 'report.edit', 'display' => 'Report List Edit Button'],
            ['name' => 'report.editor', 'display' => 'Report List Editor Button'],
            ['name' => 'report.saveandcontinue', 'display' => 'Report Edit Save and Continue Button'],
            ['name' => 'report.saveandsubmit', 'display' => 'Report Edit Save and Submit Button'],
            ['name' => 'report.saveandapprove', 'display' => 'Report Edit Save and Approve Button'],
            ['name' => 'report.saveandclose', 'display' => 'Report Edit Save and Close Button'],
            ['name' => 'report.review', 'display' => 'Report Edit Review Function'],
            ['name' => 'report.group_1', 'display' => 'Report Review target Group 1'],
            ['name' => 'report.group_2', 'display' => 'Report Review target Group 2'],
            ['name' => 'report.group_3', 'display' => 'Report Review target Group 3'],
            ['name' => 'report.group_4', 'display' => 'Report Review target Group 4'],
            ['name' => 'report.group_5', 'display' => 'Report Review target Group 5'],
        ];
        foreach($permissions as $p){
            Permission::create($p);
        }

        $sa_permission = Permission::all();
        $sa->syncPermissions(['user.manage', 'spaf.manage','template.manage', 'supplier.manage', 'client.manage', 'spaf.approve', 'role.manage', 'template.approve', 'dashboard.default', 'settings.email.manage', 'settings.country.manage', 'settings.scheduleStatus.manage', 'settings.auditModel.manage', 'schedule.manage', 'settings.schedule.manage', 'schedule.selectableAuditor', 'audit.manage', 'audit.approve', 'settings.audit.manage', 'report.manage', 'settings.standard.manage', 'audit.auditform', 'audit.wif', 'audit.drf', 'audit.mif', 'audit.all_records', 'auditForm.saveandcontinue', 'auditForm.saveandsubmit', 'auditForm.saveandapprove', 'auditForm.view', 'auditForm.edit', 'auditForm.delete', 'auditForm.review', 'auditForm.modifyForms', 'report.manage_assigned_resource', 'report.view', 'report.edit', 'report.editor', 'report.saveandcontinue', 'report.saveandsubmit', 'report.saveandapprove', 'report.saveandclose', 'report.review', 'report.group_1']);
        $default->syncPermissions(['dashboard.default']);
        $supplier->syncPermissions(['dashboard.supplier']);
        $client->syncPermissions(['dashboard.client']);


        User::find(1)->assignRole('Super Admin');
    }
}
