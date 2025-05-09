<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Land;

class InterventionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['Semis', 'Arrosage', 'Fertilisation', 'Recolte', 'Traitement'];
        
        $products = [
            'Semis' => ['Semence de maïs hybride', 'Semence de riz', 'Semence de coton', 'Semence d\'arachide', 'Semence de soja'],
            'Arrosage' => ['Eau de pluie', 'Eau de puits', 'Eau de rivière', 'Système goutte à goutte'],
            'Fertilisation' => ['NPK 15-15-15', 'Urée', 'Fumier organique', 'Compost', 'Engrais foliaire'],
            'Recolte' => ['Manuelle', 'Mécanisée', 'Semi-mécanisée'],
            'Traitement' => ['Fongicide', 'Insecticide', 'Herbicide', 'Bio-pesticide', 'Traitement préventif']
        ];
        
        $units = [
            'Semis' => ['kg', 'sac', 'g'],
            'Arrosage' => ['L', 'm³', 'barrique'],
            'Fertilisation' => ['kg', 'sac', 'tonne'],
            'Recolte' => ['kg', 'tonne', 'sac'],
            'Traitement' => ['L', 'mL', 'kg']
        ];
        
        // Récupérer toutes les terres
        $lands = Land::all();
        
        foreach ($lands as $land) {
            // 13 interventions par terre en moyenne
            $interventionCount = 13;
            
            // Calculer une date de début (entre 1 et 12 mois dans le passé)
            $startDate = now()->subMonths(rand(1, 12))->subDays(rand(1, 30));
            
            for ($i = 0; $i < $interventionCount; $i++) {
                $type = $types[array_rand($types)];
                $productName = $products[$type][array_rand($products[$type])];
                $unit = $units[$type][array_rand($units[$type])];
                
                // Date progressive pour les interventions
                $interventionDate = clone $startDate;
                $interventionDate->addDays($i * rand(3, 15));
                
                // Les interventions récentes ont moins de chance d'être complétées
                $isDone = $interventionDate->lt(now()) ? (rand(0, 100) > 20) : false;
                
                DB::table('interventions')->insert([
                    'title' => $type . ' de ' . $land->cultureType . ' - ' . $interventionDate->format('d/m/Y'),
                    'type' => $type,
                    'isDone' => $isDone,
                    'quantity' => rand(10, 1000) + (rand(0, 99) / 100),
                    'unit' => $unit,
                    'product_name' => $productName,
                    'description' => "Intervention de $type sur la terre {$land->name}. Utilisation de $productName.",
                    'land_id' => $land->id,
                    'created_at' => $interventionDate,
                    'updated_at' => $interventionDate,
                ]);
            }
        }
    }
}
