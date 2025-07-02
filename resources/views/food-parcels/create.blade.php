@extends('layouts.app-sections')

@section('title', 'Nieuw Voedselpakket Toevoegen')

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
                    <i class="fas fa-plus"></i>
                    Nieuw Voedselpakket Toevoegen
                </h1>
                <p class="card-subtitle">
                    Voeg een nieuw voedselpakket toe aan het systeem
                </p>
            </div>
            <div class="btn-group">
                <a href="{{ route('food-parcels.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    <span class="btn-text">Terug naar Overzicht</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Create Form -->
    <div class="card">
        <div class="card-content">
            <form action="{{ route('food-parcels.store') }}" method="POST" id="createFoodParcelForm" class="needs-validation" novalidate>
                @csrf

                <div class="form-grid">
                    <!-- Customer Selection -->
                    <div class="form-group">
                        <label for="customer_id" class="form-label required">
                            <i class="fas fa-user"></i>
                            Klant
                        </label>
                        <select name="customer_id" id="customer_id" class="form-control select2 @error('customer_id') is-invalid @enderror" required>
                            <option value="">Selecteer een klant...</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    #{{ $customer->id }} - {{ $customer->name ?? $customer->email }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text">
                            <i class="fas fa-info-circle"></i>
                            Selecteer de klant die dit voedselpakket zal ontvangen
                        </small>
                    </div>

                    <!-- Stock Selection -->
                    <div class="form-group">
                        <label for="stock_id" class="form-label required">
                            <i class="fas fa-warehouse"></i>
                            Voorraad Item
                        </label>
                        <select name="stock_id" id="stock_id" class="form-control select2 @error('stock_id') is-invalid @enderror" required>
                            <option value="">Selecteer een voorraad item...</option>
                            @foreach($stocks as $stock)
                                <option value="{{ $stock->id }}"
                                        data-quantity="{{ $stock->quantity_in_stock }}"
                                        data-unit="{{ $stock->unit }}"
                                        data-product="{{ $stock->product_name }}"
                                        data-category="{{ $stock->category_name }}"
                                        data-received="{{ $stock->received_date ? \Carbon\Carbon::parse($stock->received_date)->format('d-m-Y') : 'N/A' }}"
                                        {{ old('stock_id') == $stock->id ? 'selected' : '' }}>
                                    {{ $stock->display_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('stock_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text">
                            <i class="fas fa-info-circle"></i>
                            Kies het voorraad item om in dit pakket op te nemen
                        </small>
                    </div>

                    <!-- Stock Preview -->
                    <div id="stockPreview" class="stock-preview" style="display: none;">
                        <div class="preview-header">
                            <i class="fas fa-eye"></i>
                            Voorraad Informatie Voorbeeld
                        </div>
                        <div class="preview-content">
                            <div class="preview-item">
                                <span class="preview-label">Beschikbare Hoeveelheid:</span>
                                <span id="previewQuantity" class="preview-value">-</span>
                            </div>
                            <div class="preview-item">
                                <span class="preview-label">Ontvangen Datum:</span>
                                <span id="previewReceived" class="preview-value">-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <label for="is_active" class="form-label">
                            <i class="fas fa-toggle-on"></i>
                            Status
                        </label>
                        <div class="form-switch-group">
                            <div class="form-switch">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" id="is_active" value="1"
                                       class="form-switch-input @error('is_active') is-invalid @enderror"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label for="is_active" class="form-switch-label">
                                    <span class="form-switch-button"></span>
                                    <span class="form-switch-text">
                                        <span class="active-text">Actief</span>
                                        <span class="inactive-text">Inactief</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text">
                            <i class="fas fa-info-circle"></i>
                            Schakel in om dit voedselpakket te activeren of deactiveren
                        </small>
                    </div>

                    <!-- Notes -->
                    <div class="form-group full-width">
                        <label for="note" class="form-label">
                            <i class="fas fa-sticky-note"></i>
                            Notities
                        </label>
                        <textarea name="note" id="note" rows="4"
                                  class="form-control @error('note') is-invalid @enderror"
                                  placeholder="Voeg eventuele aanvullende notities of opmerkingen over dit voedselpakket toe...">{{ old('note') }}</textarea>
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text">
                            <i class="fas fa-info-circle"></i>
                            Optioneel: Voeg speciale instructies of opmerkingen toe
                        </small>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-save"></i>
                        Voedselpakket Toevoegen
                    </button>

                    <button type="reset" class="btn btn-warning btn-lg">
                        <i class="fas fa-undo"></i>
                        Formulier Leegmaken
                    </button>

                    <a href="{{ route('food-parcels.index') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-times"></i>
                        Annuleren
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Information Cards -->
    <div class="info-grid">
        <!-- Instructions Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle"></i>
                    Instructies
                </h3>
            </div>
            <div class="card-content">
                <div class="help-item">
                    <i class="fas fa-user"></i>
                    <div>
                        <strong>Klant Selectie:</strong>
                        <p>Kies de klant die dit voedselpakket zal ontvangen. Je kunt zoeken op naam of ID.</p>
                    </div>
                </div>
                <div class="help-item">
                    <i class="fas fa-warehouse"></i>
                    <div>
                        <strong>Voorraad Beheer:</strong>
                        <p>Selecteer voorraad items die beschikbaar zijn en niet verlopen. Controleer het voorbeeld voor voorraad details.</p>
                    </div>
                </div>
                <div class="help-item">
                    <i class="fas fa-toggle-on"></i>
                    <div>
                        <strong>Status Controle:</strong>
                        <p>Gebruik de schakelaar om het voedselpakket te activeren of deactiveren naar behoefte.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar"></i>
                    Snelle Statistieken
                </h3>
            </div>
            <div class="card-content">
                <div class="info-item">
                    <span class="info-label">Beschikbare Klanten:</span>
                    <span class="info-value">{{ count($customers) }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Beschikbare Voorraad Items:</span>
                    <span class="info-value">{{ count($stocks) }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Nieuwe Voedselpakket ID:</span>
                    <span class="info-value">#{{ (App\Models\FoodParcel::max('id') ?? 0) + 1 }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles for Create Form -->
<style>
/* Import styles from edit form */
.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

/* Stock Preview */
.stock-preview {
    grid-column: 1 / -1;
    background: var(--bg-light);
    border: 2px dashed var(--primary-color);
    border-radius: 8px;
    padding: 1rem;
    margin-top: 0.5rem;
}

.preview-header {
    font-weight: 600;
    color: var(--primary-color);
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.preview-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.preview-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem;
    background: white;
    border-radius: 4px;
}

.preview-label {
    font-weight: 500;
    color: var(--text-secondary);
}

.preview-value {
    font-weight: 600;
    color: var(--text-primary);
}

/* Form Switch */
.form-switch-group {
    margin-top: 0.5rem;
}

.form-switch {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.form-switch-input {
    display: none;
}

.form-switch-label {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
    user-select: none;
}

.form-switch-button {
    position: relative;
    width: 50px;
    height: 26px;
    background: var(--border-color);
    border-radius: 13px;
    transition: all 0.3s ease;
}

.form-switch-button::after {
    content: '';
    position: absolute;
    top: 2px;
    left: 2px;
    width: 22px;
    height: 22px;
    background: white;
    border-radius: 50%;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.form-switch-input:checked + .form-switch-label .form-switch-button {
    background: var(--success-color);
}

.form-switch-input:checked + .form-switch-label .form-switch-button::after {
    transform: translateX(24px);
}

.form-switch-text {
    font-weight: 500;
}

.form-switch-input:checked + .form-switch-label .inactive-text,
.form-switch-input:not(:checked) + .form-switch-label .active-text {
    display: none;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
    padding-top: 2rem;
    border-top: 1px solid var(--border-color);
}

/* Info Grid */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border-light);
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 500;
    color: var(--text-secondary);
}

.info-value {
    font-weight: 600;
    color: var(--text-primary);
}

.help-item {
    display: flex;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid var(--border-light);
}

.help-item:last-child {
    border-bottom: none;
}

.help-item i {
    color: var(--primary-color);
    margin-top: 0.25rem;
    flex-shrink: 0;
}

.help-item strong {
    color: var(--text-primary);
}

.help-item p {
    margin: 0.25rem 0 0 0;
    color: var(--text-secondary);
    font-size: 0.875rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column;
    }

    .btn-group {
        flex-direction: column;
        width: 100%;
    }
}

@media (max-width: 480px) {
    .card-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }

    .preview-content {
        grid-template-columns: 1fr;
    }

    .form-switch-label {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}
</style>

<!-- JavaScript for Form Enhancement -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const stockSelect = document.getElementById('stock_id');
    const stockPreview = document.getElementById('stockPreview');
    const previewQuantity = document.getElementById('previewQuantity');
    const previewReceived = document.getElementById('previewReceived');

    /**
     * Update stock preview when selection changes
     */
    stockSelect.addEventListener('change', updateStockPreview);

    function updateStockPreview() {
        const selectedOption = stockSelect.options[stockSelect.selectedIndex];

        if (selectedOption.value) {
            const quantity = selectedOption.dataset.quantity || 'N/A';
            const unit = selectedOption.dataset.unit || 'stuks';
            const received = selectedOption.dataset.received || 'N/A';

            previewQuantity.textContent = quantity + ' ' + unit;
            previewReceived.textContent = received;

            stockPreview.style.display = 'block';
        } else {
            stockPreview.style.display = 'none';
        }
    }

    // Form validation
    const form = document.getElementById('createFoodParcelForm');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });
});

/**
 * Initialize Select2 for better dropdowns
 */
$(document).ready(function() {
    if (typeof $.fn.select2 !== 'undefined') {
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: function() {
                return $(this).data('placeholder');
            },
            allowClear: true
        });
    }
});
</script>
@endsection
