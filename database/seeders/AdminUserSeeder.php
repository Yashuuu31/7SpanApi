<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        // Admin Seeder For Insert Admin In phpMyAdmin
        User::updateOrCreate(['email' => "admin@demo.com"],
        [
            'role' => config("custom.admin_role"),
            'first_name' => "Super",
            'last_name' => "Admin",
            'email' => "admin@demo.com",
            'password' => Hash::make("12345678"),
            'password_view' => "12345678",
            'is_active' => 1,
        ]);
    }
}
