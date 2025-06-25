<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PenilaianTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Insert data alternatif (lengkap dengan nik)
DB::table('alternatif')->insert([
    [
        'id_alternatif' => 1,
        'nik' => '1234567890123451',
        'nama_lengkap' => 'Tukiran',
        'alamat' => 'Desa A',
        'dokumen' => null,
        'eligible' => 0,
        'pendapatan' => 400000,
        'luas_tanah' => 24,
    ],
    [
        'id_alternatif' => 2,
        'nik' => '1234567890123452',
        'nama_lengkap' => 'Boiran',
        'alamat' => 'Desa B',
        'dokumen' => null,
        'eligible' => 0,
        'pendapatan' => 500000,
        'luas_tanah' => 25,
    ],
    [
        'id_alternatif' => 9,
        'nik' => '1234567890123459',
        'nama_lengkap' => 'Jariyah',
        'alamat' => 'Desa I',
        'dokumen' => null,
        'eligible' => 0,
        'pendapatan' => 500000,
        'luas_tanah' => 25,
    ],
]);



        // Insert data kriteria
        DB::table('kriteria')->insert([
            ['id_kriteria' => 1, 'nama_kriteria' => 'Pendapatan', 'bobot' => 0.3],
            ['id_kriteria' => 2, 'nama_kriteria' => 'Luas tanah', 'bobot' => 0.2],
            ['id_kriteria' => 3, 'nama_kriteria' => 'Pekerjaan', 'bobot' => 0.5],
        ]);

        // Insert sub-kriteria untuk pekerjaan
DB::table('sub_kriteria')->insert([
    ['id_sub_kriteria' => 1, 'id_kriteria' => 3, 'nama_sub_kriteria' => 'Buruh', 'nilai_sub_kriteria' => 0.9],
    ['id_sub_kriteria' => 2, 'id_kriteria' => 3, 'nama_sub_kriteria' => 'Petani', 'nilai_sub_kriteria' => 0.7],
    ['id_sub_kriteria' => 3, 'id_kriteria' => 3, 'nama_sub_kriteria' => 'Pedagang', 'nilai_sub_kriteria' => 0.6],
]);


        // Penilaian (untuk pekerjaan, karena pendapatan & tanah dari tabel alternatif)
        DB::table('penilaian')->insert([
            ['id_alternatif' => 1, 'id_kriteria' => 3, 'id_sub_kriteria' => 1],
            ['id_alternatif' => 2, 'id_kriteria' => 3, 'id_sub_kriteria' => 2],
            ['id_alternatif' => 9, 'id_kriteria' => 3, 'id_sub_kriteria' => 3],
        ]);
    }

    public function test_fuzzy_saw_perhitungan_akurasi()
    {
        $response = $this->get('/penilaian/akhir'); // Pastikan route ini ada

        $response->assertStatus(200);

        // Ambil data view
        $viewData = $response->getOriginalContent()->getData()['results'];

        $nilaiMap = collect($viewData)->pluck('nilai', 'nama_lengkap');

        // Bandingkan nilai dengan yang diharapkan
        $this->assertEqualsWithDelta(6.36, $nilaiMap['Tukiran'], 0.1);
        $this->assertEqualsWithDelta(4.58, $nilaiMap['Boiran'], 0.1);
        $this->assertEqualsWithDelta(6.14, $nilaiMap['Jariyah'], 0.1);
    }
}
