<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserCompany;

class UserCompanyManyToManyMigration extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        foreach($users as $user){
            UserCompany::create(['user_id' => $user->id, 'company_id' => $user->company_id]);
        }
    }
}
