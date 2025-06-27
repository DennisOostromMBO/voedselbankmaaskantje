<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock toevoegen</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="header no-print">
        <nav class="navbar container">
            <a href="#" class="logo">Voedselbank Maaskantje</a>
            <ul class="nav-links">
                <li><a href="{{ route('stocks.index') }}" class="nav-link">Terug naar overzicht</a></li>
            </ul>
        </nav>
    </header>
    <main class="main-content">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h1 class="card-title">Nieuwe voorraad toevoegen</h1>
                        <p class="card-subtitle">Vul het formulier in om een nieuwe voorraad toe te voegen.</p>
                    </div>
                </div>
                <form action="{{ route('stocks.store') }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Categorie</label>
                            <select name="category_short_name" id="category-select" class="form-control" required>
                                <option value="" id="category-placeholder">-- Kies een categorie --</option>
                                @foreach($categories as $short_name => $cat_id)
                                    <option value="{{ $short_name }}">{{ $short_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Product</label>
                            <input type="text" name="product_name" class="form-control" required placeholder="Voer productnaam in">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Eenheid</label>
                            <select name="eenheid" id="eenheid-select" class="form-control" required>
                                <option value="" id="eenheid-placeholder">-- Kies een eenheid --</option>
                                <!-- Options will be filled by JS -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Ontvangdatum</label>
                            <input type="date" name="received_date" class="form-control" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Uigeleverddatum</label>
                            <input type="date" name="delivered_date" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Aantal Op Voorad</label>
                            <input type="number" name="quantity_in_stock" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Aantal Uigegeven</label>
                            <input type="number" name="quantity_delivered" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Aantal Bijgeleverd</label>
                            <input type="number" name="quantity_supplied" class="form-control" required>
                        </div>
                        <!-- Set is_active always to 1 (Ja) and hide the field -->
                        <input type="hidden" name="is_active" value="1">
                        <!--
                        <div class="form-group">
                            <label class="form-label">Actief</label>
                            <select name="is_active" class="form-control" required>
                                <option value="1">Ja</option>
                                <option value="0">Nee</option>
                            </select>
                        </div>
                        -->
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label class="form-label">Notitie</label>
                            <textarea name="note" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Opslaan
                        </button>
                        <a href="{{ route('stocks.index') }}" class="btn btn-secondary">Annuleren</a>
                    </div>
                </form>
                <script>
                    // Mapping of category short names to allowed units
                    const eenheidOptions = {
                        "Groente & Fruit": ["kg", "stuks", "zak"],
                        "Vleeswaren": ["kg", "pak", "stuks"],
                        "Zuivel": ["ltr", "pak", "stuks"],
                        "Bakkerij": ["stuks", "brood", "zak"],
                        "Dranken": ["ltr", "fles", "pak"],
                        "Pasta & Wereld": ["kg", "pak", "stuks"],
                        "Soepen & Sauzen": ["ltr", "blik", "pak"],
                        "Snoep & Snacks": ["zak", "pak", "stuks"],
                        "Baby & Hygiëne": ["pak", "stuks", "doos"]
                    };

                    document.addEventListener('DOMContentLoaded', function () {
                        const categorySelect = document.getElementById('category-select');
                        const eenheidSelect = document.getElementById('eenheid-select');

                        function updateEenheidOptions() {
                            const selectedCategory = categorySelect.value;
                            eenheidSelect.innerHTML = '<option value="">-- Kies een eenheid --</option>';
                            if (eenheidOptions[selectedCategory]) {
                                eenheidOptions[selectedCategory].forEach(function(unit) {
                                    const opt = document.createElement('option');
                                    opt.value = unit;
                                    opt.textContent = unit;
                                    eenheidSelect.appendChild(opt);
                                });
                            }
                        }

                        categorySelect.addEventListener('change', updateEenheidOptions);
                        updateEenheidOptions();

                        function hidePlaceholder(selectId, placeholderId) {
                            const select = document.getElementById(selectId);
                            const placeholder = document.getElementById(placeholderId);
                            select.addEventListener('change', function () {
                                if (select.value !== "") {
                                    if (placeholder) placeholder.style.display = 'none';
                                } else {
                                    if (placeholder) placeholder.style.display = '';
                                }
                            });
                        }

                        hidePlaceholder('category-select', 'category-placeholder');
                        hidePlaceholder('product-select', 'product-placeholder');
                        hidePlaceholder('eenheid-select', 'eenheid-placeholder');
                    });
                </script>
            </div>
        </div>
    </main>
</body>
</html>
