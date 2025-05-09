<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Liste de noms togolais pour les utilisateurs
        $firstNames = [
            'Kodjo', 'Kokou', 'Koffi', 'Komlan', 'Kossi', 'Komi', 'Kwame', 'Kwasi',
            'Abla', 'Afi', 'Akossiwa', 'Ama', 'Ami', 'Amivi', 'Adjoa', 'Ayele',
            'Mensah', 'Mawuli', 'Mawuena', 'Notsé', 'Nuku', 'Sena', 'Sénam', 'Sika',
            'Folly', 'Foli', 'Edem', 'Dzifa', 'Dela', 'Dodzi', 'Afiwa', 'Yawa'
        ];

        $lastNames = [
            'Adjavon', 'Agbodjan', 'Akolly', 'Akakpo', 'Atitso', 'Agbeko', 'Amegan',
            'Ayéfouni', 'Dogbé', 'Dossou', 'Etse', 'Gaba', 'Gbedemah', 'Klutse',
            'Koudawo', 'Lawson', 'Mensah', 'Olympio', 'Sewordor', 'Sedzro',
            'Soglo', 'Tagba', 'Tamakloé', 'Wilson', 'Zinzindohoue', 'Zotchi'
        ];

        // Création de 25 utilisateurs (15 landOwner, 5 admin, 5 worker)
        $roles = [
            'landOwner' => 15,
            'admin' => 5,
            'worker' => 10
        ];

        $userCount = 1;
        
        foreach ($roles as $role => $count) {
            for ($i = 0; $i < $count; $i++) {
                $firstName = $firstNames[array_rand($firstNames)];
                $lastName = $lastNames[array_rand($lastNames)];
                $email = strtolower($firstName . '.' . $lastName . $userCount . '@example.com');
                
                DB::table('users')->insert([
                    'firstName' => $firstName,
                    'lastName' => $lastName,
                    'bio' => 'Agriculteur togolais avec expérience dans la culture de ' . ['maïs', 'manioc', 'igname', 'mil', 'sorgho', 'riz', 'cacao', 'café'][array_rand(['maïs', 'manioc', 'igname', 'mil', 'sorgho', 'riz', 'cacao', 'café'])],
                    'email' => $email,
                    'phoneNumber' => '+228' . rand(90, 99) . rand(100000, 999999),
                    'role' => $role,
                    'email_verified_at' => now(),
                    'is_blocked' => false,
                    'password' => Hash::make('password'),
                    'remember_token' => Str::random(10),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $userCount++;
            }
        }
    }
}
