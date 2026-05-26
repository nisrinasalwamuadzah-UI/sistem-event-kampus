<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Mahasiswa::create([
            'nim' => '12345',
            'nama' => 'Budi Santoso',
            'jurusan' => 'Teknik Informatika',
            'semester' => '4',
            'alamat' => 'Jl. Merdeka No. 1'
        ]);

        \App\Models\Mahasiswa::create([
            'nim' => '67890',
            'nama' => 'Siti Aminah',
            'jurusan' => 'Sistem Informasi',
            'semester' => '6',
            'alamat' => 'Jl. Sudirman No. 2'
        ]);
        
        \App\Models\Mahasiswa::create([
            'nim' => '11223',
            'nama' => 'Andi Wijaya',
            'jurusan' => 'Teknik Komputer',
            'semester' => '2',
            'alamat' => 'Jl. Mawar No. 3'
        ]);

        \App\Models\Mahasiswa::create([
            'nim' => '23190042',
            'nama' => 'Rosalia',
            'jurusan' => 'Teknik Informatika',
            'semester' => '6',
            'alamat' => 'Tegal'
        ]);

        \App\Models\Mahasiswa::create([
            'nim' => '23190012',
            'nama' => 'Nisrina',
            'jurusan' => 'Teknik Informatika',
            'semester' => '6',
            'alamat' => 'Tegal'
        ]);
    }
}
