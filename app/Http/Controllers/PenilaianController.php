<?php

namespace App\Http\Controllers;

use App\Helpers\AlternatifHelper;
use App\Models\Penilaian;
use Illuminate\Http\Request;
use App\Models\Kriteria;
use App\Models\Alternatif;

class PenilaianController extends Controller
{

    function hitungBobot($x, $min, $max)
    {
        if ($max == $min)
            return 0;
        return round(($max - $x) / ($max - $min), 2);
    }

    public function index()
    {
        $alternatif = AlternatifHelper::getAlternatifData();

        return view('program.penilaian.index', compact('alternatif'));
    }


    public function normalisasi()
    {
        $alternatif = AlternatifHelper::getAlternatifData();


        $matrix = $alternatif->map(function ($alt) {
            return [
                'id_alternatif' => $alt['id_alternatif'],
                'nama_lengkap' => $alt['nama_lengkap'],
                'penilaian' => $alt['penilaian'],
            ];
        });
        // Calculate normalization matrix using SAW method
        $columns = [];
        foreach ($matrix as $alt) {
            foreach ($alt['penilaian'] as $key => $penilaian) {
                $columns[$key][] = $penilaian['sub_kriteria']['nilai'];
            }
        }

        $normalizationMatrix = $matrix->map(function ($alt) use ($columns) {
            $normalizedPenilaian = $alt['penilaian']->map(function ($penilaian, $key) use ($columns) {
                $max = max($columns[$key]);
                $min = min($columns[$key]);
                $originalValue = $penilaian['sub_kriteria']['nilai']; // Nilai asli sebelum normalisasi
                $normalizedValue = $max == 0 ? 0 : round($originalValue / $max, 4);
                $weightedValue = round($normalizedValue * $penilaian['kriteria']['bobot'], 4);

                return [
                    'kriteria' => $penilaian['kriteria'],
                    'sub_kriteria' => $penilaian['sub_kriteria'],
                    'original_value' => $originalValue,
                    'normalized_nilai' => $normalizedValue,
                    'weighted_nilai' => $weightedValue,
                ];
            });

            $totalWeightedValue = $normalizedPenilaian->sum('weighted_nilai');

            return [
                'id_alternatif' => $alt['id_alternatif'],
                'nama_lengkap' => $alt['nama_lengkap'],
                'normalized_penilaian' => $normalizedPenilaian,
                'total_weighted_value' => $totalWeightedValue,
            ];
        });
        return view('program.penilaian.normalisasi', compact('normalizationMatrix'));
    }
    public function result()
    {
        $alternatif = AlternatifHelper::getAlternatifData();

        // 1. Kumpulkan semua nilai per id_kriteria untuk normalisasi
        $nilaiPerKriteria = [];

        foreach ($alternatif as $alt) {
            foreach ($alt['penilaian'] as $penilaian) {
                $id_kriteria = $penilaian['kriteria']['id_kriteria'] ?? null;
                $nilai = (float) ($penilaian['sub_kriteria']['nilai'] ?? 0);

                if (!is_null($id_kriteria)) {
                    $nilaiPerKriteria[$id_kriteria][] = $nilai;
                }
            }
        }

        // 2. Hitung nilai maksimum per kriteria
        $maxPerKriteria = [];
        foreach ($nilaiPerKriteria as $id_kriteria => $nilaiList) {
            $maxPerKriteria[$id_kriteria] = max($nilaiList);
        }

        // 3. Hitung nilai total untuk setiap alternatif
        $results = collect($alternatif)->map(function ($alt) use ($maxPerKriteria) {
            $penilaianList = collect($alt['penilaian']);

            // Deteksi duplikat id_kriteria
            $idCounts = $penilaianList->pluck('kriteria.id_kriteria')->countBy();
            $duplikatIds = $idCounts->filter(fn($count) => $count > 1)->keys();

            if ($duplikatIds->isNotEmpty()) {
                logger("Duplikat ID Kriteria ditemukan di {$alt['nama_lengkap']}: " . $duplikatIds->implode(', '));
            }

            // Ambil hanya satu penilaian per id_kriteria
            $filtered = $penilaianList
                ->keyBy(fn($p) => $p['kriteria']['id_kriteria'] ?? uniqid())
                ->values();

            $totalWeightedValue = $filtered->map(function ($penilaian) use ($alt, $maxPerKriteria) {
                $id_kriteria = $penilaian['kriteria']['id_kriteria'] ?? null;
                $value = (float) ($penilaian['sub_kriteria']['nilai'] ?? 0);
                $max = $maxPerKriteria[$id_kriteria] ?? 1;

                // Normalisasi
                $nilai_normalisasi = $max > 0 ? $value / $max : 0;

                // Bobot fallback jika null
                $bobot = $penilaian['kriteria']['bobot'] ?? match ($id_kriteria) {
                    1, 2 => 0.5,
                    3, 4, 5, 6, 7, 8 => 0.75,
                    9, 10, 11, 12 => 1.0,
                    default => 1.0,
                };

                $hasil = round($nilai_normalisasi * $bobot, 4);

                logger("{$alt['nama_lengkap']} - Kriteria {$id_kriteria} : Normalisasi {$nilai_normalisasi} x Bobot {$bobot} = {$hasil}");
                return $hasil;
            })->sum();

            return [
                'id_alternatif' => $alt['id_alternatif'],
                'nama_lengkap' => $alt['nama_lengkap'],
                'total_weighted_value' => round($totalWeightedValue, 4),
            ];
        });

        // 4. Format hasil untuk ditampilkan
        $results = $results->map(function ($item, $index) {
            return [
                'no' => $index + 1,
                'id_alternatif' => $item['id_alternatif'],
                'nama_lengkap' => $item['nama_lengkap'],
                'nilai' => $item['total_weighted_value'],
            ];
        });

        return view('program.penilaian.result', compact('results'));
    }


}
