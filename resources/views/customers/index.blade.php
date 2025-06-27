<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klanten - Voedselbank Maaskantje</title>
    @vite(['public/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#f8fafc] min-h-screen flex flex-col font-sans">
    @include('components.navbar')

    <main class="main-content flex-1 w-full">
        <div class="container mx-auto px-4 py-10">
            <!-- Success/Error message at the top -->
            @if(session('success'))
                <div id="success-message" class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 bg-green-500 text-white px-6 py-3 rounded shadow-lg flex items-center gap-2 transition-opacity duration-500">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
                <script>
                    setTimeout(function() {
                        var msg = document.getElementById('success-message');
                        if(msg) msg.style.opacity = 0;
                    }, 5000);
                </script>
            @endif
            @if(session('error'))
                <div id="error-message" class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 bg-red-500 text-white px-6 py-3 rounded shadow-lg flex items-center gap-2 transition-opacity duration-500">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
                <script>
                    setTimeout(function() {
                        var msg = document.getElementById('error-message');
                        if(msg) msg.style.opacity = 0;
                    }, 5000);
                </script>
            @endif

            <div class="card">
                <div class="card-header">
                    <div>
                        <h1 class="card-title flex items-center gap-2">
                            <i class="fas fa-users"></i>
                            Klanten
                        </h1>
                        <p class="card-subtitle">Overzicht van alle klanten</p>
                    </div>
                    <div class="btn-group">
                        <a href="{{ route('customers.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            Nieuwe Klant
                        </a>
                    </div>
                </div>

                <!-- Search and Filters -->
                <form method="GET" action="{{ route('customers.index') }}" class="search-container mb-4 flex flex-wrap gap-4">
                    <div class="form-group search-input">
                        <label class="form-label">Zoek klanten</label>
                        <input type="text" name="search" class="form-control" placeholder="Zoek op Familienaam ...." value="{{ request('search') }}">
                    </div>
                    <div class="form-group">

                    </div>
                    <div class="form-group">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                            Zoeken
                        </button>
                    </div>
                </form>

                <div class="table-container">
                    @if(count($customers) === 0)
                        <div class="text-red-600 text-center py-8 text-lg font-semibold">
                            Momenteel geen klantgegevens beschikbaar.
                        </div>
                    @else
                    <table class="table w-full">
                        <thead>
                            <tr>
                                <th><i class="fas fa-user"></i> Naam</th>
                                <th><i class="fas fa-users"></i> Familienaam</th>
                                <th><i class="fas fa-map-marker-alt"></i> Adres</th>
                                <th><i class="fas fa-phone"></i> Mobiel</th>
                                <th><i class="fas fa-envelope"></i> E-mail</th>
                                <th><i class="fas fa-birthday-cake"></i> Leeftijd</th>
                                <th><i class="fas fa-id-card"></i> Nr</th>
                                <th><i class="fas fa-star"></i> Wens</th>
                                <th><i class="fas fa-cogs"></i> Acties</th>
                            </tr>
                        </thead>
                        <tbody>
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
                                        <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning btn-sm" title="Bewerken">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if(empty($customer->has_food_parcel) || !$customer->has_food_parcel)
                                            <button
                                                class="btn btn-danger btn-sm opacity-50 cursor-not-allowed"
                                                title="Kan niet verwijderen omdat deze klant niet gekoppeld is aan een voedselpakket"
                                                disabled
                                            ><i class="fas fa-trash"></i></button>
                                        @else
                                            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="btn btn-danger btn-sm"
                                                    title="Verwijderen"
                                                    onclick="return confirm('Weet je zeker dat je deze klant wilt verwijderen?')"
                                                ><i class="fas fa-trash"></i></button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
                <!-- Pagination -->
                <div class="pagination flex justify-center items-center gap-2 mt-6">
                    {{ $customers->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>
    </main>
</body>
</html>
