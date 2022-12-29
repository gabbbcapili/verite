<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = Company::create(['company_name' => 'Verite', 'type' => 'admin']);

        $user = User::create([
            'first_name' => 'Gabriel',
            'last_name' => 'Capili',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'company_id' => $company->id,
        ]);
    }
}
