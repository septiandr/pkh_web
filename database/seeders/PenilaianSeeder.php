<?php

namespace Database\Seeders;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\SubKriteria;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class PenilaianSeeder extends Seeder
{
    public function run()
    {
        // Truncate the Penilaian table
        Schema::disableForeignKeyConstraints();
        Penilaian::truncate();
        Schema::enableForeignKeyConstraints();

        // Data Penilaian - urutan sesuai dengan kriteria (C1 sampai C12)
        $penilaianData = [
            'Tukiran' => ['400000', '24', 'Tanah', 'Bambu', 'Tanah liat', 'Sumur bor', 'Kayu bakar', 'Cemplung', 'Ada', 'Tidak ada', 'Tidak ada', 'Tidak ada'],
            'Boiran' => ['500000', '25', 'Tanah', 'Tembok', 'Tanah liat', 'Sumur bor', 'Gas 3 kg', 'Kloset jongkok', 'Tidak ada', 'Ada', 'Tidak ada', 'Tidak ada'],
            'Tukimun' => ['400000', '24', 'Semen', 'Tembok', 'Tanah liat', 'Sumur bor', 'Kayu bakar', 'Kloset jongkok', 'Ada', 'Tidak ada', 'Tidak ada', 'Tidak ada'],
            'Binti Salimah' => ['700000', '15', 'Tanah', 'Tembok', 'Tanah liat', 'Sumur bor', 'Gas 3 kg', 'Kloset jongkok', 'Ada', 'Tidak ada', 'Tidak ada', 'Tidak ada'],
            'Marsum' => ['600000', '15', 'Keramik', 'Tembok', 'Tanah liat', 'Sumur bor', 'Gas 3 kg', 'Kloset jongkok', 'Ada', 'Ada', 'Tidak ada', 'Tidak ada'],
            'Katimah' => ['500000', '37', 'Keramik', 'Tembok', 'Tanah liat', 'Sumur bor', 'Gas 3 kg', 'Kloset jongkok', 'Ada', 'Ada', 'Tidak ada', 'Tidak ada'],
            'Masfungatin' => ['600000', '36', 'Semen', 'Tembok', 'Tanah liat', 'Sumur bor', 'Gas 3 kg', 'Kloset jongkok', 'Ada', 'Ada', 'Tidak ada', 'Tidak ada'],
            'Suharno' => ['700000', '28', 'Tanah', 'Tembok', 'Tanah liat', 'Sumur bor', 'Gas 3 kg', 'Kloset jongkok', 'Tidak ada', 'Tidak ada', 'Tidak ada', 'Tidak ada'],
            'Jariyah' => ['500000', '25', 'Tanah', 'Tembok', 'Tanah liat', 'Sumur bor', 'Gas 3 kg', 'Cemplung', 'Ada', 'Ada', 'Tidak ada', 'Tidak ada'],
            'Sarmonah' => ['500000', '32', 'Keramik', 'Tembok', 'Tanah liat', 'Sumur bor', 'Gas 3 kg', 'Kloset jongkok', 'Ada', 'Ada', 'Tidak ada', 'Tidak ada'],
        ];

        // Ambil daftar kriteria sesuai urutan ID
        $kriteriaList = Kriteria::orderBy('id_kriteria')->get();

        foreach ($penilaianData as $namaAlternatif => $subKriteriaValues) {
            $alternatif = Alternatif::where('nama_lengkap', $namaAlternatif)->first();

            if (!$alternatif) {
                Log::warning("Alternatif tidak ditemukan: {$namaAlternatif}");
                continue;
            }

            $this->processSubKriteriaValues($alternatif, $subKriteriaValues, $kriteriaList);
        }
    }

    private function processSubKriteriaValues($alternatif, $subKriteriaValues, $kriteriaList)
    {
        foreach ($subKriteriaValues as $i => $namaSub) {
            $kriteria = $kriteriaList[$i] ?? null;
            if (!$kriteria) {
                Log::warning("Kriteria ke-{$i} tidak ditemukan untuk alternatif {$alternatif->nama_lengkap}");
                continue;
            }

            // Tangani C1 dan C2 langsung ke model Alternatif
            if ($i === 0) { // C1: pendapatan
                $alternatif->pendapatan = $namaSub;
                $alternatif->save();
                continue;
            }

            if ($i === 1) { // C2: luas tanah
                $alternatif->luas_tanah = $namaSub;
                $alternatif->save();
                continue;
            }

            // Sisanya lewat sub_kriteria
            $sub = $this->findMatchingSubKriteria($namaSub, $kriteria->id_kriteria);

            if (!$sub) {
                Log::warning("SubKriteria tidak ditemukan: '{$namaSub}' untuk {$alternatif->nama_lengkap} di kriteria ID {$kriteria->id_kriteria}");
                continue;
            }

            Penilaian::create([
                'id_alternatif'    => $alternatif->id_alternatif,
                'id_kriteria'      => $kriteria->id_kriteria,
                'id_sub_kriteria'  => $sub->id_sub_kriteria,
            ]);
        }
    }

    private function findMatchingSubKriteria($namaSub, $idKriteria)
    {
        // Cek kecocokan persis terlebih dahulu
        $exactMatch = SubKriteria::where('id_kriteria', $idKriteria)
            ->where('nama_sub_kriteria', $namaSub)
            ->first();

        if ($exactMatch) {
            return $exactMatch;
        }

        // Jika tidak ditemukan, cek kecocokan yang lebih longgar (menggunakan LIKE)
        return SubKriteria::where('id_kriteria', $idKriteria)
            ->where('nama_sub_kriteria', 'like', "%{$namaSub}%")
            ->first();
    }
}
