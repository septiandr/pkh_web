<?php

namespace App\Http\Controllers;

use App\Helpers\AlternatifHelper;
use App\Models\Penilaian;
use App\Models\Rangking;
use Illuminate\Http\Request;
use App\Models\Kriteria;
use App\Models\Alternatif;
use Illuminate\Support\Collection;


class PenilaianController extends Controller
{

    function hitungBobot($x, $min, $max)
    {
        if ($max == $min)
            return 0;
        return round(($max - $x) / ($max - $min), 2);
    }
    private function mapAlternatif(Collection $items): array
    {
        return $items->map(function ($item) {
            $penilaian = collect($item['penilaian'])->mapWithKeys(function ($nilaiItem) {
                $namaKriteria = $nilaiItem['kriteria']['nama'];
                $nilai = (float) $nilaiItem['sub_kriteria']['nilai'];
                return [$namaKriteria => $nilai];
            });

            return [
                'id_alternatif' => $item['id_alternatif'],
                'nama_lengkap' => $item['nama_lengkap'],
                'eligible' => $item['eligible'],
                'penilaian' => $penilaian->toArray(),
            ];
        })->toArray();
    }
    private function hitungNormalisasi(array $data): array
    {
        // Ambil semua nama kriteria dari data pertama
        $kriteriaList = array_keys($data[0]['penilaian']);

        // Hitung nilai maksimum dari tiap kriteria
        $maxPerKriteria = [];
        foreach ($kriteriaList as $kriteria) {
            $maxPerKriteria[$kriteria] = collect($data)->max(function ($alt) use ($kriteria) {
                return $alt['penilaian'][$kriteria];
            });
        }

        // Proses normalisasi
        return collect($data)->map(function ($alt) use ($maxPerKriteria) {
            $normalisasi = [];

            foreach ($alt['penilaian'] as $kriteria => $nilai) {
                $max = $maxPerKriteria[$kriteria] ?: 1; // Hindari bagi 0
                $normalisasi[$kriteria] = round($nilai / $max, 4);
            }

            return [
                'id_alternatif' => $alt['id_alternatif'],
                'nama_lengkap' => $alt['nama_lengkap'],
                'eligible' => $alt['eligible'],
                'penilaian' => $normalisasi,
            ];
        })->toArray();
    }

    private function hitungSkorPreferensi(array $normalisasi, array $bobot): array
    {
        // Pastikan key bobot lowercase agar konsisten
        $bobot = array_change_key_case($bobot, CASE_LOWER);

        $hasil = [];

        foreach ($normalisasi as $alt) {
            $total = 0;
            $log = "{$alt['nama_lengkap']} (ID: {$alt['id_alternatif']}) => V{$alt['id_alternatif']} = ";
            $logRumus = [];

            foreach ($alt['penilaian'] as $kriteriaNama => $nilaiNormalisasi) {
                $nama = strtolower($kriteriaNama); // lowercase match
                $bobotKriteria = $bobot[$nama] ?? 0;
                $hasilPerkalian = $bobotKriteria * $nilaiNormalisasi;
                $logRumus[] = "({$bobotKriteria} × " . number_format($nilaiNormalisasi, 2) . ")";
                $total += $hasilPerkalian;
            }

            // $log .= implode(' + ', $logRumus) . ' = ' . number_format($total, 2);
            // echo $log . "\n";

            $hasil[] = [
                'id_alternatif' => $alt['id_alternatif'],
                'nama_lengkap' => $alt['nama_lengkap'],
                'skor' => round($total, 4),
                'log' => $log,
            ];
        }

        return $hasil;
    }

    private function simpanKeRangking(array $skorPreferensi): void
    {
        foreach ($skorPreferensi as $item) {
            Rangking::updateOrCreate(
                ['id_alternatif' => $item['id_alternatif']],
                [
                    'total_nilai' => $item['skor'],
                    'keterangan' => 'Hasil perangkingan sistem', // bisa Anda ubah sesuai kebutuhan
                ]
            );
        }
    }
    
    private function getBobotKriteria(): array
    {
        return Kriteria::all()
            ->pluck('bobot', 'nama_kriteria')
            ->toArray();
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

        $mapped = $this->mapAlternatif($alternatif);
        $normalisasi = $this->hitungNormalisasi($mapped);
        $bobot = $this->getBobotKriteria();
        $skorPreferensi = $this->hitungSkorPreferensi($normalisasi, $bobot);

        // Ubah hasil skor menjadi data siap tampil
        $results = collect($skorPreferensi)->map(function ($item, $index) {
            return [
                'no' => $index + 1,
                'id_alternatif' => $item['id_alternatif'],
                'nama_lengkap' => $item['nama_lengkap'],
                'nilai' => $item['skor'], // ganti dari total_weighted_value ke skor
                'log' => $item['log'],    // jika ingin ditampilkan penjelasannya
            ];
        });

        $this->simpanKeRangking($skorPreferensi);


        return view('program.penilaian.result', compact('results'));
    }



}
