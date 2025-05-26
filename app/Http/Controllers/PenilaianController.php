<?php

namespace App\Http\Controllers;

use App\Helpers\AlternatifHelper;
use App\Models\Penilaian;
use Illuminate\Http\Request;
use App\Models\Kriteria;
use App\Models\Alternatif;

class PenilaianController extends Controller
{

    function hitungBobot($x, $min, $max) {
        if ($max == $min) return 0;
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

        $results = $matrix->map(function ($alt) use ($columns) {
            $totalWeightedValue = $alt['penilaian']->map(function ($penilaian, $key) use ($columns) {
                $max = max($columns[$key]);
                $originalValue = $penilaian['sub_kriteria']['nilai'];
                $normalizedValue = $max == 0 ? 0 : round($originalValue / $max, 4);
                $weightedValue = round($normalizedValue * $penilaian['kriteria']['bobot'], 4);
                return $weightedValue;
            })->sum();

            return [
                'id_alternatif' => $alt['id_alternatif'],
                'nama_lengkap' => $alt['nama_lengkap'],
                'total_weighted_value' => $totalWeightedValue,
            ];
        });

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
