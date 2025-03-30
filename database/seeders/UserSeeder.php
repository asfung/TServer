<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // DB::table('roles')->insert([
        //     [
        //         'name' => 'Admin',
        //     ],
        //     [
        //         'name' => 'User',
        //     ],
        //     [
        //         'name' => 'Vim',
        //     ],
        //     [
        //         'name' => 'Skibidi',
        //     ],
        //     [
        //         'name' => 'Wibu',
        //     ],
        //     [
        //         'name' => 'Fanum Tax',
        //     ],
        // ]);

        $adminRoleId = DB::table('roles')->where('name', 'Admin')->value('id');
        $userRoleId = DB::table('roles')->where('name', 'User')->value('id');

        DB::table('users')->insert([
            [
                'id' => Str::uuid(),
                'display_name' => 'Paung Bois',
                'username' => 'Paung',
                'email' => 'paung@gmail.com',
                'password' => Hash::make('paung123'),
                'role_id' => $adminRoleId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'display_name' => 'Steven Wang',
                'username' => 'StevenW',
                'email' => 'steven@gmail.com',
                'password' => Hash::make('steven123'),
                'role_id' => $adminRoleId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'display_name' => 'Carson Gross',
                'username' => 'CarsonMSU',
                'email' => 'carson@gmail.com',
                'password' => Hash::make('carson123'),
                'role_id' => $adminRoleId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'display_name' => 'Lauren Paulson',
                'username' => 'Lauren30',
                'email' => 'lauren@gmail.com',
                'password' => Hash::make('lauren123'),
                'role_id' => $userRoleId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'display_name' => 'Noah Atkinson',
                'username' => 'NoahGarut',
                'email' => 'noah@gmail.com',
                'password' => Hash::make('noah123'),
                'role_id' => $userRoleId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'display_name' => 'Robert James',
                'username' => 'ImJames',
                'email' => 'james@gmail.com',
                'password' => Hash::make('james123'),
                'role_id' => $userRoleId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }
}
