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
                @foreach($fields as $field)
                    <div>
                        <x-input-field :type="$field['type']" :name="$field['name']" :label="$field['label']"
                            :value="$field['value'] ?? ''" :required="$field['required'] ?? false" :min="$field['min'] ?? null"
                            :options="$field['options'] ?? []" :existing="$field['value'] ?? []" />
                        @error($field['name'])
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                @if ($data['method'] === 'POST')
                    <div>
                        <x-input-field type="file" name="dokumen" label="Surat Tidak Mampu" :existing="$alternatif->dokumen ?? null" />
                        @if(isset($alternatif->dokumen))
                            <a href="{{ asset('storage/' . $alternatif->dokumen) }}" target="_blank"
                                class="text-blue-600 text-sm underline">Lihat Dokumen</a>
                        @endif
                        @error('dokumen')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

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