<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@tc.com',
            'password' => Hash::make('AdminPassword311%'),
            'role_id' => 2
        ]);

        User::create([
            'name' => 'qualityTeamMember',
            'email' => 'qtmember@tc.com',
            'password' => Hash::make('QualityTeamPassword211%'),
            'role_id' => 3
        ]);

        User::create([
            'name' => 'regularUser',
            'email' => 'regularuser@tc.com',
            'password' => Hash::make('RegularUserPassword111%'),
            'role_id' => 1
        ]);

        User::create([
            'name' => 'rareUser',
            'email' => 'rareuser@tc.com',
            'password' => Hash::make('RareUserPassword411%'),
            'role_id' => 1
        ]);

        User::create([
            'name' => 'stubbornUser',
            'email' => 'stubbornuser@tc.com',
            'password' => Hash::make('StubbornUserPassword511%'),
            'role_id' => 1
        ]);

        User::create([
            'name' => 'youngUser',
            'email' => 'younguser@tc.com',
            'password' => Hash::make('YoungUserPassword611%'),
            'role_id' => 1
        ]);
    }
}
