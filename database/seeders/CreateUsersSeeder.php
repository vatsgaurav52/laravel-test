<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
               'name'=>'Admin User',
               'email'=>'admin@gmail.com',
               'password'=> bcrypt('123456'),
            ]
        ];
    
        foreach ($users as $key => $user) {
            $userd = User::create($user);
            $userd->assignRole('Admin');
        }

        
    
    }
}
