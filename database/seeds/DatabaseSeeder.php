<?php

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'User One',
            'email' => 'user@gmail.com',
            'password' => Hash::make('123456'),
            'available_time' => User::AVAILABLETIME()
        ]);

//        Admin::create([
//            'name' => 'Admin One',
//            'email' => 'admin@gmail.com',
//            'password' => Hash::make('123456'),
//        ]);

//         $this->call(UsersTableSeeder::class);
    }
}
