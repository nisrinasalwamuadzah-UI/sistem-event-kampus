<?php

namespace Database\Seeders;

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
        // Bersihkan tabel users untuk menghindari duplikat
        \Illuminate\Support\Facades\DB::table('users')->delete();

        User::create([
            'name' => 'Administrator',
            'email' => 'admin@pbjt.web.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Pimpinan Kampus',
            'email' => 'pimpinan@pbjt.web.id',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
        ]);
    }
}
