<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-paper text-ink font-sans">
    <div class="min-h-screen grid lg:grid-cols-2">
        <aside class="hidden lg:flex flex-col justify-between bg-primary text-white p-12">
            <a href="/" class="font-display text-xl font-bold">Toko<span class="text-accent">.</span></a>

            <div>
                <p class="font-display text-3xl font-bold leading-tight max-w-sm">
                    Kebutuhan harian, dari elektronik sampai dapur, di satu tempat.
                </p>
                <p class="text-white/70 text-sm mt-4 max-w-sm">
                    Elektronik, fashion, rumah tangga, kesehatan, makanan, olahraga, dan alat tulis.
                </p>
            </div>

            <p class="text-white/50 text-sm">Ongkos kirim flat Rp15.000.</p>
        </aside>

        <main class="flex items-center justify-center p-6">
            <div class="w-full max-w-sm">
                <a href="/" class="lg:hidden font-display text-xl font-bold block mb-8">
                    Toko<span class="text-accent">.</span>
                </a>
                {{ $slot }}
            </div>
        </main>
    </div>

    @livewireScripts
</body>
</html>