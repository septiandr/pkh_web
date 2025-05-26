@extends('layouts.app')

@section('content')

    {{-- Load Lucide Icons --}}
    @push('scripts')
        <script src="https://unpkg.com/lucide@latest"></script>
        <script>
            lucide.createIcons();
        </script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let isProcessing = false; // Flag global untuk mencegah aksi bersamaan

        function handleRequest(button, method, actionName = null) {
            if (isProcessing) return; // Cegah klik ganda
            isProcessing = true;

            const url = button.getAttribute('href');

            const headers = {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            };

            const body = actionName ? JSON.stringify({ action: actionName }) : null;

            fetch(url, {
                method,
                headers,
                body
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Aksi gagal dijalankan.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memproses permintaan.');
                })
                .finally(() => {
                    isProcessing = false; // Reset flag setelah selesai
                });
        }

        // Accept
        document.querySelectorAll('.accept-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                handleRequest(this, 'POST', 'accept');
            });
        });

        // Reject
        document.querySelectorAll('.reject-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                handleRequest(this, 'POST');
            });
        });

        // Delete
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                if (confirm('Yakin ingin menghapus data ini?')) {
                    handleRequest(this, 'DELETE', 'delete');
                }
            });
        });
    });
</script>

    @endpush
    <div class="flex flex-row min-h-3.5">
        <x-sidebar />
        <div class="flex-1 p-8 bg-gray-50">
            <h1 class="text-center my-4 text-4xl font-bold">Data Alternatif</h1>

            <!-- Search and Add Button -->
            <div class="flex justify-between items-center mb-4">
                <form action="{{ route('alternatif.search') }}" method="GET" class="flex items-center">
                    <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}"
                        class="form-control w-64 mr-2 p-2 border-2 border-[#343a40] rounded-lg" />
                    <button type="submit" class="btn btn-primary flex p-2 rounded-lg" style="background-color: #343a40; color: #fff;">
                        <i data-lucide="search"></i>
                    </button>
                </form>
                @if($isLogin)
                <a href="{{ route('alternatif.create') }}" class="flex flex-row gap-2 p-2 rounded-lg" style="background-color: #343a40; color: #fff;">
                    <i data-lucide="plus-circle"></i> Tambah Data
                </a>
                @endif
            </div>

            <!-- Scrollable table container -->
            <div style="overflow-y: auto; max-height: 80vh; background-color: #f8f9fa;">
                <table class="table table-bordered table-hover align-middle w-full"
                    style="border-top: 2px solid #dee2e6; border-bottom: 2px solid #dee2e6;">
                    <thead class="table-dark text-center h-16"
                        style="position: sticky; top: 0; z-index: 1; background-color: #343a40; color: #fff;">
                        <tr>
                            <th>No</th>
                            <th>NIK</th>
                            <th>Nama Lengkap</th>
                            <th>Alamat</th>
                            <th>Dokumen</th>
                            <th>Status</th>
                            <th>Kriteria</th>
                            <th>Sub Kriteria</th>
                            @if($isLogin)
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alternatif as $index => $alt)
                            @php
                                $penilaianCount = $alt['penilaian']->count();
                            @endphp

                            @if($penilaianCount > 0)
                                @foreach($alt['penilaian'] as $i => $penilaian)
                                    <tr class="{{ $loop->even ? 'table-light' : '' }}">
                                        @if($i === 0)
                                            <td rowspan="{{ $penilaianCount }}" style="border: 1px solid #dee2e6; padding: 8px;">{{ $index + 1 }}</td>
                                            <td rowspan="{{ $penilaianCount }}" style="border: 1px solid #dee2e6; padding: 8px;">{{ $alt['nik'] }}</td>
                                            <td rowspan="{{ $penilaianCount }}" style="border: 1px solid #dee2e6; padding: 8px;">{{ $alt['nama_lengkap'] }}</td>
                                            <td rowspan="{{ $penilaianCount }}" style="border: 1px solid #dee2e6; padding: 8px;">{{ $alt['alamat'] }}</td>
                                            <td rowspan="{{ $penilaianCount }}" style="border: 1px solid #dee2e6; padding: 8px;">
                                                @if($alt['dokumen'])
                                                <a href="{{ asset('storage/' . $alt['dokumen']) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-info">
                                                    <i data-lucide="file-text"></i> Lihat
                                                </a>

                                                @else
                                                    <span class="badge bg-secondary">Tidak Ada</span>
                                                @endif
                                            </td>
                                            <td rowspan="{{ $penilaianCount }}" style="border: 1px solid #dee2e6; padding: 8px; ">
                                                @if($alt['eligible'])
                                                    <span class="bg-green-600 text-white p-2 rounded-lg">Layak</span>
                                                @else
                                                    <span class="bg-red-600 text-white p-2 rounded-lg">Tidak Layak</span>
                                                @endif
                                            </td>
                                        @endif

                                        <td style="border: 1px solid #dee2e6; padding: 8px;">{{ $penilaian['kriteria'] }}</td>
                                        <td style="border: 1px solid #dee2e6; padding: 8px;">
                                            @if($penilaian['kriteria'] === 'Pendapatan')
                                             Rp. {{ number_format($penilaian['sub_kriteria']['nama_sub_kriteria'] ?? 0,0, ',', '.') ?? '' }} ({{ $penilaian['sub_kriteria']['nilai_sub_kriteria'] ?? 0}})
                                            @elseif($penilaian['kriteria'] === 'Luas Tanah')
                                            {{ $penilaian['sub_kriteria']['nama_sub_kriteria'] . ' m²'  ?? '' }}  ({{ ($penilaian['sub_kriteria']['nilai_sub_kriteria'] ?? 0)}})
                                            @else
                                                {{ $penilaian['sub_kriteria']['nama_sub_kriteria'] ?? '' }} ({{ $penilaian['sub_kriteria']['nilai_sub_kriteria'] ?? '' }})
                                            @endif
                                        </td>
                                        @if($i === 0 && $isLogin)
                                            <td rowspan="{{ $penilaianCount }}" style="border: 1px solid #dee2e6; padding: 8px;">
                                                <div class="btn-group-vertical btn-group-sm flex flex-col justify-center items-center gap-4 py-2" role="group">
                                                    @if($alt['eligible'])
                                                        <!-- Reject Button -->
                                                        <a href="{{ route('alternatif.reject', $alt['id_alternatif']) }}" 
                                                            class="btn p-2 reject-btn" title="Tolak"
                                                            style="background-color: #f59e0b; border-radius: 100px; color: white;">
                                                            <i data-lucide="x-circle" style="color: white;"></i>
                                                        </a>
                                                    @else
                                                        <!-- Edit Button -->
                                                        <a href="{{ route('alternatif.edit', ['id' => $alt['id_alternatif']]) }}"
                                                            class=" p-2" title="Edit"
                                                            style="background-color: #3b82f6; color: white; border-radius: 100px;">
                                                            <i data-lucide="edit" style="color: white;"></i>
                                                        </a>

                                                        <!-- Delete Link -->
                                                        <a href="{{ route('alternatif.destroy', $alt['id_alternatif']) }}" 
                                                            class=" p-2 delete-btn" title="Hapus" 
                                                            style="background-color: #ef4444; border-radius: 100px; color: white;">
                                                            <i data-lucide="trash" style="color: white;"></i>
                                                        </a>

                                                        <!-- Accept Button -->
                                                        <a href="{{ route('alternatif.accept', $alt['id_alternatif']) }}" 
                                                            class=" p-2 accept-btn" title="Terima"
                                                            style="background-color: #10b981; border-radius: 100px; color: white;">
                                                            <i data-lucide="check-circle" style="color: white;"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td style="border: 1px solid #dee2e6; padding: 8px;">{{ $index + 1 }}</td>
                                    <td style="border: 1px solid #dee2e6; padding: 8px;">{{ $alt['nik'] }}</td>
                                    <td style="border: 1px solid #dee2e6; padding: 8px;">{{ $alt['nama_lengkap'] }}</td>
                                    <td style="border: 1px solid #dee2e6; padding: 8px;">{{ $alt['alamat'] }}</td>
                                    <td style="border: 1px solid #dee2e6; padding: 8px;">
                                        @if($alt['dokumen'])
                                        <a href="{{ asset('storage/' . $alt['dokumen']) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-info">
                                                    <i data-lucide="file-text"></i> Lihat
                                                </a>
                                        @else
                                            <span class="badge bg-secondary">Tidak Ada</span>
                                        @endif
                                    </td>
                                    <td colspan="3" class="text-center text-muted" style="border: 1px solid #dee2e6; padding: 8px;">Belum ada penilaian</td>
                                    @if($isLogin)
                                        <td style="border: 1px solid #dee2e6; padding: 8px;">
                                            <div class="btn-group-vertical btn-group-sm flex flex-col justify-center items-center gap-2 py-2" role="group">
                                                @if($alt['eligible'])
                                                    <!-- Reject Button -->
                                                    <a href="{{ route('alternatif.reject', $alt['id_alternatif']) }}" 
                                                        class="btn btn-warning reject-btn" title="Tolak">
                                                        <i data-lucide="x-circle" style="color: #ffc107;"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('alternatif.edit', ['id' => $alt['id_alternatif']]) }}"
                                                        class="btn btn-primary" title="Edit">
                                                        <i data-lucide="edit" style="color: #007bff;"></i>
                                                    </a>
                                                    <a href="{{ route('alternatif.destroy', $alt['id_alternatif']) }}" 
                                                        class="btn btn-danger delete-btn" title="Hapus"
                                                        onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                        <i data-lucide="trash" style="color: #dc3545;"></i>
                                                    </a>
                                                    <a href="{{ route('alternatif.accept', $alt['id_alternatif']) }}" 
                                                        class="btn btn-success accept-btn" title="Terima">
                                                        <i data-lucide="check-circle" style="color: #28a745;"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="{{ $isLogin ? 9 : 8 }}" class="text-center text-muted" style="border: 1px solid #dee2e6; padding: 8px;">Data Alternatif Kosong</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection