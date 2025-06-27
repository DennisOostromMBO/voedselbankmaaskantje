<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klant toevoegen</title>
    @vite(['public/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">
    <div class="max-w-2xl mx-auto mt-10 px-4">
        <h1 class="text-2xl font-bold mb-6 text-blue-900">Klant toevoegen</h1>
        @if ($errors->any())
            <div class="mb-6">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        <div class="bg-white rounded-xl shadow border border-blue-100 p-6">
            <form method="POST" action="{{ route('customers.store') }}">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1 text-blue-900">Voornaam</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" required
                            class="w-full border border-blue-200 rounded px-2 py-1 text-gray-900 focus:ring focus:ring-blue-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1 text-blue-900">Tussenvoegsel</label>
                        <input type="text" name="infix" value="{{ old('infix') }}"
                            class="w-full border border-blue-200 rounded px-2 py-1 text-gray-900 focus:ring focus:ring-blue-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1 text-blue-900">Achternaam</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" required
                            class="w-full border border-blue-200 rounded px-2 py-1 text-gray-900 focus:ring focus:ring-blue-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1 text-blue-900">Straatnaam</label>
                        <input type="text" name="street" value="{{ old('street') }}" required
                            class="w-full border border-blue-200 rounded px-2 py-1 text-gray-900 focus:ring focus:ring-blue-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1 text-blue-900">Huisnummer</label>
                        <input type="text" name="house_number" value="{{ old('house_number') }}" required
                            class="w-full border border-blue-200 rounded px-2 py-1 text-gray-900 focus:ring focus:ring-blue-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1 text-blue-900">Toevoeging</label>
                        <input type="text" name="addition" value="{{ old('addition') }}"
                            class="w-full border border-blue-200 rounded px-2 py-1 text-gray-900 focus:ring focus:ring-blue-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1 text-blue-900">Postcode</label>
                        <input type="text" name="postcode" value="{{ old('postcode') }}" required
                            class="w-full border border-blue-200 rounded px-2 py-1 text-gray-900 focus:ring focus:ring-blue-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1 text-blue-900">Plaats</label>
                        <input type="text" name="city" value="{{ old('city') }}" required
                            class="w-full border border-blue-200 rounded px-2 py-1 text-gray-900 focus:ring focus:ring-blue-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1 text-blue-900">Mobiel</label>
                        <input type="text" name="mobile" value="{{ old('mobile') }}" required
                            class="w-full border border-blue-200 rounded px-2 py-1 text-gray-900 focus:ring focus:ring-blue-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1 text-blue-900">E-mail</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full border border-blue-200 rounded px-2 py-1 text-gray-900 focus:ring focus:ring-blue-200">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1 text-blue-900">Leeftijd</label>
                        <input type="number" name="age" value="{{ old('age') }}" required
                            class="w-full border border-blue-200 rounded px-2 py-1 text-gray-900 focus:ring focus:ring-blue-200">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1 text-blue-900">Wens</label>
                        <select name="wish" class="w-full border border-blue-200 rounded px-2 py-1 text-gray-900 focus:ring focus:ring-blue-200">
                            <option value="geen" {{ old('wish') == 'geen' ? 'selected' : '' }}>Geen</option>
                            <option value="Vegetarisch" {{ old('wish') == 'Vegetarisch' ? 'selected' : '' }}>Vegetarisch</option>
                            <option value="Veganistisch" {{ old('wish') == 'Veganistisch' ? 'selected' : '' }}>Veganistisch</option>
                            <option value="Geen varkensvlees" {{ old('wish') == 'Geen varkensvlees' ? 'selected' : '' }}>Geen varkensvlees</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('customers.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Annuleren</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Opslaan</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
