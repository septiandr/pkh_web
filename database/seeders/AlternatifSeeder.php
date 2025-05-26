<?php

namespace Database\Seeders;

use App\Models\Penilaian;
use Illuminate\Database\Seeder;
use App\Models\Alternatif;
use Illuminate\Support\Facades\Schema;

class AlternatifSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Alternatif::truncate();
        Schema::enableForeignKeyConstraints();
        
        $alternatifs = [
            ['nik' => '1234567890123451', 'nama_lengkap' => 'Tukiran', 'alamat' => 'Desa A', 'pendapatan' => 0, 'luas_tanah' => 0],
            ['nik' => '1234567890123452', 'nama_lengkap' => 'Boiran', 'alamat' => 'Desa B', 'pendapatan' => 0, 'luas_tanah' => 0],
            ['nik' => '1234567890123453', 'nama_lengkap' => 'Tukimun', 'alamat' => 'Desa C', 'pendapatan' => 0, 'luas_tanah' => 0],
            ['nik' => '1234567890123454', 'nama_lengkap' => 'Binti Salimah', 'alamat' => 'Desa D', 'pendapatan' => 0, 'luas_tanah' => 0],
            ['nik' => '1234567890123455', 'nama_lengkap' => 'Marsum', 'alamat' => 'Desa E', 'pendapatan' => 0, 'luas_tanah' => 0],
            ['nik' => '1234567890123456', 'nama_lengkap' => 'Katimah', 'alamat' => 'Desa F', 'pendapatan' => 0, 'luas_tanah' => 0],
            ['nik' => '1234567890123457', 'nama_lengkap' => 'Masfungatin', 'alamat' => 'Desa G', 'pendapatan' => 0, 'luas_tanah' => 0],
            ['nik' => '1234567890123458', 'nama_lengkap' => 'Suharno', 'alamat' => 'Desa H', 'pendapatan' => 0, 'luas_tanah' => 0],
            ['nik' => '1234567890123459', 'nama_lengkap' => 'Jariyah', 'alamat' => 'Desa I', 'pendapatan' => 0, 'luas_tanah' => 0],
            ['nik' => '1234567890123460', 'nama_lengkap' => 'Sarmonah', 'alamat' => 'Desa J', 'pendapatan' => 0, 'luas_tanah' => 0],
        ];

        foreach ($alternatifs as $alt) {
            Alternatif::create($alt);
        }
    }
}
