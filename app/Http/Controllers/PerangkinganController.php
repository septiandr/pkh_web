<?php

namespace App\Http\Controllers;

use App\Helpers\AlternatifHelper;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Rangking;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PerangkinganController extends Controller
{
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
    private function getBobotKriteria(): array
    {
        return Kriteria::all()
            ->pluck('bobot', 'nama_kriteria')
            ->toArray();
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


        return $results;
    }
    public function index()
    {
        // Ambil data rangking dengan alternatif yang eligible saja
        $rangking = Rangking::whereHas('alternatif', function ($query) {
            $query->where('eligible', 1);
        })
            ->with('alternatif')
            ->orderByDesc('total_nilai')
            ->get();

        if ($rangking->isNotEmpty()) {
            // Jika data rangking tersedia
            $results = $rangking->map(function ($item, $index) {
                return [
                    'no' => $index + 1,
                    'id_alternatif' => $item->id_alternatif,
                    'nama_lengkap' => $item->alternatif->nama_lengkap ?? '-',
                    'nilai' => $item->total_nilai,
                    'keterangan' => $item->keterangan,
                ];
            });
        } else {
            // Jika tidak ada data di tabel rangking, gunakan fallback dari result()
            $resultData = $this->result();

            $results = collect($resultData)
                ->filter(function ($item) {
                    $alt = Alternatif::find($item['id_alternatif']);
                    return $alt && $alt->eligible == 1;
                })
                ->sortByDesc('nilai')
                ->values()
                ->map(function ($item, $index) {
                    return [
                        'no' => $index + 1,
                        'id_alternatif' => $item['id_alternatif'],
                        'nama_lengkap' => $item['nama_lengkap'],
                        'nilai' => $item['nilai'],
                        'log' => $item['log'],
                    ];
                });
        }

        return view('program.perangkingan', compact('results'));
    }


}
