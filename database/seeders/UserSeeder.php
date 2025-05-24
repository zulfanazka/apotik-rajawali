<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "username" => "Admin", // atau "admin" sesuai preferensi
            "password" => Hash::make("admin123"),
            "role" => "admin" // Tambahkan role admin
        ]);

        // Opsional: Tambahkan pengguna staff untuk pengujian
        User::create([
            "username" => "Staff", // atau "staff"
            "password" => Hash::make("staff123"), // Ganti dengan password yang aman
            "role" => "staff"
        ]);

        User::create([
            "username" => "Guest", // atau "staff"
            "password" => Hash::make("guest123"), // Ganti dengan password yang aman
            "role" => ""
        ]);
    }
}
