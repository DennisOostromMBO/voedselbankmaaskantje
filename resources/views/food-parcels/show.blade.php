@extends('layouts.app-sections')

@section('title', 'Voedselpakket Details')

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
                    <i class="fas fa-box"></i>
                    Voedselpakket Details
                </h1>
                <p class="card-subtitle">
                    Bekijk gedetailleerde informatie over voedselpakket #{{ $foodParcel->id }}
                </p>
            </div>
            <div class="btn-group">
                <a href="{{ route('food-parcels.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    <span class="btn-text">Terug naar Overzicht</span>
                </a>
                <a href="{{ route('food-parcels.edit', $foodParcel->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i>
                    <span class="btn-text">Wijzigen</span>
                </a>
                <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $foodParcel->id }})">
                    <i class="fas fa-trash"></i>
                    <span class="btn-text">Verwijderen</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid">
        <!-- Food Parcel Information Card -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-info-circle"></i>
                    Parcel Information
                </h2>
            </div>
            <div class="card-content">
                <div class="detail-grid">
                    <div class="detail-item">
                        <label class="detail-label">Parcel ID</label>
                        <div class="detail-value">#{{ $foodParcel->id }}</div>
                    </div>

                    <div class="detail-item">
                        <label class="detail-label">Status</label>
                        <div class="detail-value">
                            @if($foodParcel->is_active)
                                <span class="status-badge status-active">
                                    <i class="fas fa-check-circle"></i>
                                    Active
                                </span>
                            @else
                                <span class="status-badge status-inactive">
                                    <i class="fas fa-times-circle"></i>
                                    Inactive
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="detail-item">
                        <label class="detail-label">Created Date</label>
                        <div class="detail-value">
                            <i class="fas fa-calendar"></i>
                            {{ \Carbon\Carbon::parse($foodParcel->created_at)->format('M d, Y') }}
                            <span class="text-muted">
                                ({{ \Carbon\Carbon::parse($foodParcel->created_at)->diffForHumans() }})
                            </span>
                        </div>
                    </div>

                    <div class="detail-item">
                        <label class="detail-label">Last Updated</label>
                        <div class="detail-value">
                            <i class="fas fa-clock"></i>
                            {{ \Carbon\Carbon::parse($foodParcel->updated_at)->format('M d, Y H:i') }}
                            <span class="text-muted">
                                ({{ \Carbon\Carbon::parse($foodParcel->updated_at)->diffForHumans() }})
                            </span>
                        </div>
                    </div>
                </div>

                @if($foodParcel->note)
                    <div class="detail-item full-width">
                        <label class="detail-label">Notes</label>
                        <div class="detail-value note-content">
                            <i class="fas fa-sticky-note"></i>
                            {{ $foodParcel->note }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Customer Information Card -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-user"></i>
                    Customer Information
                </h2>
            </div>
            <div class="card-content">
                <div class="detail-grid">
                    <div class="detail-item">
                        <label class="detail-label">Customer ID</label>
                        <div class="detail-value">#{{ $foodParcel->customer_id }}</div>
                    </div>

                    <div class="detail-item">
                        <label class="detail-label">Customer Name</label>
                        <div class="detail-value">
                            <i class="fas fa-user-circle"></i>
                            {{ $foodParcel->customer_name ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <label class="detail-label">Customer Email</label>
                        <div class="detail-value">
                            <i class="fas fa-envelope"></i>
                            {{ $foodParcel->customer_email ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <label class="detail-label">Customer Phone</label>
                        <div class="detail-value">
                            <i class="fas fa-phone"></i>
                            {{ $foodParcel->customer_phone ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Information Card -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-warehouse"></i>
                    Stock Information
                </h2>
            </div>
            <div class="card-content">
                <div class="detail-grid">
                    <div class="detail-item">
                        <label class="detail-label">Stock ID</label>
                        <div class="detail-value">#{{ $foodParcel->stock_id }}</div>
                    </div>

                    <div class="detail-item">
                        <label class="detail-label">Product Name</label>
                        <div class="detail-value">
                            <i class="fas fa-box"></i>
                            {{ $foodParcel->product_name ?? 'Onbekend Product' }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <label class="detail-label">Category</label>
                        <div class="detail-value">
                            <i class="fas fa-tags"></i>
                            {{ $foodParcel->category_name ?? 'Onbekende Categorie' }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <label class="detail-label">Available Quantity</label>
                        <div class="detail-value">
                            <i class="fas fa-cubes"></i>
                            {{ $foodParcel->stock_quantity ?? 0 }} {{ $foodParcel->stock_unit ?? 'stuks' }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <label class="detail-label">Received Date</label>
                        <div class="detail-value">
                            <i class="fas fa-calendar-plus"></i>
                            @if($foodParcel->stock_received_date)
                                {{ \Carbon\Carbon::parse($foodParcel->stock_received_date)->format('d/m/Y') }}
                            @else
                                Onbekend
                            @endif
                        </div>
                    </div>

                    <div class="detail-item">
                        <label class="detail-label">Delivered Date</label>
                        <div class="detail-value">
                            <i class="fas fa-truck"></i>
                            @if($foodParcel->stock_delivered_date)
                                {{ \Carbon\Carbon::parse($foodParcel->stock_delivered_date)->format('d/m/Y') }}
                            @else
                                Nog niet geleverd
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="card">
        <div class="card-content">
            <div class="action-buttons-main">
                <a href="{{ route('food-parcels.edit', $foodParcel->id) }}" class="btn btn-warning btn-lg">
                    <i class="fas fa-edit"></i>
                  Bewerk Voedselpakket
                </a>

                <button type="button" class="btn btn-danger btn-lg" onclick="confirmDelete({{ $foodParcel->id }})">
                    <i class="fas fa-trash"></i>
                    Verwijder Voedselpakket
                </button>

                <a href="{{ route('food-parcels.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fas fa-arrow-left"></i>
                    Terug naar Lijst
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-exclamation-triangle"></i>
                Confirm Deletion
            </h3>
            <button type="button" class="modal-close" onclick="closeDeleteModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this food parcel?</p>
            <p class="text-muted">
                <strong>Food Parcel ID:</strong> #{{ $foodParcel->id }}<br>
                <strong>Customer:</strong> {{ $foodParcel->customer_name ?? 'N/A' }}<br>
                <strong>Stock:</strong> {{ $foodParcel->stock_name ?? 'N/A' }}
            </p>
            <p class="warning-text">
                <i class="fas fa-exclamation-triangle"></i>
                This action cannot be undone!
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">
                <i class="fas fa-times"></i>
                Cancel
            </button>
            <form id="deleteForm" method="POST" action="{{ route('food-parcels.destroy', $foodParcel->id) }}" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i>
                    Delete Food Parcel
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Custom Styles for Details Page -->
<style>
/* Detail Grid Layout */
.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.detail-item.full-width {
    grid-column: 1 / -1;
}

.detail-label {
    font-weight: 600;
    color: var(--text-secondary);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.detail-value {
    font-weight: 500;
    color: var(--text-primary);
    font-size: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.detail-value i {
    color: var(--primary-color);
    width: 16px;
}

.note-content {
    background: var(--bg-light);
    padding: 1rem;
    border-radius: 8px;
    border-left: 4px solid var(--primary-color);
    font-style: italic;
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-active {
    background: var(--success-light);
    color: var(--success-color);
}

.status-inactive {
    background: var(--danger-light);
    color: var(--danger-color);
}

.status-warning {
    background: var(--warning-light);
    color: var(--warning-color);
}

.status-danger {
    background: var(--danger-light);
    color: var(--danger-color);
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

/* Action Buttons */
.action-buttons {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border-color);
}

.action-buttons-main {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

/* Responsive Design */
@media (max-width: 768px) {
    .content-grid {
        grid-template-columns: 1fr;
    }

    .detail-grid {
        grid-template-columns: 1fr;
    }

    .action-buttons-main {
        flex-direction: column;
    }

    .btn-group {
        flex-direction: column;
        width: 100%;
    }

    .btn-text {
        display: inline;
    }
}

@media (max-width: 480px) {
    .card-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }

    .btn-group {
        width: 100%;
    }
}
</style>

<!-- JavaScript for Modal and Interactions -->
<script>
/**
 * Show delete confirmation modal
 * @param {number} id - Food parcel ID
 */
function confirmDelete(id) {
    const modal = document.getElementById('deleteModal');
    modal.style.display = 'block';

    // Use a Blade-generated route with a placeholder, then replace in JS
    const form = document.getElementById('deleteForm');
    const routeTemplate = "{{ route('food-parcels.destroy', ['id' => 'FOODPARCEL_ID_PLACEHOLDER']) }}";
    form.action = routeTemplate.replace('FOODPARCEL_ID_PLACEHOLDER', id);
}

/**
 * Close delete confirmation modal
 */
function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('deleteModal');
    if (event.target === modal) {
        closeDeleteModal();
    }
}

// Close modal with escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endsection
