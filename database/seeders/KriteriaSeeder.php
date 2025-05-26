<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing data
        DB::table('kriteria')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Insert new data
        DB::table('kriteria')->insert([
            [
                'id_kriteria' => 1,
                'nama_kriteria' => 'Pendapatan',
                'bobot' => 0.5,
            ],
            [
                'id_kriteria' => 2,
                'nama_kriteria' => 'Luas tanah',
                'bobot' => 0.5,
            ],
            [
                'id_kriteria' => 3,
                'nama_kriteria' => 'Jenis lantai rumah',
                'bobot' => 0.75,
            ],
            [
                'id_kriteria' => 4,
                'nama_kriteria' => 'Jenis dinding rumah',
                'bobot' => 0.75,
            ],
            [
                'id_kriteria' => 5,
                'nama_kriteria' => 'Jenis atap rumah',
                'bobot' => 0.75,
            ],
            [
                'id_kriteria' => 6,
                'nama_kriteria' => 'Sumber air minum',
                'bobot' => 0.75,
            ],
            [
                'id_kriteria' => 7,
                'nama_kriteria' => 'Bahan bakar memasak',
                'bobot' => 0.75,
            ],
            [
                'id_kriteria' => 8,
                'nama_kriteria' => 'Tempat buang air',
                'bobot' => 0.75,
            ],
            [
                'id_kriteria' => 9,
                'nama_kriteria' => 'Lansia',
                'bobot' => 1,
            ],
            [
                'id_kriteria' => 10,
                'nama_kriteria' => 'Anak sekolah',
                'bobot' => 1,
            ],
            [
                'id_kriteria' => 11,
                'nama_kriteria' => 'Ibu Hamil',
                'bobot' => 1,
            ],
            [
                'id_kriteria' => 12,
                'nama_kriteria' => 'Disabilitas',
                'bobot' => 1,
            ],
        ]);
    }
}
