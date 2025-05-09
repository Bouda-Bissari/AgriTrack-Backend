<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;

class LandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cultureTypes = ['Maïs', 'Manioc', 'Igname', 'Mil', 'Sorgho', 'Riz', 'Cacao', 'Café', 'Coton', 'Soja', 'Arachide', 'Haricot'];
        $cities = ['Lomé', 'Sokodé', 'Kara', 'Kpalimé', 'Atakpamé', 'Bassar', 'Tsévié', 'Aného', 'Mango', 'Dapaong'];
        $statuts = ['En culture', 'Récolte', 'En jachère'];
        
        // Récupération des users avec le rôle landOwner
        $landOwners = User::where('role', 'landOwner')->get();
        
        foreach ($landOwners as $owner) {
            // Création de 10 à 15 terrains pour chaque propriétaire
            $landCount = rand(10, 15);
            
            for ($i = 0; $i < $landCount; $i++) {
                $city = $cities[array_rand($cities)];
                $cultureType = $cultureTypes[array_rand($cultureTypes)];
                
                // Coordonnées approximatives du Togo
                $latitude = 6.0 + (rand(0, 300) / 100); // Entre 6.0 et 9.0
                $longitude = 0.5 + (rand(0, 200) / 100); // Entre 0.5 et 2.5
                
                DB::table('lands')->insert([
                    'name' => "Terre de " . $cultureType . " " . ($i + 1) . " de " . $owner->firstName,
                    'city' => $city,
                    'cultureType' => $cultureType,
                    'area' => rand(1, 50) + (rand(0, 99) / 100), // Entre 1 et 50 hectares
                    'ownershipdoc' => 'DOC-' . Str::upper(Str::random(8)),
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'statut' => $statuts[array_rand($statuts)],
                    'user_id' => $owner->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}