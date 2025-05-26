<div class="sidebar bg-gray-800 text-white h-screen w-64">
    <ul class="sidebar-menu">
        @php
            $menuItems = [
                ['href' => '/program/1', 'title' => 'Dashboard', 'label' => 'Dashboard'],
                ['href' => '/alternatif', 'title' => 'Alternatif', 'label' => 'Alternatif'],
                ['href' => '/kriteria', 'title' => 'Kriteria', 'label' => 'Kriteria'],
            ];
            $currentUrl = request()->path();

            // Deteksi jika salah satu submenu Penilaian aktif
            $isPenilaianActive = in_array($currentUrl, [
                'penilaian/alternatif-kriteria',
                'penilaian/normalisasi',
                'penilaian/akhir'
            ]);
        @endphp

        @foreach ($menuItems as $item)
            <li class="menu-item p-4  {{ $currentUrl === ltrim($item['href'], '/') ? 'bg-gray-700' : 'hover:bg-gray-700' }}">
                <a href="{{ $item['href'] }}" title="{{ $item['title'] }}" class="block text-white no-underline text-md">{{ $item['label'] }}</a>
            </li>
        @endforeach

        <li class="menu-item">
            <div class="accordion">
                <button class="accordion-header hover:bg-gray-700 p-4 w-full text-left flex items-center justify-between"
                    onclick="toggleAccordion(this)">
                    <span>Penilaian</span>
                    <svg class="w-4 h-4 transform transition-transform {{ $isPenilaianActive ? 'rotate-90' : '' }}"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                <div class="accordion-content pl-4 {{ $isPenilaianActive ? '' : 'hidden' }}">
                    <ul>
                        <li class="hover:bg-gray-700 p-2 {{ $currentUrl === 'penilaian/alternatif-kriteria' ? 'bg-gray-700' : '' }}">
                            <a href="/penilaian/alternatif-kriteria" class="block text-white no-underline text-md">Tabel Alternatif Kriteria</a>
                        </li>
                        <li class="hover:bg-gray-700 p-2 {{ $currentUrl === 'penilaian/normalisasi' ? 'bg-gray-700' : '' }}">
                            <a href="/penilaian/normalisasi" class="block text-white no-underline text-md">Tabel Normalisasi</a>
                        </li>
                        <li class="hover:bg-gray-700 p-2 {{ $currentUrl === 'penilaian/akhir' ? 'bg-gray-700' : '' }}">
                            <a href="/penilaian/akhir" class="block text-white no-underline text-md">Tabel Akhir</a>
                        </li>
                    </ul>
                </div>
            </div>
        </li>

        <li class="menu-item p-4 {{ $currentUrl === 'perangkingan' ? 'bg-gray-700' : 'hover:bg-gray-700' }}">
            <a href="/perangkingan" title="Perangkingan" class="block text-white no-underline text-md">Perangkingan</a>
        </li>
    </ul>
</div>

<script>
    function toggleAccordion(button) {
        const content = button.nextElementSibling;
        const svg = button.querySelector('svg');
        content.classList.toggle('hidden');
        svg.classList.toggle('rotate-90');
    }
</script>
