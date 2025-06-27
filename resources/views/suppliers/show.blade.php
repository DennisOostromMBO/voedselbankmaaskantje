<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leverancier: {{ $supplier->supplier_name }}</title>
    @vite(['public/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#f8fafc] min-h-screen flex flex-col font-sans">
    @include('components.navbar')

    <main class="main-content flex-1 w-full">
        <div class="container mx-auto px-4 py-10">
            <div class="card max-w-xl mx-auto">
                <div class="card-header">
                    <h1 class="card-title flex items-center gap-2">
                        <i class="fas fa-truck"></i>
                        {{ $supplier->supplier_name }}
                    </h1>
                    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Terug naar overzicht
                    </a>
                </div>
                <div class="mb-4">
                    <span class="font-semibold">Contactnummer:</span>
                    <span>{{ $supplier->contact_number ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <span class="font-semibold">E-mail:</span>
                    <span>{{ $supplier->email ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <span class="font-semibold">Adres:</span>
                    <span>{{ $supplier->full_address ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <span class="font-semibold">Volgende levering:</span>
                    <span>
                        @if(!empty($supplier->upcoming_delivery_at))
                            {{ \Carbon\Carbon::parse($supplier->upcoming_delivery_at)->format('d-m-Y H:i') }}
                        @else
                            Geen gepland
                        @endif
                    </span>
                </div>
                <div class="mb-4">
                    <span class="font-semibold">Status:</span>
                    @if($supplier->is_active)
                        <span class="badge badge-success">Actief</span>
                    @else
                        <span class="badge badge-danger">Inactief</span>
                    @endif
                </div>
                <div class="mb-4">
                    <span class="font-semibold">Notitie:</span>
                    <span>{{ $supplier->note ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <span class="font-semibold">Mobiel:</span>
                    <span>{{ $supplier->mobile ?? '-' }}</span>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
