<?php

use Illuminate\Database\Seeder;
use App\Models\User;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's clear the users table first
        User::truncate();
       //Hashing password for security reasons
        $password = Hash::make('takeaway');

        User::create([
            'name' => 'Administrator',
            'email' => 'admin@takeaway.com',
            'password' => $password,
        ]);
        
    }
}
