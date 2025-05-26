@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center h-screen bg-gray-50">
    <div class="text-center">
        <h1 class="text-6xl font-bold text-gray-900">404</h1>
        <p class="text-xl text-gray-600 mt-4">Halaman tidak ditemukan.</p>
        <button onclick="history.back()" class="mt-6 px-6 py-3 bg-gray-800 text-white rounded-lg shadow hover:bg-gray-600">
            Kembali ke Halaman Sebelumnya
        </button>
    </div>
</div>
@endsection
