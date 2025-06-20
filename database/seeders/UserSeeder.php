<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['name' => 'superadmin'], // Unik berdasarkan name
            [
                'email' => null,
                'usertype' => 'superadmin',
                'password' => bcrypt('12341234'),
            ]
        );

        User::updateOrCreate(
            ['name' => 'admin'],
            [
                'email' => null,
                'usertype' => 'admin',
                'password' => bcrypt('12341234'),
            ]
        );

        User::updateOrCreate(
            ['name' => 'petugas masuk'],
            [
                'email' => null,
                'usertype' => 'p_masuk',
                'password' => bcrypt('12341234'),
            ]
        );

        User::updateOrCreate(
            ['name' => 'petugas keluar'],
            [
                'email' => null,
                'usertype' => 'p_keluar',
                'password' => bcrypt('12341234'),
            ]
        );

        User::updateOrCreate(
            ['name' => 'petugas ruangan'],
            [
                'email' => null,
                'usertype' => 'p_ruangan',
                'password' => bcrypt('12341234'),
            ]
        );

        User::updateOrCreate(
            ['name' => 'admin 2'],
            [
                'email' => null,
                'usertype' => 'admin',
                'password' => bcrypt('12341234'),
            ]
        );
    }
}
