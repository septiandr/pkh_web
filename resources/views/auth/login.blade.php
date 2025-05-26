<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Desa Harmoni</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral min-h-screen flex items-center justify-center text-text-base font-sans">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
        <h2 class="text-2xl font-bold text-center text-primary mb-6">Selamat Datang di {{ $villageName }} {{ $currentYear }}</h2>

        @if ($errors->any())
            <div class="mb-4 text-red-600">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-text-muted" for="username">Username</label>
                <input
                    type="text"
                    name="username"
                    id="username"
                    required
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:outline-none"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-text-muted" for="password">Password</label>
                <div class="relative">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        required
                        class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:outline-none"
                    >
                    <button
                        type="button"
                        id="togglePassword"
                        class="absolute inset-y-0 right-3 flex items-center text-gray-500"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-.274.986-.68 1.902-1.194 2.707M15 12a3 3 0 01-6 0m6 0a3 3 0 01-6 0m6 0c.514.805.92 1.721 1.194 2.707C20.268 16.057 16.477 19 12 19c-4.477 0-8.268-2.943-9.542-7" />
                        </svg>
                    </button>
                </div>
            </div>

            <button
                type="submit"
                class="w-full mt-9 bg-primary text-white py-4 px-4 rounded-lg hover:bg-primary/90 transition"
            >
                Masuk
            </button>
        </form>
    </div>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.querySelector('svg').classList.toggle('text-gray-700');
        });
    </script>
</body>
</html>
