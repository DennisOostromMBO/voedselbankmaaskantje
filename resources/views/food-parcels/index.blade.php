@extends('layouts.app-sections')

@section('title', 'Voedselpakketten Overzicht')

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

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">{{ $statistics->total ?? 0 }}</div>
            <div class="stat-label">
                <i class="fas fa-box"></i>
                Totaal Pakketten
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $statistics->active ?? 0 }}</div>
            <div class="stat-label">
                <i class="fas fa-check-circle"></i>
                Actieve Pakketten
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $statistics->inactive ?? 0 }}</div>
            <div class="stat-label">
                <i class="fas fa-times-circle"></i>
                Inactieve Pakketten
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $statistics->this_month ?? 0 }}</div>
            <div class="stat-label">
                <i class="fas fa-calendar-alt"></i>
                Deze Maand
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="card">
        <div class="card-header">
            <div>
                <h1 class="card-title">
                    <i class="fas fa-box"></i>
                    Voedselpakketten Overzicht
                </h1>
                <p class="card-subtitle">Beheer en volg alle voedselpakketten voor klanten</p>
            </div>
            <div class="btn-group">
                <a href="{{ route('food-parcels.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    <span class="btn-text">Nieuw Voedselpakket Toevoegen</span>
                </a>

                <div class="dropdown">
                    <button class="btn btn-outline dropdown-toggle" onclick="toggleDropdown('exportDropdown')">
                        <i class="fas fa-download"></i>
                        <span class="btn-text">Export</span>
                    </button>
                    <div id="exportDropdown" class="dropdown-menu">
                        <a href="{{ route('food-parcels.export.csv', request()->query()) }}" class="dropdown-item">
                            <i class="fas fa-file-csv"></i>
                            Exporteer als CSV
                        </a>
                        <a href="{{ route('food-parcels.export.pdf', request()->query()) }}" class="dropdown-item" target="_blank">
                            <i class="fas fa-file-pdf"></i>
                            Exporteer als PDF
                        </a>
                    </div>
                </div>

                <div class="dropdown">
                    <button class="btn btn-outline dropdown-toggle" onclick="toggleDropdown('bulkDropdown')" id="bulkActionsBtn" disabled>
                        <i class="fas fa-tasks"></i>
                        <span class="btn-text">Bulk Acties</span>
                    </button>
                    <div id="bulkDropdown" class="dropdown-menu">
                        <button type="button" class="dropdown-item" onclick="bulkUpdateStatus(true)">
                            <i class="fas fa-check-circle"></i>
                            Activeer Geselecteerde
                        </button>
                        <button type="button" class="dropdown-item" onclick="bulkUpdateStatus(false)">
                            <i class="fas fa-times-circle"></i>
                            Deactiveer Geselecteerde
                        </button>
                        <div class="dropdown-divider"></div>
                        <button type="button" class="dropdown-item text-danger" onclick="bulkDelete()">
                            <i class="fas fa-trash"></i>
                            Verwijder Geselecteerde
                        </button>
                    </div>
                </div>

                <button class="btn btn-outline" onclick="window.print()">
                    <i class="fas fa-print"></i>
                    <span class="btn-text">Lijst Afdrukken</span>
                </button>
            </div>
        </div>

        <!-- Search and Filters -->
        <form method="GET" action="{{ route('food-parcels.index') }}" class="search-container">
            <div class="form-group search-input">
                <label class="form-label">Zoeken</label>
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Zoek op klantnaam, e-mail, product..."
                    value="{{ $filters['search'] ?? '' }}"
                >
            </div>

            <div class="form-group">
                <label class="form-label">Klant</label>
                <select name="customer_id" class="form-control form-select">
                    <option value="">Alle Klanten</option>
                    @foreach($customers as $customer)
                        <option
                            value="{{ $customer->id }}"
                            {{ ($filters['customer_id'] ?? '') == $customer->id ? 'selected' : '' }}
                        >
                            {{ $customer->first_name }} {{ $customer->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="is_active" class="form-control form-select">
                    <option value="">Alle Statussen</option>
                    <option value="1" {{ ($filters['is_active'] ?? '') === '1' ? 'selected' : '' }}>Actief</option>
                    <option value="0" {{ ($filters['is_active'] ?? '') === '0' ? 'selected' : '' }}>Inactief</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">&nbsp;</label>
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                        Zoeken
                    </button>
                    <a href="{{ route('food-parcels.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Wissen
                    </a>
                </div>
            </div>
        </form>

        <!-- Food Parcels Table -->
        <div class="table-container">
            <form id="bulkActionsForm" method="POST">
                @csrf

                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                <label class="checkbox-container">
                                    <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                    <span class="checkmark"></span>
                                </label>
                            </th>
                            <th><i class="fas fa-user"></i> Klant</th>
                            <th><i class="fas fa-box"></i> Product</th>
                            <th><i class="fas fa-warehouse"></i> Voorraad</th>
                            <th><i class="fas fa-toggle-on"></i> Status</th>
                            <th><i class="fas fa-calendar"></i> Aangemaakt</th>
                            <th><i class="fas fa-cogs"></i> Acties</th>
                        </tr>
                    </thead>
                <tbody>
                    @forelse($foodParcels as $parcel)
                        <tr>
                            <td>
                                <label class="checkbox-container">
                                    <input type="checkbox" name="selected_ids[]" value="{{ $parcel->id }}" class="row-checkbox" onchange="updateBulkActions()">
                                    <span class="checkmark"></span>
                                </label>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $parcel->customer_name }}</strong>
                                    @if($parcel->customer_email)
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-envelope"></i>
                                            {{ $parcel->customer_email }}
                                        </small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div>
                                    @if($parcel->product_name)
                                        <strong>{{ $parcel->product_name }}</strong>
                                        @if($parcel->category_name)
                                            <br>
                                            <small class="text-muted">{{ $parcel->category_name }}</small>
                                        @endif
                                    @else
                                        <span class="text-muted">Geen productinformatie</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div>
                                    <span class="badge badge-info">
                                        {{ $parcel->stock_quantity ?? 0 }} {{ $parcel->stock_unit ?? 'stuks' }}
                                    </span>
                                    @if($parcel->stock_received_date)
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-plus"></i>
                                            Ontvangen: {{ \Carbon\Carbon::parse($parcel->stock_received_date)->format('d/m/Y') }}
                                        </small>
                                    @endif
                                    @if($parcel->stock_delivered_date)
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-truck"></i>
                                            Geleverd: {{ \Carbon\Carbon::parse($parcel->stock_delivered_date)->format('d/m/Y') }}
                                        </small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($parcel->is_active)
                                    <span class="badge badge-success">
                                        <i class="fas fa-check"></i>
                                        Actief
                                    </span>
                                @else
                                    <span class="badge badge-warning">
                                        <i class="fas fa-pause"></i>
                                        Inactief
                                    </span>
                                @endif

                                @if(isset($parcel->expiry_status))
                                    <br>
                                    @if($parcel->expiry_status === 'Expired')
                                        <span class="badge badge-danger">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Verlopen
                                        </span>
                                    @elseif($parcel->expiry_status === 'Expiring Soon')
                                        <span class="badge badge-warning">
                                            <i class="fas fa-clock"></i>
                                            Bijna Verlopen
                                        </span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                <div>
                                    {{ \Carbon\Carbon::parse($parcel->created_at)->format('d/m/Y') }}
                                    <br>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($parcel->created_at)->format('H:i') }}
                                    </small>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a
                                        href="{{ route('food-parcels.show', $parcel->id) }}"
                                        class="btn btn-info btn-sm"
                                        title="Bekijk Details"
                                    >
                                        <i class="fas fa-eye"></i>
                                        Bekijken
                                    </a>
                                    <a
                                        href="{{ route('food-parcels.edit', $parcel->id) }}"
                                        class="btn btn-warning btn-sm"
                                        title="Bewerk Pakket"
                                    >
                                        <i class="fas fa-edit"></i>
                                        Bewerken
                                    </a>
                                    <button
                                        type="button"
                                        class="btn btn-danger btn-sm"
                                        title="Verwijder Pakket"
                                        onclick="confirmDelete({{ $parcel->id }}, '{{ addslashes($parcel->customer_name ?? 'N/A') }}', {{ $parcel->is_active ? 'true' : 'false' }})"
                                    >
                                        <i class="fas fa-trash"></i>
                                        Verwijderen
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <div style="padding: 2rem;">
                                    <i class="fas fa-box" style="font-size: 3rem; color: #9ca3af; margin-bottom: 1rem;"></i>
                                    <h3>Er zijn geen voedselpakketten beschikbaar om weer te geven.</h3>
                                    <p class="text-muted">
                                        @if(!empty($filters))
                                            Geen pakketten komen overeen met uw zoekcriteria. Probeer uw filters aan te passen.
                                        @else
                                            Begin met het maken van uw eerste voedselpakket.
                                        @endif
                                    </p>
                                    <a href="{{ route('food-parcels.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i>
                                        Nieuw Voedselpakket Toevoegen
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </form>
        </div>

        <!-- Pagination Links -->
        @if($foodParcels->hasPages())
            <div class="pagination-wrapper" style="margin-top: 1rem; display: flex; justify-content: center;">
                {{ $foodParcels->links() }}
            </div>
        @endif

        @if($foodParcels->count() > 0)
            <!-- Results Summary -->
            <div style="margin-top: 1rem; padding: 1rem; background: var(--light-bg); border-radius: var(--border-radius);">
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i>
                    Pagina {{ $foodParcels->currentPage() }} van {{ $foodParcels->lastPage() }}
                    ({{ $foodParcels->total() }} totaal, {{ $foodParcels->count() }} op deze pagina)
                    @if(!empty($filters))
                        met toegepaste filters
                    @endif
                </small>
            </div>
        @endif
    </div>
</div>

<!-- Additional CSS for mobile responsiveness -->
<style>
@media (max-width: 768px) {
    .table-container {
        font-size: 0.8rem;
    }

    .btn-group {
        flex-direction: column;
        gap: 0.25rem;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.7rem;
    }

    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }

    .card-header {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
    }

    .search-container {
        flex-direction: column;
        gap: 1rem;
    }

    .search-container .form-group {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }

    .table {
        min-width: 600px;
    }

    .btn-group .btn {
        width: 100%;
        margin-bottom: 0.25rem;
    }

    /* Responsive pagination */
    .pagination-wrapper .pagination {
        gap: 0.125rem;
        flex-wrap: wrap;
    }

    .pagination-wrapper .pagination li a,
    .pagination-wrapper .pagination li span {
        padding: 0.375rem 0.5rem;
        font-size: 0.75rem;
        min-width: 2rem;
        text-align: center;
    }
}

/* Print styles */
@media print {
    .btn, .search-container, .no-print {
        display: none !important;
    }

    .card {
        box-shadow: none;
        border: 1px solid #ddd;
    }

    .table {
        font-size: 0.8rem;
    }
}

/* Dropdown Styles */
.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-menu {
    display: none;
    position: absolute;
    background-color: white;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    border-radius: 4px;
    z-index: 1000;
    border: 1px solid var(--border-color);
    right: 0;
    top: 100%;
    margin-top: 2px;
}

.dropdown-menu.show {
    display: block;
}

.dropdown-item {
    display: block;
    width: 100%;
    padding: 8px 16px;
    text-decoration: none;
    color: var(--text-primary);
    background: none;
    border: none;
    text-align: left;
    cursor: pointer;
    transition: background-color 0.2s;
}

.dropdown-item:hover {
    background-color: var(--bg-light);
}

.dropdown-item.text-danger {
    color: var(--danger-color);
}

.dropdown-divider {
    height: 1px;
    background-color: var(--border-color);
    margin: 4px 0;
}

/* Checkbox Styles */
.checkbox-container {
    display: block;
    position: relative;
    cursor: pointer;
    user-select: none;
}

.checkbox-container input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}

.checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 18px;
    width: 18px;
    background-color: var(--bg-light);
    border: 2px solid var(--border-color);
    border-radius: 3px;
}

.checkbox-container:hover input ~ .checkmark {
    background-color: var(--primary-light);
}

.checkbox-container input:checked ~ .checkmark {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

.checkbox-container input:checked ~ .checkmark:after {
    display: block;
}

.checkbox-container .checkmark:after {
    left: 5px;
    top: 2px;
    width: 4px;
    height: 8px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

/* Light Mode Pagination Styling */
.pagination-wrapper {
    margin: 1rem 0;
    padding: 0.5rem;
    background: #ffffff;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.pagination-wrapper .pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    list-style: none;
    padding: 0;
    margin: 0;
    gap: 0.25rem;
}

.pagination-wrapper .pagination li {
    display: inline-block;
}

.pagination-wrapper .pagination li a,
.pagination-wrapper .pagination li span {
    display: block;
    padding: 0.5rem 0.75rem;
    text-decoration: none;
    color: #374151;
    background: #ffffff;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    transition: all 0.2s ease;
    font-size: 0.875rem;
    font-weight: 500;
    min-width: 2.5rem;
    text-align: center;
}

.pagination-wrapper .pagination li a:hover {
    background: #f9fafb;
    border-color: #6b7280;
    color: #1f2937;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.pagination-wrapper .pagination li.active span {
    background: #ffffff;
    color: #1f2937;
    border-color: #374151;
    border-width: 2px;
    font-weight: 600;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.pagination-wrapper .pagination li.disabled span {
    color: #9ca3af;
    background: #ffffff;
    border-color: #e5e7eb;
    cursor: not-allowed;
    opacity: 0.6;
}
</style>

<!-- JavaScript for Enhanced Functionality -->
<script>
/**
 * Toggle dropdown menu visibility
 * @param {string} dropdownId - ID of the dropdown to toggle
 */
function toggleDropdown(dropdownId) {
    const dropdown = document.getElementById(dropdownId);
    const allDropdowns = document.querySelectorAll('.dropdown-menu');

    // Close all other dropdowns
    allDropdowns.forEach(menu => {
        if (menu.id !== dropdownId) {
            menu.classList.remove('show');
        }
    });

    // Toggle the clicked dropdown
    dropdown.classList.toggle('show');
}

/**
 * Close dropdowns when clicking outside
 */
document.addEventListener('click', function(event) {
    const dropdowns = document.querySelectorAll('.dropdown');
    let clickedInsideDropdown = false;

    dropdowns.forEach(dropdown => {
        if (dropdown.contains(event.target)) {
            clickedInsideDropdown = true;
        }
    });

    if (!clickedInsideDropdown) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.remove('show');
        });
    }
});

/**
 * Toggle select all checkboxes
 */
function toggleSelectAll() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');

    rowCheckboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });

    updateBulkActions();
}

/**
 * Update bulk actions button state
 */
function updateBulkActions() {
    const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked');
    const bulkActionsBtn = document.getElementById('bulkActionsBtn');
    const selectAllCheckbox = document.getElementById('selectAll');
    const allRowCheckboxes = document.querySelectorAll('.row-checkbox');

    // Enable/disable bulk actions button
    bulkActionsBtn.disabled = selectedCheckboxes.length === 0;

    // Update select all checkbox state
    if (selectedCheckboxes.length === 0) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = false;
    } else if (selectedCheckboxes.length === allRowCheckboxes.length) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = true;
    } else {
        selectAllCheckbox.indeterminate = true;
    }
}

/**
 * Bulk update status
 * @param {boolean} status - New status value
 */
function bulkUpdateStatus(status) {
    const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked');

    if (selectedCheckboxes.length === 0) {
        alert('Selecteer ten minste één voedselpakket om bij te werken.');
        return;
    }

    const statusText = status ? 'activeren' : 'deactiveren';
    if (!confirm(`Weet u zeker dat u ${selectedCheckboxes.length} geselecteerde voedselpakket(ten) wilt ${statusText}?`)) {
        return;
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("food-parcels.bulk.update-status") }}';

    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);

    const statusInput = document.createElement('input');
    statusInput.type = 'hidden';
    statusInput.name = 'status';
    statusInput.value = status ? '1' : '0';
    form.appendChild(statusInput);

    selectedCheckboxes.forEach(checkbox => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'selected_ids[]';
        input.value = checkbox.value;
        form.appendChild(input);
    });

    document.body.appendChild(form);
    form.submit();
}

/**
 * Bulk delete food parcels
 */
function bulkDelete() {
    const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked');

    if (selectedCheckboxes.length === 0) {
        alert('Selecteer ten minste één voedselpakket om te verwijderen.');
        return;
    }

    if (!confirm(`Weet u zeker dat u ${selectedCheckboxes.length} geselecteerde voedselpakket(ten) wilt verwijderen? Deze actie kan niet ongedaan worden gemaakt!`)) {
        return;
    }

    const form = document.getElementById('bulkActionsForm');
    form.action = '{{ route("food-parcels.bulk.delete") }}';
    form.submit();
}

/**
 * Show delete confirmation popup for individual parcel
 * @param {number} id - Food parcel ID
 * @param {string} customerName - Customer name
 * @param {boolean} isActive - Whether the parcel is active
 */
function confirmDelete(id, customerName, isActive) {
    // Check if parcel is active and show appropriate message
    if (isActive) {
        alert('Dit voedselpakket kan niet worden verwijderd.\n\n' +
              'Voedselpakket ID: #' + id + '\n' +
              'Klant: ' + customerName + '\n\n' +
              'Kan voedselpakket niet verwijderen: gekoppeld aan actieve uitgifte.');
        return;
    }

    // Show confirmation dialog for inactive parcel
    const confirmed = confirm('Weet je zeker dat je dit voedselpakket wilt verwijderen?\n\n' +
                              'Voedselpakket ID: #' + id + '\n' +
                              'Klant: ' + customerName + '\n\n' +
                              'Deze actie kan niet ongedaan worden gemaakt!');

    if (confirmed) {
        // Create and submit form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('food-parcels.destroy.post', ['id' => 'PARCEL_ID_PLACEHOLDER']) }}".replace('PARCEL_ID_PLACEHOLDER', id);

        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        // Add form to body and submit
        document.body.appendChild(form);
        form.submit();
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateBulkActions();
});
</script>

@endsection
