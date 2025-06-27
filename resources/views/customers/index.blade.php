<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Noto+Color+Emoji&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans" style="font-family: 'Noto Color Emoji', sans-serif;">
    <div class="max-w-5xl mx-auto mt-8 px-2">
        <h1 class="text-2xl font-bold mb-4">Customers</h1>
        <div class="bg-white rounded-xl shadow border border-blue-100">
            <table class="w-full text-sm">
                <thead class="bg-gradient-to-r from-blue-600 to-blue-400 text-white">
                    <tr>
                        <th class="px-2 py-1 text-left font-semibold uppercase">Name</th>
                        <th class="px-2 py-1 text-left font-semibold uppercase">Family</th>
                        <th class="px-2 py-1 text-left font-semibold uppercase">Address</th>
                        <th class="px-2 py-1 text-left font-semibold uppercase">Mobile</th>
                        <th class="px-2 py-1 text-left font-semibold uppercase">Email</th>
                        <th class="px-2 py-1 text-left font-semibold uppercase">Age</th>
                        <th class="px-2 py-1 text-left font-semibold uppercase">Nr</th>
                        <th class="px-2 py-1 text-left font-semibold uppercase">Wish</th>
                        <th class="px-2 py-1 text-left font-semibold uppercase">Acties</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($customers as $customer)
                        <tr class="hover:bg-blue-50/60 transition-colors duration-200">
                            <td class="px-2 py-1 text-gray-800">{{ $customer->full_name }}</td>
                            <td class="px-2 py-1 text-gray-700">{{ $customer->family_name }}</td>
                            <td class="px-2 py-1 text-gray-700 truncate max-w-[120px]" title="{{ $customer->full_address }}">{{ $customer->full_address }}</td>
                            <td class="px-2 py-1 text-gray-700 font-mono">{{ preg_replace('/\D+/', '', $customer->mobile) }}</td>
                            <td class="px-2 py-1 text-gray-700 truncate max-w-[140px]" title="{{ $customer->email }}">{{ $customer->email }}</td>
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
                                <button class="bg-cyan-500 hover:bg-cyan-600 text-white px-1 py-0.5 rounded text-xs" title="View">👁️</button>
                                <button class="bg-red-500 hover:bg-red-600 text-white px-1 py-0.5 rounded text-xs" title="Delete">🗑️</button>
                                <button class="bg-orange-400 hover:bg-orange-500 text-white px-1 py-0.5 rounded text-xs" title="Edit">✏️</button>
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
