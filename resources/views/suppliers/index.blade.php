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
                        <a href="#" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            Nieuwe Leverancier
                        </a>
                        <a href="#" class="btn btn-outline">
                            <i class="fas fa-download"></i>
                            Exporteren
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
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> ID</th>
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
                                <td>{{ $supplier->id }}</td>
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
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="#" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-[#4b5563]">Geen leveranciers gevonden.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination (optional, static for now) -->
                <div class="pagination">
                    <a href="#">‹ Vorige</a>
                    <span class="current">1</span>
                    <a href="#">2</a>
                    <a href="#">3</a>
                    <a href="#">Volgende ›</a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>

