<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        //create default account
        User::factory()->create([
            'email'=>'default@account.com',
            'role'=>'admin'
        ]);
        User::factory(10)->create();
    }
}
