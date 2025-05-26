@extends('layouts.app')

@section('content')
    <div class="flex flex-row min-h-3.5">
        <x-sidebar />
        <div class="flex-1 p-8 bg-gray-50">
            <h1 class="text-center my-4 text-3xl font-bold">Normalisasi Matrix</h1>
            <div style="overflow-y: auto; overflow-x: auto; max-height: 80vh; background-color: #f8f9fa;">
                @if (!empty($normalizationMatrix) && count($normalizationMatrix) > 0)
                    <table class="table-auto w-full border-collapse border border-gray-300">
                        <thead class="" style="background-color: #343a40; color: #fff;">
                            <tr>
                                <th class="px-4 py-2 border border-gray-300">Nama Lengkap</th>
                                @foreach ($normalizationMatrix[0]['normalized_penilaian'] as $index => $penilaian)
                                    <th class="px-4 py-2 border border-gray-300">
                                        {{ $penilaian['kriteria']['nama'] ?? 'Kriteria ' . ($index + 1) }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($normalizationMatrix as $row)
                                <tr class="hover:bg-gray-100">
                                    <td style="background-color: #343a40; color: #fff;" class="px-4 py-2 border border-gray-300">{{ $row['nama_lengkap'] }}</td>
                                    @foreach ($row['normalized_penilaian'] as $penilaian)
                                        <td class="px-4 py-2 border border-gray-300">
                                            {{ number_format($penilaian['normalized_nilai'], 2) }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-center text-gray-500">Data normalisasi tidak tersedia.</p>
                @endif
            </div>
        </div>
    </div>

@endsection