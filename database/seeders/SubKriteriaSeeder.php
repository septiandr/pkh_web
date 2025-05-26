<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubKriteria;

class SubKriteriaSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing data
        SubKriteria::truncate();

        // Re-enable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            // C1 - Pendapatan
            ['nama_sub_kriteria' => '0 - 600.000', 'nilai_sub_kriteria' => 0, 'id_kriteria' => 1],
            ['nama_sub_kriteria' => '600.000 - 1.000.000', 'nilai_sub_kriteria' => 0.5, 'id_kriteria' => 1],
            ['nama_sub_kriteria' => ' lebih dari 1.000.000', 'nilai_sub_kriteria' => 1, 'id_kriteria' => 1],

            // C2 - Luas Tanah
            ['nama_sub_kriteria' => '0 m² - 8 m²', 'nilai_sub_kriteria' => 0, 'id_kriteria' => 2],
            ['nama_sub_kriteria' => '8 m² - 50 m²', 'nilai_sub_kriteria' => 0.5, 'id_kriteria' => 2],
            ['nama_sub_kriteria' => 'lebih dari 50 m²', 'nilai_sub_kriteria' => 1, 'id_kriteria' => 2],


            // C3 - Lantai
            ['nama_sub_kriteria' => 'Keramik', 'nilai_sub_kriteria' => 0, 'id_kriteria' => 3],
            ['nama_sub_kriteria' => 'Semen', 'nilai_sub_kriteria' => 0.25, 'id_kriteria' => 3],
            ['nama_sub_kriteria' => 'Kayu', 'nilai_sub_kriteria' => 0.5, 'id_kriteria' => 3],
            ['nama_sub_kriteria' => 'Bambu', 'nilai_sub_kriteria' => 0.75, 'id_kriteria' => 3],
            ['nama_sub_kriteria' => 'Tanah', 'nilai_sub_kriteria' => 1, 'id_kriteria' => 3],

            // C4 - Dinding
            ['nama_sub_kriteria' => 'Tembok', 'nilai_sub_kriteria' => 0, 'id_kriteria' => 4],
            ['nama_sub_kriteria' => 'Plasteran', 'nilai_sub_kriteria' => 0.25, 'id_kriteria' => 4],
            ['nama_sub_kriteria' => 'Kayu', 'nilai_sub_kriteria' => 0.5, 'id_kriteria' => 4],
            ['nama_sub_kriteria' => 'Anyaman bambu', 'nilai_sub_kriteria' => 0.75, 'id_kriteria' => 4],
            ['nama_sub_kriteria' => 'Bambu', 'nilai_sub_kriteria' => 1, 'id_kriteria' => 4],

            // C5 - Atap
            ['nama_sub_kriteria' => 'Genteng beton', 'nilai_sub_kriteria' => 0, 'id_kriteria' => 5],
            ['nama_sub_kriteria' => 'Genteng keramik', 'nilai_sub_kriteria' => 0.25, 'id_kriteria' => 5],
            ['nama_sub_kriteria' => 'Genteng tanah liat', 'nilai_sub_kriteria' => 0.5, 'id_kriteria' => 5],
            ['nama_sub_kriteria' => 'Genteng seng', 'nilai_sub_kriteria' => 0.75, 'id_kriteria' => 5],
            ['nama_sub_kriteria' => 'Genteng asbes', 'nilai_sub_kriteria' => 1, 'id_kriteria' => 5],

            // C6 - Air
            ['nama_sub_kriteria' => 'Air kemasan bermerk', 'nilai_sub_kriteria' => 0, 'id_kriteria' => 6],
            ['nama_sub_kriteria' => 'Leding', 'nilai_sub_kriteria' => 0.25, 'id_kriteria' => 6],
            ['nama_sub_kriteria' => 'Sumur bor/pompa', 'nilai_sub_kriteria' => 0.5, 'id_kriteria' => 6],
            ['nama_sub_kriteria' => 'Sumur', 'nilai_sub_kriteria' => 0.75, 'id_kriteria' => 6],
            ['nama_sub_kriteria' => 'Air sungai', 'nilai_sub_kriteria' => 1, 'id_kriteria' => 6],

            // C7 - Bahan bakar
            ['nama_sub_kriteria' => 'Listrik', 'nilai_sub_kriteria' => 0, 'id_kriteria' => 7],
            ['nama_sub_kriteria' => 'Gas 12 kg', 'nilai_sub_kriteria' => 0.25, 'id_kriteria' => 7],
            ['nama_sub_kriteria' => 'Gas 3 kg', 'nilai_sub_kriteria' => 0.5, 'id_kriteria' => 7],
            ['nama_sub_kriteria' => 'Arang', 'nilai_sub_kriteria' => 0.75, 'id_kriteria' => 7],
            ['nama_sub_kriteria' => 'Kayu bakar', 'nilai_sub_kriteria' => 1, 'id_kriteria' => 7],

            // C8 - Toilet
            ['nama_sub_kriteria' => 'Kloset duduk', 'nilai_sub_kriteria' => 0, 'id_kriteria' => 8],
            ['nama_sub_kriteria' => 'Kloset jongkok', 'nilai_sub_kriteria' => 0.25, 'id_kriteria' => 8],
            ['nama_sub_kriteria' => 'Plengsengan dengan tutup', 'nilai_sub_kriteria' => 0.5, 'id_kriteria' => 8],
            ['nama_sub_kriteria' => 'Plengsengan tanpa tutup', 'nilai_sub_kriteria' => 0.75, 'id_kriteria' => 8],
            ['nama_sub_kriteria' => 'Cemplung/cubluk', 'nilai_sub_kriteria' => 1, 'id_kriteria' => 8],

            // C9 - C12 Biner
            ['nama_sub_kriteria' => 'Tidak ada', 'nilai_sub_kriteria' => 0, 'id_kriteria' => 9],
            ['nama_sub_kriteria' => 'Ada', 'nilai_sub_kriteria' => 1, 'id_kriteria' => 9],

            ['nama_sub_kriteria' => 'Tidak ada', 'nilai_sub_kriteria' => 0, 'id_kriteria' => 10],
            ['nama_sub_kriteria' => 'Ada', 'nilai_sub_kriteria' => 1, 'id_kriteria' => 10],

            ['nama_sub_kriteria' => 'Tidak ada', 'nilai_sub_kriteria' => 0, 'id_kriteria' => 11],
            ['nama_sub_kriteria' => 'Ada', 'nilai_sub_kriteria' => 1, 'id_kriteria' => 11],
            
            ['nama_sub_kriteria' => 'Tidak ada', 'nilai_sub_kriteria' => 0, 'id_kriteria' => 12],
            ['nama_sub_kriteria' => 'Ada', 'nilai_sub_kriteria' => 1, 'id_kriteria' => 12],
        ];

        foreach ($data as $item) {
            SubKriteria::create($item);
        }
    }
}
