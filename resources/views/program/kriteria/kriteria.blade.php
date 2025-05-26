@extends('layouts.app')

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>

@section('content')
    <div class="flex flex-row min-h-3.5">
        <x-sidebar />
        <div class="flex-1 p-8 bg-gray-50">
            <h1 class="text-center my-4 text-4xl font-bold">Data Kriteria dan Sub Kriteria</h1>
            <div class="flex justify-between items-center mb-4">
                <form action="{{ route('kriteria') }}" method="GET" class="flex items-center">
                    <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}"
                        class="form-control w-64 mr-2 p-2 border-2 border-[#343a40] rounded-lg" />
                    <button type="submit" class="btn btn-primary flex p-2 rounded-lg"
                        style="background-color: #343a40; color: #fff;">
                        <i data-lucide="search"></i>
                    </button>
                </form>
                @if($isLogin)

                <button type="button" class="flex flex-row gap-2 p-2 rounded-lg"
                    style="background-color: #343a40; color: #fff;" data-bs-toggle="modal"
                    data-bs-target="#addKriteriaModal">
                    <i data-lucide="plus-circle"></i> Tambah Kriteria
                </button>
                @endif
            </div>
            <!-- Kriteria and Sub Kriteria Table -->
            <div style="overflow-y: auto; max-height: 80vh; background-color: #f8f9fa;">
                <table class="table table-bordered table-hover align-middle w-full"
                    style="border-top: 2px solid #dee2e6; border-bottom: 2px solid #dee2e6;">
                    <thead class="table-dark text-center h-16"
                        style="position: sticky; top: 0; z-index: 1; background-color: #343a40; color: #fff;">
                        <tr>
                            <th>No</th>
                            <th>Kriteria</th>
                            <th>Sub Kriteria</th>
                            <th>Bobot/Nilai</th>
                            @if($isLogin)
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if($kriteria->isEmpty())
                            <tr>
                                <td colspan="{{ $isLogin ? 5 : 4 }}" class="text-center text-muted" style="padding: 16px;">
                                    Tidak ada data kriteria yang tersedia.
                                </td>
                            </tr>
                        @else
                            @foreach($kriteria as $index => $kri)
                                <tr>
                                    <td style="border: 1px solid #dee2e6; padding: 8px;">{{ $index + 1 }}</td>
                                    <td style="border: 1px solid #dee2e6; padding: 8px;">{{ $kri->nama_kriteria }}</td>
                                    <td style="border: 1px solid #dee2e6; padding: 8px;">
                                        <ul class="list-disc list-inside">
                                            @foreach($kri->subKriteria as $sub)
                                                <li>
                                                    {{ $sub->nama_sub_kriteria }}
                                                    <span class="text-muted">({{ $sub->nilai_sub_kriteria }})</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td style="border: 1px solid #dee2e6; padding: 8px;">{{ $kri->bobot }}</td>
                                    @if($isLogin && $kri->nama_kriteria != 'Pendapatan' && $kri->nama_kriteria != 'Luas tanah')
                                        <td style="border: 1px solid #dee2e6; padding: 8px;">
                                            <div class="btn-group-vertical btn-group-sm flex flex-col justify-center items-center gap-4 py-2"
                                                role="group">
                                                <!-- Edit Button -->
                                                <button type="button" class="p-2" title="Edit"
                                                    style="background-color: #3b82f6; color: white; border-radius: 100px;"
                                                    data-bs-toggle="modal" data-bs-target="#editKriteriaModal-{{ $kri->id_kriteria }}">
                                                    <i data-lucide="edit" style="color: white;"></i>
                                                </button>

                                                <!-- Delete Button -->
                                                <button type="button" class="p-2 delete-btn" title="Hapus"
                                                    style="background-color: #ef4444; border-radius: 100px; color: white;"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteKriteriaModal-{{ $kri->id_kriteria }}">
                                                    <i data-lucide="trash" style="color: white;"></i>
                                                </button>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for Adding Kriteria -->
    <div class="modal fade" id="addKriteriaModal" tabindex="-1" aria-labelledby="addKriteriaModalLabel" aria-hidden="true">
        <div class="modal-dialog z-40">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #343a40; color: #fff;">
                    <h5 class="modal-title" id="addKriteriaModalLabel">Tambah Kriteria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('kriteria.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_kriteria" class="form-label">Nama Kriteria</label>
                            <input type="text" class="form-control" id="nama_kriteria" name="nama_kriteria" required>
                        </div>
                        <div class="mb-3">
                            <label for="bobot" class="form-label">Bobot</label>
                            <input type="number" step="0.01" class="form-control" id="bobot" name="bobot" required>
                        </div>
                        <div class="mb-3">
                            <label for="sub_kriteria" class="form-label">Sub Kriteria</label>
                            <div id="subKriteriaContainer">
                                <div class="d-flex align-items-center mb-2">
                                    <input type="text" class="form-control me-2" name="sub_kriteria[0][nama_sub_kriteria]"
                                        placeholder="Nama Sub Kriteria" required>
                                    <input type="number" step="0.01" class="form-control me-2"
                                        name="sub_kriteria[0][nilai_sub_kriteria]" placeholder="Bobot" required>
                                    <a href="javascript:void(0);" class="btn btn-danger btn-sm remove-sub-kriteria"
                                        onclick="removeSubKriteria(this)">Hapus</a>
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="addSubKriteria()">Tambah
                                Sub Kriteria</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"
                            style="background-color: #343a40; color: #fff;">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach($kriteria as $kri)
        <div class="modal fade" id="editKriteriaModal-{{ $kri->id_kriteria }}" tabindex="-1"
            aria-labelledby="editKriteriaModalLabel-{{ $kri->id_kriteria }}" aria-hidden="true">
            <div class="modal-dialog z-40">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #343a40; color: #fff;">
                        <h5 class="modal-title" id="editKriteriaModalLabel-{{ $kri->id_kriteria }}">Edit Kriteria</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('kriteria.update', $kri->id_kriteria) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nama_kriteria_{{ $kri->id_kriteria }}" class="form-label">Nama Kriteria</label>
                                <input type="text" class="form-control" id="nama_kriteria_{{ $kri->id_kriteria }}"
                                    name="nama_kriteria" value="{{ $kri->nama_kriteria }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="bobot_{{ $kri->id_kriteria }}" class="form-label">Bobot</label>
                                <input type="number" step="0.01" class="form-control" id="bobot_{{ $kri->id_kriteria }}"
                                    name="bobot" value="{{ $kri->bobot }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="sub_kriteria_{{ $kri->id_kriteria }}" class="form-label">Sub Kriteria</label>
                                <div id="editSubKriteriaContainer-{{ $kri->id_kriteria }}">
                                    @foreach($kri->subKriteria as $index => $sub)
                                        <div class="d-flex align-items-center mb-2">
                                            <input type="text" class="form-control me-2"
                                                name="sub_kriteria[{{ $index }}][nama_sub_kriteria]"
                                                value="{{ $sub->nama_sub_kriteria }}" required>
                                            <input type="number" step="0.01" class="form-control me-2"
                                                name="sub_kriteria[{{ $index }}][nilai_sub_kriteria]"
                                                value="{{ $sub->nilai_sub_kriteria }}" required>
                                            <a href="javascript:void(0);" class="btn btn-danger btn-sm remove-sub-kriteria"
                                                onclick="removeSubKriteria(this)">Hapus</a>
                                        </div>
                                    @endforeach
                                </div>
                                <a href="javascript:void(0);" class="btn btn-secondary btn-sm mt-2"
                                    onclick="addSubKriteria({{ $kri->id_kriteria }})">Tambah Sub Kriteria</a>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="javascript:void(0);" class="btn btn-secondary" data-bs-dismiss="modal">Batal</a>
                            <button type="submit" class="btn btn-primary"
                                style="background-color: #343a40; color: #fff;">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Modal for Deleting Kriteria -->
    @foreach($kriteria as $kri)
        <div class="modal fade" id="deleteKriteriaModal-{{ $kri->id_kriteria }}" tabindex="-1"
            aria-labelledby="deleteKriteriaModalLabel-{{ $kri->id_kriteria }}" aria-hidden="true">
            <div class="modal-dialog z-40">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #ef4444; color: #fff;">
                        <h5 class="modal-title" id="deleteKriteriaModalLabel-{{ $kri->id_kriteria }}">Hapus Kriteria</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus kriteria <strong>{{ $kri->nama_kriteria }}</strong> beserta sub
                            kriterianya?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('kriteria.destroy', $kri->id_kriteria) }}" method="POST"
                            style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                style="background-color: #ef4444; color: #fff;">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script>
        let subKriteriaIndex = 1;

        function addSubKriteria(kriteriaId = null) {
            const containerId = kriteriaId
                ? `editSubKriteriaContainer-${kriteriaId}`
                : 'subKriteriaContainer';
            const container = document.getElementById(containerId);

            if (!container) {
                console.error(`Container with ID ${containerId} not found.`);
                return;
            }

            const newSubKriteria = document.createElement('div');
            newSubKriteria.classList.add('d-flex', 'align-items-center', 'mb-2');
            newSubKriteria.innerHTML = `
                        <input type="text" class="form-control me-2" name="sub_kriteria[${subKriteriaIndex}][nama_sub_kriteria]" placeholder="Nama Sub Kriteria" required>
                        <input type="number" step="0.01" class="form-control me-2" name="sub_kriteria[${subKriteriaIndex}][nilai_sub_kriteria]" placeholder="Bobot" required>
                        <a href="javascript:void(0);" class="btn btn-danger btn-sm remove-sub-kriteria" onclick="removeSubKriteria(this)">Hapus</a>
                    `;
            container.appendChild(newSubKriteria);
            subKriteriaIndex++;
        }

        function removeSubKriteria(button) {
            button.parentElement.remove();
        }
    </script>

@endsection