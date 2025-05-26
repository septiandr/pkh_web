@extends('layouts.app')

@section('content')

    <div class="flex flex-row min-h-3.5">
        <x-sidebar />
        <div class="flex-1 flex-1 p-8 bg-gray-50">
            <h1 class="text-center my-4 text-4xl font-bold">Data Kriteria dan Sub Kriteria</h1>

            <!-- Kriteria and Sub Kriteria Table -->
            <div style="overflow-y: auto; overflow-x: auto; max-height: 80vh; background-color: #f8f9fa;">
                @if (count($alternatif) > 0)
                    <table class="table-auto w-full border-collapse border border-gray-300">
                        <thead class="bg-[#343a40]" style="background-color: #343a40; color: #fff;">
                            <tr>
                                <th class="px-4 py-2 border border-gray-300">Nama Lengkap</th>
                                @foreach ($alternatif[0]['penilaian'] as $penilaian)
                                    <th class="px-4 py-2 border border-gray-300">
                                        {{ $penilaian['kriteria']['nama'] }} ({{ $penilaian['kriteria']['bobot'] }})
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($alternatif as $alt)
                                <tr class="hover:bg-gray-100">
                                    <td style="background-color: #343a40; color: #fff;"class="px-4 py-2 border border-gray-300">{{ $alt['nama_lengkap'] }}</td>
                                    @foreach ($alt['penilaian'] as $penilaian)
                                        <td class="px-4 py-2 border border-gray-300">
                                            {{ $penilaian['sub_kriteria']['nama'] }} ({{ $penilaian['sub_kriteria']['nilai'] }})
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-center text-gray-500">Tidak ada data untuk ditampilkan.</p>
                @endif
            </div>
        </div>
    </div>

@endsection
