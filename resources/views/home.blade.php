{{-- resources/views/home.blade.php --}}
<!DOCTYPE html>
<html lang="id" class="h-full bg-neutral">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda Desa</title>
    @if (app()->environment('local'))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
<script type="module">
    const lucide = window.lucide;
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof lucide !== 'undefined' && typeof lucide.createIcons === 'function') {
            lucide.createIcons();
        } else {
            console.error('Lucide library is not loaded or createIcons function is unavailable.');
        }
    });
</script>
    @else
        <link rel="stylesheet" href="{{ asset('build/assets/app.css') }}">
        <script src="{{ asset('build/assets/app.js') }}" defer></script>
    @endif
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
</head>

<body class="h-full flex flex-col font-sans text-text-base">

    <!-- Header -->
    <header class="bg-primary text-white py-6 shadow">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">{{ $villageName }}</h1>
                <p class="text-sm text-white/80">Sistem Informasi Program Sosial</p>
            </div>
            <div class="flex items-center space-x-4">
                @if (session('isLogin'))
                    <a href="/logout" class="flex items-center space-x-2 text-white hover:text-white/80 transition">
                        <i data-lucide="log-out" class="w-6 h-6"></i>
                        <span>Logout</span>
                    </a>
                @else
                    <a href="/login" class="flex items-center space-x-2 text-white hover:text-white/80 transition">
                        <i data-lucide="log-in" class="w-6 h-6"></i>
                        <span>Login Admin</span>
                    </a>
                @endif
            </div>
        </div>
    </header>

    <!-- Main -->
    <main class="flex-1 max-w-7xl mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold mb-6 text-primary">Program Bantuan Sosial</h2>

        <p>Welcome to {{ $villageName }} Village</p>
        <p>Current Year: {{ $currentYear }}</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($programs as $program)
                <a href="/program/{{ $program['id'] }}" 
                    @class([
                        'bg-white',
                        'rounded-2xl',
                        'shadow',
                        'p-6',
                        'flex',
                        'flex-col',
                        'items-start',
                        'space-y-3',
                        'hover:cursor-pointer',
                        'hover:shadow-xl',
                        'hover:p-7',
                        'transition-all',
                        'duration-300',
                    ])>
                    <div class="bg-primary/10 p-2 rounded-full">
                        <i data-lucide="{{ htmlspecialchars($program['icon'] ?? 'alert-circle', ENT_QUOTES, 'UTF-8') }}"
                            class="w-6 h-6 text-primary group-hover:scale-110 transition"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-primary">{{ $program['name'] }}</h3>
                    <p class="text-text-muted text-sm">{{ $program['desc'] }}</p>
                </a>
            @endforeach
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-secondary text-white py-4 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center text-sm">
            &copy; {{ $currentYear }} {{ $villageName }}. Semua hak dilindungi.
        </div>
    </footer>

</body>

</html>

