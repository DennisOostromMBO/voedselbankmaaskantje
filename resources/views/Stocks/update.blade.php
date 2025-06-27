<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Stock</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container mt-5">
        <h2>Update Stock Quantities</h2>
        <form action="{{ route('stocks.updateQuantities', $stock->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Quantity in Stock</label>
                <input type="number" name="quantity_in_stock" class="form-control" min="0" value="{{ $stock->quantity_in_stock }}" required>
            </div>
            <div class="form-group">
                <label>Quantity Delivered</label>
                <input type="number" name="quantity_delivered" class="form-control" min="0" value="{{ $stock->quantity_delivered }}">
            </div>
            <div class="form-group">
                <label>Quantity Supplied</label>
                <input type="number" name="quantity_supplied" class="form-control" min="0" value="{{ $stock->quantity_supplied }}">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('stocks.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>