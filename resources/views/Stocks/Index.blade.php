<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Stock List</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="header no-print">
        <nav class="navbar container">
            <a href="#" class="logo">Voedselbank Maaskantje</a>
            <ul class="nav-links">
                <li><a href="#" class="nav-link active">Dashboard</a></li>
                <li><a href="#" class="nav-link">Stocks</a></li>
            </ul>
        </nav>
    </header>
    <main class="main-content">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h1 class="card-title">Stock List</h1>
                        <p class="card-subtitle">Overzicht van alle voorraad in het systeem</p>
                    </div>
                    <div class="btn-group">
                        <a href="{{ route('stocks.create') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i> Add Stock
                        </a>
                    </div>
                </div>
                <div class="table-container">
                    @if(count($stocks) === 0)
                        <div class="alert alert-danger text-center mb-4">
                            momenteel geen voorraden beschikbaar
                        </div>
                    @else
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Productnaam</th>
                                <th>Categorie</th>
                                <th>Nummer</th>
                                <th>Ontvangstdatum</th>
                                <th>Leverdatum</th>
                                <th>Eenheid</th>
                                <th>Voorraad</th>
                                <th>Geleverd</th>
                                <th>Uitgedeeld</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stocks as $stock)
                                <tr>
                                    <td>
                                        @php
                                            // Always show the first word from note (typed product name), fallback to joined product_name
                                            $typedProduct = '';
                                            if (!empty($stock->note)) {
                                                $typedProduct = trim(explode(' ', $stock->note)[0]);
                                            }
                                            $displayProduct = $typedProduct ?: $stock->product_name;
                                        @endphp
                                        {{ $displayProduct }}
                                    </td>
                                    <td>{{ $stock->category_name }}</td>
                                    <td>{{ $stock->number }}</td>
                                    <td>{{ $stock->received_date }}</td>
                                    <td>{{ $stock->delivered_date }}</td>
                                    <td>{{ $stock->unit }}</td>
                                    <td>{{ $stock->quantity_in_stock }}</td>
                                    <td>{{ $stock->quantity_delivered }}</td>
                                    <td>{{ $stock->quantity_supplied }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('stocks.edit', $stock->id) }}" class="btn btn-warning btn-sm">Update</a>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </main>
</body>
</html>