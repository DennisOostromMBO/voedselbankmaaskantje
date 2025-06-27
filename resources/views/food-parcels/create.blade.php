@extends('layouts.app')

@section('title', 'Create Food Parcel')

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" style="margin-bottom: 1rem;">
        <ol class="breadcrumb" style="background: none; padding: 0;">
            <li class="breadcrumb-item">
                <a href="{{ route('food-parcels.index') }}" style="color: var(--primary-color); text-decoration: none;">
                    <i class="fas fa-box"></i> Food Parcels
                </a>
            </li>
            <li class="breadcrumb-item active" style="color: var(--medium-text);">
                Create New Parcel
            </li>
        </ol>
    </nav>

    <!-- Alert Messages -->
    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Main Content Card -->
    <div class="card">
        <div class="card-header">
            <div>
                <h1 class="card-title">
                    <i class="fas fa-plus"></i>
                    Create New Food Parcel
                </h1>
                <p class="card-subtitle">Assign a food item from stock to a customer</p>
            </div>
            <div class="btn-group">
                <a href="{{ route('food-parcels.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Back to List
                </a>
            </div>
        </div>

        <!-- Create Form -->
        <form method="POST" action="{{ route('food-parcels.store') }}" id="createForm">
            @csrf

            <div class="form-row">
                <!-- Customer Selection -->
                <div class="form-group">
                    <label class="form-label" for="customer_id">
                        <i class="fas fa-user"></i>
                        Customer *
                    </label>
                    <select
                        name="customer_id"
                        id="customer_id"
                        class="form-control form-select @error('customer_id') is-invalid @enderror"
                        required
                    >
                        <option value="">-- Select Customer --</option>
                        @foreach($customers as $customer)
                            <option
                                value="{{ $customer->id }}"
                                {{ old('customer_id') == $customer->id ? 'selected' : '' }}
                            >
                                {{ $customer->first_name }} {{ $customer->last_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">
                        Choose the customer who will receive this food parcel
                    </small>
                </div>

                <!-- Stock Selection -->
                <div class="form-group">
                    <label class="form-label" for="stock_id">
                        <i class="fas fa-warehouse"></i>
                        Available Stock *
                    </label>
                    <select
                        name="stock_id"
                        id="stock_id"
                        class="form-control form-select @error('stock_id') is-invalid @enderror"
                        required
                    >
                        <option value="">-- Select Stock Item --</option>
                        @foreach($stocks as $stock)
                            <option
                                value="{{ $stock->id }}"
                                {{ old('stock_id') == $stock->id ? 'selected' : '' }}
                                data-quantity="{{ $stock->quantity }}"
                                data-expiry="{{ $stock->expiry_date ? \Carbon\Carbon::parse($stock->expiry_date)->format('d/m/Y') : '' }}"
                                data-product="{{ $stock->product->name ?? 'Unknown Product' }}"
                            >
                                {{ $stock->product->name ?? 'Unknown Product' }}
                                (Qty: {{ $stock->quantity }})
                                @if($stock->expiry_date)
                                    - Exp: {{ \Carbon\Carbon::parse($stock->expiry_date)->format('d/m/Y') }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('stock_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">
                        Select from available stock items with quantity > 0
                    </small>
                </div>
            </div>

            <!-- Stock Details Preview -->
            <div id="stockDetails" class="form-group" style="display: none;">
                <div class="card" style="background: var(--light-bg); border: 2px solid var(--primary-color); margin-bottom: 1rem;">
                    <div class="card-body" style="padding: 1rem;">
                        <h5 style="color: var(--primary-color); margin-bottom: 0.5rem;">
                            <i class="fas fa-info-circle"></i>
                            Selected Stock Details
                        </h5>
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Product:</strong>
                                <span id="selectedProduct">-</span>
                            </div>
                            <div class="col-md-4">
                                <strong>Available Quantity:</strong>
                                <span id="selectedQuantity" class="badge badge-info">-</span>
                            </div>
                            <div class="col-md-4">
                                <strong>Expiry Date:</strong>
                                <span id="selectedExpiry">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <!-- Status -->
                <div class="form-group">
                    <label class="form-label" for="is_active">
                        <i class="fas fa-toggle-on"></i>
                        Status
                    </label>
                    <select
                        name="is_active"
                        id="is_active"
                        class="form-control form-select @error('is_active') is-invalid @enderror"
                    >
                        <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>
                    @error('is_active')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">
                        Set the initial status of this food parcel
                    </small>
                </div>
            </div>

            <!-- Notes -->
            <div class="form-group">
                <label class="form-label" for="note">
                    <i class="fas fa-sticky-note"></i>
                    Notes
                </label>
                <textarea
                    name="note"
                    id="note"
                    class="form-control @error('note') is-invalid @enderror"
                    rows="4"
                    placeholder="Add any additional notes about this food parcel (optional)..."
                >{{ old('note') }}</textarea>
                @error('note')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">
                    Optional: Special instructions, allergies, or other relevant information
                </small>
            </div>

            <!-- Form Actions -->
            <div class="card-footer" style="background: var(--light-bg); margin: 0 -2rem -2rem -2rem; padding: 1.5rem 2rem; border-radius: 0 0 var(--border-radius) var(--border-radius);">
                <div class="btn-group" style="width: 100%; justify-content: flex-end; gap: 1rem;">
                    <a href="{{ route('food-parcels.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i>
                        Create Food Parcel
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript for enhanced UX -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const stockSelect = document.getElementById('stock_id');
    const stockDetails = document.getElementById('stockDetails');
    const selectedProduct = document.getElementById('selectedProduct');
    const selectedQuantity = document.getElementById('selectedQuantity');
    const selectedExpiry = document.getElementById('selectedExpiry');

    // Show stock details when selection changes
    stockSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];

        if (this.value) {
            const product = selectedOption.getAttribute('data-product');
            const quantity = selectedOption.getAttribute('data-quantity');
            const expiry = selectedOption.getAttribute('data-expiry');

            selectedProduct.textContent = product;
            selectedQuantity.textContent = quantity;
            selectedExpiry.textContent = expiry || 'No expiry date';

            // Update quantity badge color based on stock level
            selectedQuantity.className = 'badge ' + (
                quantity > 10 ? 'badge-success' :
                quantity > 5 ? 'badge-warning' :
                'badge-danger'
            );

            stockDetails.style.display = 'block';
        } else {
            stockDetails.style.display = 'none';
        }
    });

    // Form validation
    document.getElementById('createForm').addEventListener('submit', function(e) {
        const customerSelect = document.getElementById('customer_id');
        const stockSelect = document.getElementById('stock_id');

        if (!customerSelect.value || !stockSelect.value) {
            e.preventDefault();
            alert('Please select both a customer and a stock item.');
            return false;
        }

        // Check if stock has quantity
        const selectedOption = stockSelect.options[stockSelect.selectedIndex];
        const quantity = parseInt(selectedOption.getAttribute('data-quantity'));

        if (quantity <= 0) {
            e.preventDefault();
            alert('Selected stock item has no available quantity.');
            return false;
        }

        return true;
    });
});
</script>

<!-- Additional CSS for mobile responsiveness -->
<style>
.breadcrumb {
    display: flex;
    flex-wrap: wrap;
    list-style: none;
    margin: 0;
    padding: 0;
}

.breadcrumb-item {
    display: flex;
    align-items: center;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: ">";
    color: var(--medium-text);
    margin: 0 0.5rem;
}

.invalid-feedback {
    color: var(--danger-color);
    font-size: 0.8rem;
    margin-top: 0.25rem;
}

.form-control.is-invalid {
    border-color: var(--danger-color);
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }

    .card-header {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
    }

    .btn-group {
        flex-direction: column;
        width: 100%;
    }

    .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }

    .card-footer .btn-group {
        flex-direction: row;
        justify-content: space-between;
    }

    .card-footer .btn {
        flex: 1;
        margin-bottom: 0;
        margin-left: 0.5rem;
    }

    .card-footer .btn:first-child {
        margin-left: 0;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 0.75rem;
    }

    .card {
        padding: 1rem;
    }

    .card-footer {
        margin: 0 -1rem -1rem -1rem;
        padding: 1rem;
    }

    .card-footer .btn-group {
        flex-direction: column;
    }

    .card-footer .btn {
        width: 100%;
        margin: 0 0 0.5rem 0;
    }

    .card-footer .btn:last-child {
        margin-bottom: 0;
    }
}

/* Form enhancements */
.form-text {
    font-size: 0.8rem;
    color: var(--medium-text);
    margin-top: 0.25rem;
}

.form-control:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    border-color: var(--primary-color);
}

select.form-control {
    background-position: right 0.75rem center;
}
</style>
@endsection
