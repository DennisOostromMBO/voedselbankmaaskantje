<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Voedselbank Maaskantje</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['public/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#f8fafc] min-h-screen flex flex-col font-sans">
    @include('components.navbar')
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