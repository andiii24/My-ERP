<?php

namespace Database\Seeders;

use App\Actions\CreateCompanyAction;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateNewCompany extends Seeder
{
    public function run(Faker $faker, CreateCompanyAction $action)
    {
        DB::transaction(function () use ($faker, $action) {
            $action->execute([
                'company_name' => $faker->company,
                'name' => $faker->name,
                'email' => User::count() ? $faker->unique()->safeEmail : 'admin@onrica.com',
                'password' => 'password',
            ]);
        });
    }
}
