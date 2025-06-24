@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto my-10 p-6 bg-white shadow-lg rounded-2xl h-[80vh] overflow-y-auto">
        <h1 class="text-3xl font-semibold mb-6 text-gray-800">
            {{ $data['title'] }}
        </h1>

        <form action="{{ $data['action'] }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @if($data['method'] === 'PUT') @method('PUT') @endif

            <div class="grid grid-cols-1 gap-6">
                {{-- Tampilkan semua field KECUALI dokumen --}}
                @foreach($fields as $field)
                    @if($field['name'] !== 'dokumen_preview')
                        <div>
                            <x-input-field :type="$field['type']" :name="$field['name']" :label="$field['label']"
                                :value="$field['value'] ?? ''" :required="$field['required'] ?? false" :min="$field['min'] ?? null"
                                :options="$field['options'] ?? []" :existing="$field['value'] ?? []" />
                            @error($field['name'])
                                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif
                @endforeach

                {{-- Hanya satu input file untuk dokumen --}}
                <div>
                    <label for="dokumen" class="block text-sm font-medium text-gray-700 mb-1">Upload Surat Tidak
                        Mampu</label>
                    <input type="file" name="dokumen" id="dokumen"
                        class="block w-full text-sm text-gray-700 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:outline-none">

                    {{-- Tampilkan link jika dokumen sudah ada --}}
                    @if(isset($alternatif->dokumen))
                        <a href="{{ asset('storage/' . $alternatif->dokumen) }}" target="_blank"
                            class="text-blue-600 text-sm underline mt-1 inline-block">
                            Lihat Dokumen Sebelumnya
                        </a>
                    @endif

                    @error('dokumen')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="bg-gray-800 hover:bg-gray-700 text-white font-medium py-2 px-6 rounded-xl transition-all duration-200">
                    {{ $data['buttonText'] }}
                </button>
            </div>
        </form>
    </div>
@endsection

{{-- Script validasi ukuran file --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const fileInput = document.querySelector('input[name="dokumen"]');
        if (fileInput) {
            fileInput.addEventListener('change', function () {
                const maxSize = 2 * 1024 * 1024; // 2MB
                if (this.files[0] && this.files[0].size > maxSize) {
                    alert("Ukuran file terlalu besar. Maksimal 2MB.");
                    this.value = ''; // Clear file input
                }
            });
        }
    });
</script>