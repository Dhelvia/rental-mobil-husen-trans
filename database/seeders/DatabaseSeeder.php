<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@husentrans.test'],
            [
                'nama' => 'Admin Husen Trans',
                'password' => Hash::make('password123'),
                'no_hp' => '08xxxxxxxxxx',
            ]
        );
    }
}
