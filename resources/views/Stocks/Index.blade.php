@extends('layouts.app-sections')

@section('title', 'Stock List')

@section('content')
<div class="container">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h1 class="card-title">Stock List</h1>
                        <p class="card-subtitle">Overzicht van alle voorraad in het systeem</p>
                    </div>
                    <div class="btn-group">
                        <a href="{{ route('stocks.create') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i> Nieuw stock
                        </a>
                    </div>
                </div>
                @if(session('custom_error'))
                    <div class="alert alert-danger text-center mb-4">
                        Fout: {{ session('custom_error') }}
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success text-center mb-4">
                        Succes: {{ session('success') }}
                    </div>
                @endif
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
                                <th>Acties</th>
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
                                            <a href="{{ route('stocks.edit', $stock->id) }}" class="btn btn-warning btn-sm">Bewerken</a>
                                            <form action="{{ route('stocks.destroy', $stock->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Weet je zeker dat je deze voorraad wilt verwijderen?')">verwijder</button>
                                            </form>
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
@endsection
