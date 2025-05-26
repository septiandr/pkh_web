<?php
namespace App\Helpers;

use App\Models\Alternatif;
use App\Models\Kriteria;

class AlternatifHelper
{
    public static function getAlternatifData()
    {
        $bobotPendapatan = Kriteria::where('nama_kriteria', 'Penghasilan')->value('bobot');
        $bobotLuasTanah = Kriteria::where('nama_kriteria', 'Luas Tanah')->value('bobot');

        return Alternatif::with(['penilaians.kriteria', 'penilaians.subKriteria'])
            ->get()
            ->map(function ($alt) use ($bobotPendapatan, $bobotLuasTanah) {
                $penilaianTetap = collect([
                    [
                        'kriteria' => [
                            'nama' => 'Pendapatan',
                            'bobot' => $bobotPendapatan,
                        ],
                        'sub_kriteria' => [
                            'nama' => $alt->pendapatan,
                            'nilai' => self::hitungBobot($alt->pendapatan, 600000, 1000000),
                        ],
                        'nilai' => $alt->pendapatan,
                    ],
                    [
                        'kriteria' => [
                            'nama' => 'Luas Tanah',
                            'bobot' => $bobotLuasTanah,
                        ],
                        'sub_kriteria' => [
                            'nama' => $alt->luas_tanah,
                            'nilai' => self::hitungBobot($alt->luas_tanah, 8, 50),
                        ],
                        'nilai' => $alt->luas_tanah,
                    ],
                ]);

                $penilaianDinamis = $alt->penilaians->map(function ($penilaian) {
                    return [
                        'kriteria' => [
                            'nama' => $penilaian->kriteria->nama_kriteria ?? null,
                            'bobot' => $penilaian->kriteria->bobot ?? null,
                        ],
                        'sub_kriteria' => [
                            'nama' => $penilaian->subKriteria->nama_sub_kriteria ?? null,
                            'nilai' => $penilaian->subKriteria->nilai_sub_kriteria ?? null,
                        ],
                        'nilai' => $penilaian->subKriteria->nilai_sub_kriteria ?? null,
                    ];
                });

                return [
                    'id_alternatif' => $alt->id_alternatif,
                    'nama_lengkap' => $alt->nama_lengkap,
                    'eligible' => $alt->eligible,
                    'penilaian' => $penilaianTetap->concat($penilaianDinamis),
                ];
            });
    }

    private static function hitungBobot($x, $min, $max)
    {
        if ($max == $min) return 0;
        return round(($max - $x) / ($max - $min), 2);
    }
}
