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
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Number</th>
                                <th>Ontvangdatum</th>
                                <th>Uigeleverddatum</th>
                                <th>Eenheid</th>
                                <th>Aantal Op Voorad</th>
                                <th>Aantal Uigegeven</th>
                                <th>Aantal Bijgeleverd</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stocks as $stock)
                                <tr>
                                    <td>{{ $stock->product_name }}</td>
                                    <td>{{ $stock->category_name }}</td>
                                    <td>{{ $stock->number }}</td>
                                    <td>{{ $stock->ontvangdatum }}</td>
                                    <td>{{ $stock->uigeleverddatum }}</td>
                                    <td>{{ $stock->eenheid }}</td>
                                    <td>{{ $stock->aantalOpVoorad }}</td>
                                    <td>{{ $stock->aantalUigegeven }}</td>
                                    <td>{{ $stock->aantalBijgeleverd }}</td>
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