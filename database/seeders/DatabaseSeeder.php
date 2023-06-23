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
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * @throws Exception
     */
    public function run(): void
    {
        //Création d'un administrateur, ce compte sera supprimé lors de la création du premier (vrai) administrateur
        User::factory()->create([
            "email" => "root@alsatis.com",
            "password" => password_hash("password", PASSWORD_BCRYPT),
            "prenom" => "root",
            "nom" => "administrator",
            "role" => "admin",
        ]);

        Categorie::factory()->create([
            "label" => "DATA"
        ]);

        Categorie::factory()->create([
            "label" => "TELEPHONIE"
        ]);

        Categorie::factory()->create([
            "label" => "SERVICES"
        ]);

        $prestation = new Prestation();
        $prestation->label = "Autre prestation - Nom à modifier";
        $prestation->note = "Merci de modifier le nom de le nom de cette prestation";
        $prestation->id = 0; //Marche pas pour une raison que je ne comprehends pas
        $prestation->version = 1;
        $prestation->idCategorie = 0;
        $prestation->save();

        DB::update("UPDATE prestations SET id = 0  WHERE label = 'Autre prestation - Nom à modifier'");

        $this->demo(true);

        Prestation::factory()->create([
            "id" => 1,
            "label" => "Ouverure d'un nouveau site - FAS",
            "version" => 1,
            "prixFAS" => 200,
            "note" => "Cette prestation correspond au FAS d'un nouveau site. Il est conseillé de ne pas modifier le prix directement sur cette prestation mais de créer une prestation personnalisé et de mettre un prix negatif (ou positif)",
            "idCategorie" => 0
        ]);

    }

    /**
     * Cette fonction est utilisée pour tester la base de données & pour faire de la démonstration.
     *
     * Elle génère :
     *  - 4 Utilisateurs demo (non-fonctionnels)
     *  - 0~4 Tokens (Les tokens son générés aléatoirement pour chaque utilisateur demo)
     *  - 3 Catégories parents (Une par thème (data/tel/services))
     *  - 1 Catégories de test (par parent)
     *  - 4 Prestations de test (par parent) dont:
     *      - 1 Prestation classique
     *      - 1 Option avec un engagement minimum
     *      - 1 Option avec un engagement compris entre deux valeurs
     *      - 1 Prestation avec un engagement maximal
     *  - 1 Client
     *
     * @param bool $isEnabled Si jamais non renseigné ou réglé à false, les données de test ne seront jamais entrés dans la base de donnée.
     * @return void
     * @throws Exception
     */
    private function demo(bool $isEnabled = false): void
    {
        if (!$isEnabled) return;

        // 4 Utilisateurs demo (non-fonctionnels)
        User::factory(4)->create();

        // 0~4 Tokens (Les tokens son générés aléatoirement pour chaque utilisateur demo)
        for ($i = 2; $i < 6; $i++) {
            for ($j = 0; $j < random_int(0, 1); $j++) {
                UserToken::factory()->create([
                    "token" => Str::random(120),
                    "uid" => $i,
                    "validUntil" => "2000-01-01 00:00:00",
                    "lastSeen" => Carbon::now()->subMinutes(random_int(0, 10)),
                ]);
            }
        }

        for ($i = 1; $i < 4; $i++) {
            Categorie::factory()->create([
                "label" => "Parent test",
                "parentID" => $i,
                "note" => "Cette catégorie n'est présente qu'a des fins de développement, de test et de démonstration. Si elle est encore présente cela veut dire que l'application est encore en mode de développement. Merci de le faire remonter à un dévloppeur pour qu'il change d'environement !"
            ]);
        }

        for ($i = 0; $i < 3; $i++) {
            Categorie::factory()->create([
                "label" => "Catégorie de test",
                "parentID" => $i + 4,
                "note" => "Cette catégorie n'est présente qu'a des fins de développement, de test et de démonstration. Si elle est encore présente cela veut dire que l'application est encore en mode de développement. Merci de le faire remonter à un dévloppeur pour qu'il change d'environement !"
            ]);

            for ($j = 0; $j < 4; $j++) {
                $p = Prestation::factory()->create([
                    "version" => 1,
                    "label" => "Prestation de test #$j",
                    "prixMensuel" => random_int(0, 1000) / 100,
                    "prixFAS" => random_int(0, 1000) / 100,
                    "note" => "Cette prestation n'est présente qu'a des fins de développement, de test et de démonstration. Si elle est encore présente cela veut dire que l'application est encore en mode de développement. Merci de le faire remonter à un dévloppeur pour qu'il change d'environement !",
                    "minEngagement" => ($j >= 1 and $j <= 2) ? 24 : null,
                    "maxEngagement" => ($j >= 2) ? 36 : null,
                    "idCategorie" => $i + 4 + 3
                ]);

                $p = Prestation::orderby("id", "desc")->first();

                if ($j >= 1 and $j <= 2) {
                    Option::factory()->create([
                        "option_id" => $p->id,
                        "prestation_id" => 2 + (4 * $i),
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
