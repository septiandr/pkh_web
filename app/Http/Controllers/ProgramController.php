<?php

namespace App\Http\Controllers;


class ProgramController extends Controller
{
    protected $programs = [
        1 => ['name' => 'Program PKH', 'desc' => 'Bantuan untuk keluarga harapan', 'icon' => 'users'],
        2 => ['name' => 'BPNT', 'desc' => 'Bantuan Pangan Non-Tunai', 'icon' => 'shopping-bag'],
        3 => ['name' => 'BLT-DD', 'desc' => 'Bantuan Langsung Tunai Dana Desa', 'icon' => 'wallet'],
        4 => ['name' => 'BPJS', 'desc' => 'Badan Penyelenggara Jaminan Sosial', 'icon' => 'activity'],
        5 => ['name' => 'KIS', 'desc' => 'Kartu Indonesia Sehat', 'icon' => 'heart'],
        6 => ['name' => 'KIP', 'desc' => 'Kartu Indonesia Pintar', 'icon' => 'book-open'],
        7 => ['name' => 'RASTRA', 'desc' => 'Beras untuk Keluarga Sejahtera', 'icon' => 'package'],
        8 => ['name' => 'Program Lainnya', 'desc' => 'Informasi akan ditambahkan', 'icon' => 'more-horizontal'],
    ];

    public function show($id)
    {
        if (!isset($this->programs[$id])) {
            abort(404);
        }

        $program = $this->programs[$id];
        if($id == 1){
            return view('program.pkh_dashboard', compact('program'));
        }

        return view('program.show', compact('program'));
    }
}
