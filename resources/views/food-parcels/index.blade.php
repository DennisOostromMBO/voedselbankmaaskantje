@extends('layouts.app')

@section('title', 'Food Parcels Management')

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
                Total Parcels
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $statistics->active ?? 0 }}</div>
            <div class="stat-label">
                <i class="fas fa-check-circle"></i>
                Active Parcels
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $statistics->inactive ?? 0 }}</div>
            <div class="stat-label">
                <i class="fas fa-times-circle"></i>
                Inactive Parcels
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $statistics->this_month ?? 0 }}</div>
            <div class="stat-label">
                <i class="fas fa-calendar-alt"></i>
                This Month
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="card">
        <div class="card-header">
            <div>
                <h1 class="card-title">
                    <i class="fas fa-box"></i>
                    Food Parcels Management
                </h1>
                <p class="card-subtitle">Manage and track all food parcels for customers</p>
            </div>
            <div class="btn-group">
                <a href="{{ route('food-parcels.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    <span class="btn-text">Add New Parcel</span>
                </a>
                
                <div class="dropdown">
                    <button class="btn btn-outline dropdown-toggle" onclick="toggleDropdown('exportDropdown')">
                        <i class="fas fa-download"></i>
                        <span class="btn-text">Export</span>
                    </button>
                    <div id="exportDropdown" class="dropdown-menu">
                        <a href="{{ route('food-parcels.export.csv', request()->query()) }}" class="dropdown-item">
                            <i class="fas fa-file-csv"></i>
                            Export as CSV
                        </a>
                        <a href="{{ route('food-parcels.export.pdf', request()->query()) }}" class="dropdown-item" target="_blank">
                            <i class="fas fa-file-pdf"></i>
                            Export as PDF
                        </a>
                    </div>
                </div>
                
                <div class="dropdown">
                    <button class="btn btn-outline dropdown-toggle" onclick="toggleDropdown('bulkDropdown')" id="bulkActionsBtn" disabled>
                        <i class="fas fa-tasks"></i>
                        <span class="btn-text">Bulk Actions</span>
                    </button>
                    <div id="bulkDropdown" class="dropdown-menu">
                        <button type="button" class="dropdown-item" onclick="bulkUpdateStatus(true)">
                            <i class="fas fa-check-circle"></i>
                            Activate Selected
                        </button>
                        <button type="button" class="dropdown-item" onclick="bulkUpdateStatus(false)">
                            <i class="fas fa-times-circle"></i>
                            Deactivate Selected
                        </button>
                        <div class="dropdown-divider"></div>
                        <button type="button" class="dropdown-item text-danger" onclick="bulkDelete()">
                            <i class="fas fa-trash"></i>
                            Delete Selected
                        </button>
                    </div>
                </div>
                
                <button class="btn btn-outline" onclick="window.print()">
                    <i class="fas fa-print"></i>
                    <span class="btn-text">Print List</span>
                </button>
            </div>
        </div>

        <!-- Search and Filters -->
        <form method="GET" action="{{ route('food-parcels.index') }}" class="search-container">
            <div class="form-group search-input">
                <label class="form-label">Search</label>
                <input 
                    type="text" 
                    name="search" 
                    class="form-control" 
                    placeholder="Search by customer name, email, product..." 
                    value="{{ $filters['search'] ?? '' }}"
                >
            </div>
            
            <div class="form-group">
                <label class="form-label">Customer</label>
                <select name="customer_id" class="form-control form-select">
                    <option value="">All Customers</option>
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
                    <option value="">All Status</option>
                    <option value="1" {{ ($filters['is_active'] ?? '') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ ($filters['is_active'] ?? '') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">&nbsp;</label>
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                        Search
                    </button>
                    <a href="{{ route('food-parcels.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Clear
                    </a>
                </div>
            </div>
        </form>

        <!-- Food Parcels Table -->
        <div class="table-container">
            <form id="bulkActionsForm" method="POST">
                @csrf
                @method('DELETE')
                
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                <label class="checkbox-container">
                                    <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                    <span class="checkmark"></span>
                                </label>
                            </th>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-user"></i> Customer</th>
                            <th><i class="fas fa-box"></i> Product</th>
                            <th><i class="fas fa-warehouse"></i> Stock</th>
                            <th><i class="fas fa-toggle-on"></i> Status</th>
                            <th><i class="fas fa-calendar"></i> Created</th>
                            <th><i class="fas fa-cogs"></i> Actions</th>
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
                                <span class="badge badge-secondary">#{{ $parcel->id }}</span>
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
                                        <span class="text-muted">No product info</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div>
                                    <span class="badge badge-info">
                                        Qty: {{ $parcel->stock_quantity ?? 0 }}
                                    </span>
                                    @if($parcel->stock_expiry_date)
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-times"></i>
                                            Exp: {{ \Carbon\Carbon::parse($parcel->stock_expiry_date)->format('d/m/Y') }}
                                        </small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($parcel->is_active)
                                    <span class="badge badge-success">
                                        <i class="fas fa-check"></i>
                                        Active
                                    </span>
                                @else
                                    <span class="badge badge-warning">
                                        <i class="fas fa-pause"></i>
                                        Inactive
                                    </span>
                                @endif

                                @if(isset($parcel->expiry_status))
                                    <br>
                                    @if($parcel->expiry_status === 'Expired')
                                        <span class="badge badge-danger">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Expired
                                        </span>
                                    @elseif($parcel->expiry_status === 'Expiring Soon')
                                        <span class="badge badge-warning">
                                            <i class="fas fa-clock"></i>
                                            Expiring Soon
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
                                        title="View Details"
                                    >
                                        <i class="fas fa-eye"></i>
                                        View
                                    </a>
                                    <a 
                                        href="{{ route('food-parcels.edit', $parcel->id) }}" 
                                        class="btn btn-warning btn-sm"
                                        title="Edit Parcel"
                                    >
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </a>
                                    <form 
                                        action="{{ route('food-parcels.destroy', $parcel->id) }}" 
                                        method="POST" 
                                        style="display: inline;"
                                        onsubmit="return confirm('Are you sure you want to delete this food parcel?')"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            class="btn btn-danger btn-sm"
                                            title="Delete Parcel"
                                        >
                                            <i class="fas fa-trash"></i>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <div style="padding: 2rem;">
                                    <i class="fas fa-box" style="font-size: 3rem; color: #9ca3af; margin-bottom: 1rem;"></i>
                                    <h3>No Food Parcels Found</h3>
                                    <p class="text-muted">
                                        @if(!empty($filters))
                                            No parcels match your search criteria. Try adjusting your filters.
                                        @else
                                            Start by creating your first food parcel.
                                        @endif
                                    </p>
                                    <a href="{{ route('food-parcels.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i>
                                        Create First Parcel
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </form>
        </div>

        @if($foodParcels->count() > 0)
            <!-- Results Summary -->
            <div style="margin-top: 1rem; padding: 1rem; background: var(--light-bg); border-radius: var(--border-radius);">
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i>
                    Showing {{ $foodParcels->count() }} food parcel(s)
                    @if(!empty($filters))
                        with applied filters
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
        alert('Please select at least one food parcel to update.');
        return;
    }
    
    const statusText = status ? 'activate' : 'deactivate';
    if (!confirm(`Are you sure you want to ${statusText} ${selectedCheckboxes.length} selected food parcel(s)?`)) {
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
        alert('Please select at least one food parcel to delete.');
        return;
    }
    
    if (!confirm(`Are you sure you want to delete ${selectedCheckboxes.length} selected food parcel(s)? This action cannot be undone!`)) {
        return;
    }
    
    const form = document.getElementById('bulkActionsForm');
    form.action = '{{ route("food-parcels.bulk.delete") }}';
    form.submit();
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateBulkActions();
});
</script>
@endsection
