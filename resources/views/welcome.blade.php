<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Voedselbank Maaskantje</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f8fafc] min-h-screen flex flex-col font-sans">
    <header class="w-full bg-gradient-to-r from-[#3b82f6] to-[#2563eb] py-8 shadow-lg">
        <div class="container mx-auto px-8 flex flex-col md:flex-row items-center justify-between">
            <div class="flex items-center gap-4">
                <span class="text-3xl md:text-4xl font-bold text-white drop-shadow">Voedselbank Maaskantje</span>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('login') }}"
                   class="inline-block bg-gradient-to-r from-[#f53003] to-[#dc2626] text-white font-semibold rounded-lg px-8 py-3 shadow hover:from-[#d42a00] hover:to-[#b91c1c] transition text-lg">
                    Inloggen
                </a>
            </div>
        </div>
    </header>
    <main class="flex-1 flex flex-col items-center justify-center">
        <section class="w-full max-w-5xl mx-auto px-4 py-16 flex flex-col md:flex-row gap-12 items-center">
            <div class="flex-1 text-center md:text-left">
                <h2 class="text-2xl md:text-3xl font-semibold text-[#1b1b18] mb-4">Voor elkaar, met elkaar</h2>
                <p class="text-[#4b5563] text-lg md:text-xl mb-8">
                    Welkom bij <span class="font-semibold text-[#3b82f6]">Voedselbank Maaskantje</span>!<br>
                    Wij ondersteunen mensen in Maaskantje en omgeving die tijdelijk een steuntje in de rug nodig hebben.<br>
                    Samen zorgen we ervoor dat niemand zonder eten hoeft te zitten.
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                    <div class="bg-[#f9fafb] rounded-lg p-6 border border-[#e5e7eb] shadow">
                        <div class="font-semibold text-[#2563eb] mb-1 text-lg">Openingstijden</div>
                        <div class="text-[#4b5563] text-base">Vrijdag: 10:00 - 14:00</div>
                    </div>
                    <div class="bg-[#f9fafb] rounded-lg p-6 border border-[#e5e7eb] shadow">
                        <div class="font-semibold text-[#2563eb] mb-1 text-lg">Adres</div>
                        <div class="text-[#4b5563] text-base">Dorpsstraat 12, 5271 AA Maaskantje</div>
                    </div>
                </div>
                <div class="text-[#10b981] text-base italic">
                    Wil je helpen of doneren? Neem contact met ons op!
                </div>
            </div>
            <div class="flex-1 flex items-center justify-center">
                <img src="https://cdn.nos.nl/image/2022/05/12/860223/3840x2160a.jpg"
                     alt="Voedselbank sfeerbeeld"
                     class="rounded-2xl shadow-lg w-full max-w-md object-cover border border-[#e5e7eb]" />
            </div>
        </section>
    </main>
    <footer class="w-full bg-[#2563eb] text-white py-4 text-center text-sm">
        &copy; {{ date('Y') }} Voedselbank Maaskantje. Alle rechten voorbehouden.
    </footer>
</body>
</html>
</html>
