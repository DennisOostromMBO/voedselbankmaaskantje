<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leveranciers - Voedselbank Maaskantje</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#f8fafc] min-h-screen flex flex-col font-sans">
    @include('components.navbar')

    <main class="main-content flex-1 w-full">
        <div class="container mx-auto px-4 py-10">
            <!-- Success message at the top -->
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
                            <i class="fas fa-truck"></i>
                            Leveranciers
                        </h1>
                        <p class="card-subtitle">Overzicht van alle leveranciers</p>
                    </div>
                    <div class="btn-group">
                        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            Nieuwe Leverancier
                        </a>
                    </div>
                </div>

                <!-- Search and Filters -->
                <div class="search-container">
                    <div class="form-group search-input">
                        <label class="form-label">Zoek leveranciers</label>
                        <input type="text" class="form-control" placeholder="Zoek op naam, e-mail of telefoon...">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select class="form-control form-select">
                            <option>Alle statussen</option>
                            <option>Actief</option>
                            <option>Inactief</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">&nbsp;</label>
                        <button class="btn btn-primary">
                            <i class="fas fa-search"></i>
                            Zoeken
                        </button>
                    </div>
                </div>

                <!-- Table -->
                <div id="delete-tooltip-container"></div>
                <div class="table-container">
                    <table class="table w-full">
                        <thead>
                            <tr>
                                <th><i class="fas fa-truck"></i> Naam</th>
                                <th><i class="fas fa-phone"></i> Contactnummer</th>
                                <th><i class="fas fa-envelope"></i> E-mail</th>
                                <th><i class="fas fa-map-marker-alt"></i> Adres</th>
                                <th><i class="fas fa-chart-line"></i> Status</th>
                                <th><i class="fas fa-cogs"></i> Acties</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($suppliers as $supplier)
                            <tr>
                                <td>
                                    <div class="d-flex align-center gap-1">
                                        <i class="fas fa-truck" style="color: var(--primary-color);"></i>
                                        {{ $supplier->supplier_name ?? '-' }}
                                    </div>
                                </td>
                                <td>{{ $supplier->contact_number ?? '-' }}</td>
                                <td>{{ $supplier->email ?? '-' }}</td>
                                <td>
                                    {{ $supplier->full_address ?? '-' }}
                                </td>
                                <td>
                                    @if($supplier->is_active)
                                        <span class="badge badge-success">Actief</span>
                                    @else
                                        <span class="badge badge-danger">Inactief</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group flex flex-row items-center gap-2">
                                        <a href="{{ route('suppliers.show', $supplier->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(!empty($supplier->upcoming_delivery_at))
                                            <span class="relative group cursor-not-allowed">
                                                <span class="btn btn-warning btn-sm opacity-50 pointer-events-none cursor-not-allowed">
                                                    <i class="fas fa-edit"></i>
                                                </span>
                                                <span class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 bg-white border border-red-300 text-xs text-red-600 rounded px-2 py-1 shadow-lg opacity-0 group-hover:opacity-100 transition-opacity z-10 whitespace-nowrap cursor-not-allowed">
                                                    Kan niet bewerken: geplande levering
                                                </span>
                                            </span>
                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm opacity-50 cursor-not-allowed"
                                                onclick="showDeleteTooltip()"
                                                tabindex="0"
                                            >
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @else
                                            <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Weet je zeker dat je deze leverancier wilt verwijderen?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-[#4b5563]">Geen leveranciers gevonden.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <script>
                function showDeleteTooltip() {
                    var container = document.getElementById('delete-tooltip-container');
                    container.innerHTML = `
                        <div id="delete-error-message" class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 bg-red-500 text-white px-6 py-3 rounded shadow-lg flex items-center gap-2 transition-opacity duration-500">
                            <i class="fas fa-exclamation-circle"></i>
                            Kan niet verwijderen: er is een geplande levering voor deze leverancier.
                        </div>
                    `;
                    setTimeout(function() {
                        var msg = document.getElementById('delete-error-message');
                        if(msg) msg.style.opacity = 0;
                        setTimeout(function() {
                            container.innerHTML = '';
                        }, 500);
                    }, 4000);
                }
                </script>
                <!-- Pagination -->
                <div class="pagination flex justify-center items-center gap-2 mt-6">
                    {{ $suppliers->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>
    </main>
</body>
</html>


