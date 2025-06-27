<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nieuwe Leverancier - Voedselbank Maaskantje</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#f8fafc] min-h-screen flex flex-col font-sans">
    @include('components.navbar')

    <main class="main-content flex-1 w-full">
        <div class="container mx-auto px-4 py-10">
            <div class="card max-w-xl mx-auto">
                <div class="card-header">
                    <h1 class="card-title flex items-center gap-2">
                        <i class="fas fa-truck"></i>
                        Nieuwe Leverancier
                    </h1>
                    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Terug naar overzicht
                    </a>
                </div>
                <form action="{{ route('suppliers.store') }}" method="POST" class="mt-6">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Naam leverancier *</label>
                        <input type="text" name="supplier_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Contactnummer</label>
                        <input type="text" name="contact_number" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">E-mail</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Straat</label>
                            <input type="text" name="street" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Huisnummer</label>
                            <input type="text" name="house_number" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Toevoeging</label>
                            <input type="text" name="addition" class="form-control">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Postcode</label>
                            <input type="text" name="postcode" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Plaats</label>
                            <input type="text" name="city" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Mobiel</label>
                            <input type="text" name="mobile" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Actief</label>
                        <select name="is_active" class="form-control form-select">
                            <option value="1">Ja</option>
                            <option value="0">Nee</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Notitie</label>
                        <textarea name="note" class="form-control"></textarea>
                    </div>
                    <div class="btn-group mt-4">
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
