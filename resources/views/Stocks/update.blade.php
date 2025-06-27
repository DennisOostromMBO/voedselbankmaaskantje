<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Stock</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .update-card {
            max-width: 500px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.07);
            padding: 32px 28px 24px 28px;
        }
        .update-card h2 {
            font-size: 1.6rem;
            font-weight: 600;
            margin-bottom: 18px;
        }
        .update-card .alert {
            margin-bottom: 18px;
        }
        .update-card label {
            font-weight: 500;
            margin-bottom: 4px;
        }
        .update-card .form-group {
            margin-bottom: 18px;
        }
        .update-card .btn-group {
            display: flex;
            gap: 12px;
        }
        .update-card .stock-details {
            background: #f8fafc;
            border-radius: 8px;
            padding: 12px 18px;
            margin-bottom: 20px;
            font-size: 0.98rem;
        }
        .update-card .stock-details li {
            margin-bottom: 4px;
        }
    </style>
</head>
<body>
    <div class="update-card">
        <h2>Update Stock Quantities</h2>
        {{-- Show custom error and validation errors --}}
        @if(session('custom_error'))
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="stock-details">
            <ul>
                <li>
                    <strong>Productnaam:</strong>
                    @php
                        $typedProduct = '';
                        if (!empty($stock->note)) {
                            $typedProduct = trim(explode(' ', $stock->note)[0]);
                        }
                        $displayProduct = $typedProduct ?: ($stock->product_name ?? '');
                    @endphp
                    {{ $displayProduct }}
                </li>
            </ul>
        </div>

        <form action="{{ route('stocks.updateQuantities', $stock->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label>vooraad</label>
                <input type="number" name="quantity_in_stock" class="form-control" min="0" value="{{ old('quantity_in_stock', $stock->quantity_in_stock) }}" required>
            </div>
            <div class="form-group">
                <label>geleverd</label>
                <input type="number" name="quantity_delivered" class="form-control" min="0" value="{{ old('quantity_delivered', $stock->quantity_delivered) }}">
            </div>
            <div class="form-group">
                <label>uitgedeeld</label>
                <input type="number" name="quantity_supplied" class="form-control" min="0" value="{{ old('quantity_supplied', $stock->quantity_supplied) }}">
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('stocks.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>