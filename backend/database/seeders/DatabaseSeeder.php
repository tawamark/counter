<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $company = Company::firstOrCreate(
            ['name' => 'Counter Demo'],
            ['email' => 'contato@counter.test']
        );

        User::updateOrCreate([
            'email' => 'admin@counter.test',
        ], [
            'company_id' => $company->id,
            'name' => 'Administrador',
            'password' => 'password',
            'role' => 'admin',
        ]);
    }
}
