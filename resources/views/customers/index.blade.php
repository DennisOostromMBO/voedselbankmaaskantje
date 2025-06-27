<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klanten</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Noto+Color+Emoji&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans" style="font-family: 'Noto Color Emoji', sans-serif;">
    <div class="max-w-[98vw] mx-auto mt-8 px-2">
        <h1 class="text-2xl font-bold mb-4">Klanten</h1>
        <div class="bg-white rounded-xl shadow border border-blue-100">
            <table class="w-full text-sm">
                <thead class="bg-gradient-to-r from-blue-600 to-blue-400 text-white">
                    <tr>
                        <th class="px-2 py-1 text-left font-semibold uppercase">Naam</th>
                        <th class="px-2 py-1 text-left font-semibold uppercase">Familienaam</th>
                        <th class="px-2 py-1 text-left font-semibold uppercase w-[260px]">Adres</th>
                        <th class="px-2 py-1 text-left font-semibold uppercase">Mobiel</th>
                        <th class="px-2 py-1 text-left font-semibold uppercase w-[220px]">E-mail</th>
                        <th class="px-2 py-1 text-left font-semibold uppercase">Leeftijd</th>
                        <th class="px-2 py-1 text-left font-semibold uppercase">Nr</th>
                        <th class="px-2 py-1 text-left font-semibold uppercase">Wens</th>
                        <th class="px-2 py-1 text-left font-semibold uppercase">Acties</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($customers as $customer)
                        <tr class="hover:bg-blue-50/60 transition-colors duration-200">
                            <td class="px-2 py-1 text-gray-800">{{ $customer->full_name }}</td>
                            <td class="px-2 py-1 text-gray-700">{{ $customer->family_name }}</td>
                            <td class="px-2 py-1 text-gray-700 font-mono" title="{{ $customer->full_address }}">{{ preg_replace('/\s+/', '', $customer->full_address) }}</td>
                            <td class="px-2 py-1 text-gray-700 font-mono">{{ preg_replace('/\D+/', '', $customer->mobile) }}</td>
                            <td class="px-2 py-1 text-gray-700" title="{{ $customer->email }}">{{ $customer->email }}</td>
                            <td class="px-2 py-1 text-gray-700 font-mono">{{ preg_replace('/\D+/', '', $customer->age) }}</td>
                            <td class="px-2 py-1 text-gray-700 font-mono">{{ preg_replace('/\D+/', '', $customer->customer_number) }}</td>
                            <td class="px-2 py-1 text-gray-700">
                                <span class="inline-block px-2 py-0.5 text-xs font-semibold rounded
                                    @if($customer->wish === 'Active') bg-green-100 text-green-700
                                    @elseif($customer->wish === 'Pending') bg-yellow-100 text-yellow-700
                                    @elseif($customer->wish === 'Inactive') bg-red-100 text-red-700
                                    @else bg-gray-100 text-gray-600
                                    @endif">
                                    {{ $customer->wish ?? 'geen' }}
                                </span>
                            </td>
                            <td class="px-2 py-1">
                                <button class="bg-orange-400 hover:bg-orange-500 text-white px-1 py-0.5 rounded text-xs" title="Bewerken">✏️</button>
                                <button class="bg-red-500 hover:bg-red-600 text-white px-1 py-0.5 rounded text-xs" title="Verwijderen">🗑️</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
</body>
</html>
