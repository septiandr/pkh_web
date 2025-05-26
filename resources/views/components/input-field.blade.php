<div class="mb-6">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-2">
        {{ $label }}
    </label>

    @if($type === 'textarea')
        <textarea name="{{ $name }}" id="{{ $name }}"
            class="w-full border-2 border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 p-3"
            {{ $required ? 'required' : '' }}>{{ old($name, $value) }}</textarea>

    @elseif($type === 'file')
        <input type="file" name="{{ $name }}" id="{{ $name }}" accept="image/*" class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg
                       file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700
                       hover:file:bg-blue-100" {{ $required ? 'required' : '' }} onchange="previewFile(this)">

        <div class="mt-3" id="preview-{{ $name }}">
            @if($existing)
                <img src="{{ asset('storage/' . $existing) }}" alt="Preview" class="h-32 mt-3 rounded shadow">
            @endif
        </div>
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const input = document.getElementById('{{ $name }}');
                    const previewContainer = document.getElementById('preview-{{ $name }}');
                    const existingImage = {!! json_encode($existing) !!}; // Use json_encode for safer output

                    if (input && input.files.length === 0 && existingImage) {
                        previewContainer.innerHTML = `<img src="{{ asset('storage/') }}/${existingImage}" alt="Preview" class="h-32 mt-3 rounded shadow">`;
                    }
                });

                function previewFile(input) {
                    const file = input.files[0];
                    const previewContainer = document.getElementById('preview-' + input.name);

                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            previewContainer.innerHTML = `<img src="${e.target.result}" alt="Preview" class="h-32 mt-3 rounded shadow">`;
                        };
                        reader.readAsDataURL(file);
                    } else {
                        previewContainer.innerHTML = '';
                    }
                }
            </script>
        @endpush

    @elseif($type === 'multiselect')
    <!-- <pre>{{ print_r($options, true) }}</pre> -->

        <select name="{{ $name }}[]" id="{{ $name }}"
            class="w-full border-2 border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 p-3"
            {{ $required ? 'required' : '' }}>
            @if(empty($options))
                <p class="text-red-500 text-sm">Tidak ada data yang bisa dipilih.</p>
            @endif

            @foreach($options as $key => $option)
                <option value="{{ $key }}" {{ collect(old($name, is_array($value) ? $value : []))->contains($key) ? 'selected' : '' }}>
                    {{ $option }}
                </option>
            @endforeach
        </select>

    @else
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" value="{{ old($name, $value) }}"
            class="w-full border-2 h-10 border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 p-3"
            {{ $required ? 'required' : '' }}>
    @endif
</div>