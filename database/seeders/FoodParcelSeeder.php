<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FoodParcelSeeder extends Seeder
{
    public function run(): void
    {
        // Get all available stocks and customers to ensure we use valid IDs
        $stockIds = DB::table('stocks')->pluck('id')->toArray();
        $customerIds = DB::table('customers')->pluck('id')->toArray();

        if (empty($stockIds) || empty($customerIds)) {
            $this->command->warn('Geen stocks of klanten gevonden. Zorg dat deze eerst worden aangemaakt.');
            return;
        }

        $foodParcels = [];

        // Maak realistische voedselpakketten met variatie in datums en statussen
        $notes = [
            'Standaard weekpakket voor gezin van 3 personen',
            'Extra groenten toegevoegd vanwege speciale voedingsbehoefte',
            'Glutenvrij pakket - geen brood toegevoegd',
            'Pakket voor alleenstaande - aangepaste portiegrootte',
            'Feestdagenpakket met extra producten',
            'Basis pakket zonder zuivel vanwege lactose-intolerantie',
            'Uitgebreid pakket voor groot gezin (6 personen)',
            'Noodpakket - acute voedselcrisis',
            'Seizoensgebonden pakket met verse groenten',
            'Pakket aangepast voor diabetische klant',
            null, // Sommige pakketten hebben geen speciale notitie
            null,
        ];

        // Maak 20 realistische voedselpakketten
        for ($i = 0; $i < 20; $i++) {
            $stockId = $stockIds[array_rand($stockIds)];
            $customerId = $customerIds[array_rand($customerIds)];

            // Varieer de aanmaakdatums over de laatste 3 maanden
            $createdAt = Carbon::now()->subDays(rand(1, 90));

            // 85% van de pakketten zijn actief, 15% niet actief
            $isActive = rand(1, 100) <= 85;

            // Selecteer een willekeurige notitie
            $note = $notes[array_rand($notes)];

            $foodParcels[] = [
                'stock_id' => $stockId,
                'customer_id' => $customerId,
                'is_active' => $isActive,
                'note' => $note,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        }

        // Voeg ook enkele specifieke voorbeelden toe voor demo doeleinden
        $demoExamples = [
            [
                'stock_id' => $stockIds[0], // Groenten (tomaten)
                'customer_id' => $customerIds[0], // Familie de Boer
                'is_active' => true,
                'note' => 'Wekelijks pakket - Familie de Boer houdt van verse groenten',
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(7),
            ],
            [
                'stock_id' => $stockIds[1], // Fruit (bananen)
                'customer_id' => $customerIds[1], // Familie van der Berg
                'is_active' => true,
                'note' => 'Extra fruit voor de kinderen in het gezin',
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'stock_id' => $stockIds[2], // Zuivel (melk)
                'customer_id' => $customerIds[2], // Familie Hassan
                'is_active' => true,
                'note' => 'Halal gecertificeerde zuivelproducten',
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'stock_id' => $stockIds[3], // Bakkerij (brood)
                'customer_id' => $customerIds[3], // Familie Janssen
                'is_active' => false,
                'note' => 'Pakket geannuleerd - klant tijdelijk niet beschikbaar',
                'created_at' => Carbon::now()->subDays(14),
                'updated_at' => Carbon::now()->subDays(10),
            ],
        ];

        // Voeg demo voorbeelden toe aan de lijst
        $foodParcels = array_merge($foodParcels, $demoExamples);

        // Voeg alle voedselpakketten toe aan de database
        DB::table('food_parcels')->insert($foodParcels);

        $activeCount = collect($foodParcels)->where('is_active', true)->count();
        $inactiveCount = count($foodParcels) - $activeCount;

        $this->command->info('Nederlandse voedselpakket demo data succesvol aangemaakt!');
        $this->command->info('✓ Totaal voedselpakketten: ' . count($foodParcels));
        $this->command->info('✓ Actieve pakketten: ' . $activeCount);
        $this->command->info('✓ Inactieve pakketten: ' . $inactiveCount);
        $this->command->info('✓ Gekoppeld aan ' . count($stockIds) . ' verschillende voorraden');
        $this->command->info('✓ Verdeeld over ' . count($customerIds) . ' klanten');
    }
}
