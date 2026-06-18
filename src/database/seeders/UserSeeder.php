<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'username' => 'amdi',
            'email' => 'amduras@proton.me',
            'password_hash' => Hash::make('oscar'),
            'role' => 'admin',
            'status' => 'active',
        ]);
    }
}
