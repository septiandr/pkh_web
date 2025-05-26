<!DOCTYPE html>
<html lang="id" class="h-full bg-neutral">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $program['name'] }}</title>
    <link rel="stylesheet" href="{{ asset('build/assets/app.css') }}">
</head>

<body class="h-full flex flex-col font-sans text-text-base">
    <header class="bg-primary text-white py-6 shadow">
        <div class="max-w-7xl mx-auto px-4">
            <h1 class="text-2xl font-bold">{{ $program['name'] }}</h1>
        </div>
    </header>

    <main class="flex-1 max-w-7xl mx-auto px-4 py-8">
        <p class="text-lg text-text-muted">{{ $program['desc'] }}</p>
        <a href="/" class="text-primary hover:underline mt-4 block">Kembali ke Beranda</a>
    </main>
</body>

</html>
