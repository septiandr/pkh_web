<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PKH Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="h-screen flex flex-col">
        {{-- Header --}}
        <header class="bg-gray-800 text-white p-4 shadow-2xl flex justify-between items-center pr-7">
            <h1 class="text-xl font-bold">Program Keluarga Harapan (PKH)</h1>
            <a href="{{ url('/') }}" class="text-white hover:text-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M10.707 2.293a1 1 0 0 1 1.414 0l9 9a1 1 0 0 1-1.414 1.414L20 11.414V20a2 2 0 0 1-2 2h-5a1 1 0 0 1-1-1v-5h-2v5a1 1 0 0 1-1 1H6a2 2 0 0 1-2-2v-8.586l-.707.707a1 1 0 0 1-1.414-1.414l9-9z" />
                </svg>
            </a>
        </header>

        {{-- Main Content --}}
        <main class="flex-1">
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="bg-gray-800 text-white p-4 text-center">
            <p>&copy; {{ date('Y') }} Program Keluarga Harapan. All rights reserved.</p>
        </footer>
    </div>

    @stack('scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();
        });
    </script>
</body>
</html>
