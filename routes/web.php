<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\PerangkinganController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\PenilaianController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/program/{id}', [\App\Http\Controllers\ProgramController::class, 'show']);

Route::get('/alternatif', [AlternatifController::class, 'index'])->name(name: 'alternatif.index');
Route::get('/alternatif/search', [AlternatifController::class, 'index'])->name('alternatif.search');
Route::get('/alternatif/create', [AlternatifController::class, 'create'])->name('alternatif.create');
Route::get('/alternatif/{id}/edit', [AlternatifController::class, 'edit'])->name('alternatif.edit');
Route::put('/alternatif/{id}/edit', [AlternatifController::class, 'update'])->name('alternatif.update');

Route::post('/alternatif', action: [AlternatifController::class, 'store'])->name('alternatif.store');
Route::put('/alternatif/{id}', [AlternatifController::class, 'update'])->name('alternatif.update');
Route::delete('/alternatif/{id}', [AlternatifController::class, 'destroy'])->name('alternatif.destroy');
Route::post('/alternatif/{id}/accept', [AlternatifController::class, 'accept'])->name('alternatif.accept');
Route::post('/alternatif/{id}/reject', [AlternatifController::class, 'reject'])->name('alternatif.reject');

Route::get('/kriteria', action: [KriteriaController::class, 'index'])->name('kriteria');
Route::post('/kriteria', action: [KriteriaController::class, 'store'])->name('kriteria.store');
Route::put('/kriteria/{id}', [KriteriaController::class, 'update'])->name('kriteria.update');
Route::delete('/kriteria/{id}', action: [KriteriaController::class, 'destroy'])->name('kriteria.destroy');

Route::get('/penilaian/alternatif-kriteria', [PenilaianController::class, 'index'])->name('alternatif-kriteria');
Route::get('/penilaian/normalisasi', [PenilaianController::class, 'normalisasi']);
Route::get('/penilaian/akhir', [PenilaianController::class, 'result']);
Route::get('/perangkingan', action: [PerangkinganController::class, 'index']);

Route::get('/alternatif/view-dokumen/{path}', [AlternatifController::class, 'viewDokumen'])
    ->name('alternatif.viewDokumen');

Route::get('/logout', function () {
    session()->forget('isLogin');
    return redirect('/');
});
