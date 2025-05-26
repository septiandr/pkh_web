@extends('layouts.app')

@section('content')
    <div class="flex flex-row min-h-screen">
        <x-sidebar />
        <div class="flex-1 p-10 bg-gray-50">
            <h1 class="text-center text-2xl font-bold my-6">Hasil Perangkingan</h1>
            @if ($results && count($results) > 0)
                <div class="overflow-x-auto">
                    <table class="table-auto w-full border-collapse border border-gray-300">
                        <thead class="h-16" style="background-color: #343a40; color: #fff;">
                            <tr>
                                <th class="border border-gray-300 px-4 py-2 text-left">No</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Nama</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Nilai</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $result)
                                <tr class="hover:bg-gray-100">
                                    <td class="border border-gray-300 px-4 py-2">{{ $result['no'] }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $result['nama_lengkap'] }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ number_format($result['nilai'], 2) }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $result['keterangan']}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center text-gray-500">Tidak ada data untuk ditampilkan.</p>
            @endif
        </div>
    </div>
@endsection