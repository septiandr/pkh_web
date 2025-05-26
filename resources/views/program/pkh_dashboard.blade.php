@extends('layouts.app')

@section('content')
<div class="flex">
    <x-sidebar />
    <div class="main-content flex-1 p-8 bg-gray-50">
        <div class="dashboard-header mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-extrabold text-gray-900">Dashboard Program Keluarga Harapan (PKH)</h1>
                <p class="text-gray-600 mt-2 leading-relaxed">
                    Selamat datang di dashboard Program Keluarga Harapan (PKH). Program ini bertujuan untuk meningkatkan kesejahteraan keluarga miskin melalui bantuan sosial bersyarat.
                </p>
            </div>
        </div>
        <div class="dashboard-widgets grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="widget bg-white p-8 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold text-gray-800">Total Penerima</h2>
                <p class="text-4xl font-bold text-gray-700 mt-4">1,234</p>
            </div>
            <div class="widget bg-white p-8 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold text-gray-800">Bantuan Tersalurkan</h2>
                <p class="text-4xl font-bold text-gray-700 mt-4">Rp 1,500,000,000</p>
            </div>
            <div class="widget bg-white p-8 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold text-gray-800">Wilayah Terjangkau</h2>
                <p class="text-4xl font-bold text-gray-700 mt-4">45 Kabupaten</p>
            </div>
        </div>
        <div class="dashboard-content mt-12">
            <h2 class="text-3xl font-bold text-gray-900">Tentang Program PKH</h2>
            <p class="text-gray-700 mt-6 leading-relaxed">
                Program Keluarga Harapan (PKH) adalah program bantuan sosial bersyarat yang diberikan kepada keluarga miskin untuk meningkatkan akses mereka terhadap pendidikan, kesehatan, dan kesejahteraan sosial. Program ini diharapkan dapat memutus rantai kemiskinan antar-generasi.
            </p>
        </div>
    </div>
</div>
@endsection
