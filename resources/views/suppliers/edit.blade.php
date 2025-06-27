<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leverancier bewerken - Voedselbank Maaskantje</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#f8fafc] min-h-screen flex flex-col font-sans">
    @include('components.navbar')

    <main class="main-content flex-1 w-full">
        <div class="container mx-auto px-4 py-10">
            <div class="card max-w-5xl mx-auto">
                <div class="card-header flex flex-col md:flex-row md:items-center md:justify-between">
                    <h1 class="card-title flex items-center gap-2">
                        <i class="fas fa-truck"></i>
                        Leverancier bewerken
                    </h1>
                    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary btn-sm mt-4 md:mt-0">
                        <i class="fas fa-arrow-left"></i> Terug naar overzicht
                    </a>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="list-disc list-inside text-left text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST" class="mt-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Supplier Info (left) -->
                        <div>
                            <div class="form-group">
                                <label class="form-label">Naam leverancier *</label>
                                <input type="text" name="supplier_name" class="form-control" value="{{ old('supplier_name', $supplier->supplier_name) }}" required>
                                @error('supplier_name')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Contactnummer</label>
                                <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number', $supplier->contact_number) }}">
                                @error('contact_number')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">E-mail</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $supplier->email) }}">
                                @error('email')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Actief</label>
                                <select name="is_active" class="form-control form-select">
                                    <option value="1" @if(old('is_active', $supplier->is_active)==1) selected @endif>Ja</option>
                                    <option value="0" @if(old('is_active', $supplier->is_active)==0) selected @endif>Nee</option>
                                </select>
                                @error('is_active')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Notitie</label>
                                <textarea name="note" class="form-control">{{ old('note', $supplier->note) }}</textarea>
                                @error('note')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mt-4">
                                <label class="form-label">Volgende levering (datum en tijd)</label>
                                <input type="datetime-local" name="upcoming_delivery_at" class="form-control"
                                    value="{{ old('upcoming_delivery_at', $supplier->upcoming_delivery_at ? \Carbon\Carbon::parse($supplier->upcoming_delivery_at)->format('Y-m-d\TH:i') : '') }}">
                                @error('upcoming_delivery_at')
                                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Address/Contact Info (right) -->
                        <div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Straat</label>
                                    <input type="text" name="street" class="form-control" value="{{ old('street', $supplier->street) }}">
                                    @error('street')
                                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Huisnummer</label>
                                    <input type="text" name="house_number" class="form-control" value="{{ old('house_number', $supplier->house_number) }}">
                                    @error('house_number')
                                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Toevoeging</label>
                                    <input type="text" name="addition" class="form-control" value="{{ old('addition', $supplier->addition) }}">
                                    @error('addition')
                                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Postcode</label>
                                    <input type="text" name="postcode" class="form-control" value="{{ old('postcode', $supplier->postcode) }}">
                                    @error('postcode')
                                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Plaats</label>
                                    <input type="text" name="city" class="form-control" value="{{ old('city', $supplier->city) }}">
                                    @error('city')
                                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Mobiel</label>
                                    <input type="text" name="mobile" class="form-control" value="{{ old('mobile', $supplier->mobile) }}">
                                    @error('mobile')
                                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-group mt-8">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Opslaan
                        </button>
                        <a href="{{ route('suppliers.index') }}" class="btn btn-outline">
                            <i class="fas fa-times"></i> Annuleren
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
