<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Categorie;
use App\Models\Client;
use App\Models\Devis;
use App\Models\Option;
use App\Models\Prestation;
use App\Models\User;
use App\Models\UserToken;
use Carbon\Carbon;
use Database\Factories\CategorieFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::factory()->create([
           "email" => "root@alsatis.com",
           "password" => password_hash("password", PASSWORD_BCRYPT),
           "prenom" => "root",
            "nom" => "administrator",
            "role" => "admin",
        ]);

        User::factory(4)->create();

        for ($i = 1; $i < 6; $i++){
            for($j = 0; $j < random_int(0,1); $j++){
                UserToken::factory()->create([
                    "token" => Str::random(120),
                    "uid" => $i,
                    "validUntil" => "2000-01-01 00:00:00",
                    "lastSeen" => Carbon::now()->subMinutes(random_int(0,10)),
                ]);
            }
        }

        Categorie::factory()->create([
            "label" => "DATA"
        ]);

        Categorie::factory()->create([
            "label" => "TELEPHONIE"
        ]);

        Categorie::factory()->create([
            "label" => "SERVICES"
        ]);

        for ($i = 1; $i < 4; $i++){
            Categorie::factory()->create([
                "label" => "Parent test",
                "parentID" => $i,
                "note" => "Cette catégorie n'est présente qu'a des fins de développement, de test et de démonstration. Si elle est encore présente cela veut dire que l'application est encore en mode de développement. Merci de le faire remonter à un dévloppeur pour qu'il change d'environement !"
            ]);
        }

        for ($i = 0; $i < 3; $i++){
            Categorie::factory()->create([
                "label" => "Catégorie de test",
                "parentID" => $i+4,
                "note" => "Cette catégorie n'est présente qu'a des fins de développement, de test et de démonstration. Si elle est encore présente cela veut dire que l'application est encore en mode de développement. Merci de le faire remonter à un dévloppeur pour qu'il change d'environement !"
            ]);

            for ($j = 0; $j < 4; $j++){
                $p = Prestation::factory()->create([
                    "version" => 1,
                    "label" => "Prestation de test #$j",
                    "prixMensuel" => random_int(0,1000) / 100,
                    "prixFAS" => random_int(0,1000) / 100,
                    "note" => "Cette prestation n'est présente qu'a des fins de développement, de test et de démonstration. Si elle est encore présente cela veut dire que l'application est encore en mode de développement. Merci de le faire remonter à un dévloppeur pour qu'il change d'environement !",
                    "minEngagement" => ($j >= 1 and $j <=2)?24:null,
                    "maxEngagement" => ($j >= 2)?36:null,
                    "idCategorie" => $i+4+3
                ]);

                if ($j >= 1 and $j <=2) {
                    Option::factory()->create([
                        "option_id" => $p->id,
                        "prestation_id" => 1 + (4 * $i),
                        "obligatoire" => 0
                    ]);
                }
            }
        }

        Client::factory()->create([
            "raisonSociale" => "SAS",
            "nom" => "demo client",
            "RPAP" => 123456,
            "commercial" => 2,
            "agence" => 2010,
            "nbSites" => 1,
            "nvClient" => true,
            "nvSite" => true,
            "upgrade" => false,
            "engagement" => 12
        ]);
    }
}
