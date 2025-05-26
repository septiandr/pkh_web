<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class HomeController extends Controller
{
    public function index()
    {
        $programs = [
            ['id' => 1, 'name' => 'Program PKH', 'desc' => 'Bantuan untuk keluarga harapan', 'icon' => 'users'],
            ['id' => 2, 'name' => 'BPNT', 'desc' => 'Bantuan Pangan Non-Tunai', 'icon' => 'shopping-bag'],
            ['id' => 3, 'name' => 'BLT-DD', 'desc' => 'Bantuan Langsung Tunai Dana Desa', 'icon' => 'wallet'],
            ['id' => 4, 'name' => 'BPJS', 'desc' => 'Badan Penyelenggara Jaminan Sosial', 'icon' => 'activity'],
            ['id' => 5, 'name' => 'KIS', 'desc' => 'Kartu Indonesia Sehat', 'icon' => 'heart'],
            ['id' => 6, 'name' => 'KIP', 'desc' => 'Kartu Indonesia Pintar', 'icon' => 'book-open'],
            ['id' => 7, 'name' => 'RASTRA', 'desc' => 'Beras untuk Keluarga Sejahtera', 'icon' => 'package'],
            ['id' => 8, 'name' => 'Program Lainnya', 'desc' => 'Informasi akan ditambahkan', 'icon' => 'more-horizontal'],
        ];

        $villageName = Config::get('globals.village_name');
        $currentYear = Config::get('globals.currentYear');

        return view('home', compact('programs', 'villageName', 'currentYear'));
    }
}
