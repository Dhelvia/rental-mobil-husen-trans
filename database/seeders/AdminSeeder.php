<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@husentrans.test'],
            [
                'nama' => 'Admin Husen Trans',
                'password' => Hash::make('admin12345'),
                'no_hp' => '08xxxxxxxxxx',
                'foto' => null,
            ]
        );
    }
}
