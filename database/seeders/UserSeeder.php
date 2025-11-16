<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
   public function run()
{
    $users = [
        [
            'first_name' => 'Admin',
            'last_name'  => 'admin',
            'email'      => 'admin@gmail.com',
            'password'   => bcrypt('admin123'),
        ],
        [
            'first_name' => 'Dollar',
            'last_name'  => 'Store',
            'email'      => 'dollarStore@gmail.com',
            'password'   => bcrypt('dollarStore123'),
        ],
    ];

    foreach ($users as $user) {
        User::updateOrCreate(
            ['email' => $user['email']],
            $user
        );
    }
}

}
