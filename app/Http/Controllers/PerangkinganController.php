<?php

namespace App\Http\Controllers;

use App\Helpers\AlternatifHelper;
use Illuminate\Http\Request;

class PerangkinganController extends Controller
{
    public function index()
    {
        $alternatif = AlternatifHelper::getAlternatifData();

        // Filter alternatives where eligible is true
        $alternatif = $alternatif->filter(function ($alt) {
            return $alt['eligible'] === 1;
        });

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

        $results = $results->sortByDesc('total_weighted_value')->values()->map(function ($item, $index) {
            $keterangan = $item['total_weighted_value'] >= 4.55 ? 'Layak' : 'Tidak Layak';
            return [
                'no' => $index + 1,
                'id_alternatif' => $item['id_alternatif'],
                'nama_lengkap' => $item['nama_lengkap'],
                'nilai' => $item['total_weighted_value'],
                'keterangan' => $keterangan,
            ];
        });

        return view('program.perangkingan', compact('results'));
    }
}
