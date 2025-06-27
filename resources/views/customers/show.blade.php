@extends('layouts.app-sections')

@section('title', 'Klant Details')

@section('content')
<div class="container">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Page Header -->
    <div class="card">
        <div class="card-header">
            <div>
                <h1 class="card-title">
                    <i class="fas fa-user"></i>
                    Klant Details
                </h1>
                <p class="card-subtitle">
                    Bekijk gedetailleerde informatie over {{ $customer->full_name ?? 'Klant #' . $customer->id }}
                </p>
            </div>
            <div class="btn-group">
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    <span class="btn-text">Terug naar Overzicht</span>
                </a>
                <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i>
                    <span class="btn-text">Wijzigen</span>
                </a>
            </div>
        </div>
    </div>

    <div class="content-grid">
        <!-- Customer Information Card -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-info-circle"></i>
                    Klantgegevens
                </h2>
            </div>
            <div class="card-content">
                <div class="detail-grid">
                    <div class="detail-item">
                        <label class="detail-label">Naam</label>
                        <div class="detail-value">{{ $customer->full_name ?? 'N/A' }}</div>
                    </div>

                    <div class="detail-item">
                        <label class="detail-label">Email</label>
                        <div class="detail-value">
                            <i class="fas fa-envelope"></i>
                            {{ $customer->email ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <label class="detail-label">Telefoonnummer</label>
                        <div class="detail-value">
                            <i class="fas fa-phone"></i>
                            {{ $customer->mobile ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <label class="detail-label">Adres</label>
                        <div class="detail-value">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $customer->street ?? '' }} {{ $customer->house_number ?? '' }}{{ $customer->house_letter ?? '' }}
                            <br>
                            {{ $customer->zipcode ?? '' }} {{ $customer->city ?? '' }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <label class="detail-label">Status</label>
                        <div class="detail-value">
                            @if($customer->is_active ?? false)
                                <span class="badge badge-success">Actief</span>
                            @else
                                <span class="badge badge-warning">Inactief</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Food Parcels Card -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-box"></i>
                    Voedselpakketten
                </h2>
                <a href="{{ route('food-parcels.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i>
                    Nieuw Pakket
                </a>
            </div>
            <div class="card-content">
                @if(count($foodParcels) > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product</th>
                                <th>Status</th>
                                <th>Datum</th>
                                <th>Acties</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($foodParcels as $parcel)
                                <tr>
                                    <td>{{ $parcel->id }}</td>
                                    <td>{{ $parcel->product_name ?? 'Onbekend' }}</td>
                                    <td>
                                        @if($parcel->is_active)
                                            <span class="badge badge-success">Actief</span>
                                        @else
                                            <span class="badge badge-warning">Inactief</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($parcel->created_at)->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('food-parcels.show', $parcel->id) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('food-parcels.edit', $parcel->id) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <i class="fas fa-boxes"></i>
                        <p>Deze klant heeft geen voedselpakketten</p>
                        <a href="{{ route('food-parcels.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i>
                            Pakket Toevoegen
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .content-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1rem;
    }

    .detail-item {
        padding: 0.75rem;
        border-bottom: 1px solid var(--border-color);
    }

    .detail-label {
        display: block;
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
    }

    .detail-value {
        font-weight: 500;
    }

    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
        color: var(--text-secondary);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    @media (max-width: 768px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection
