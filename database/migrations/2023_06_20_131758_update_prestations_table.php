<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('prestations', function (Blueprint $table) {

            /**
             * Note du premier dev (Marc MAGUEUR) :
             * Avec laravel 10, je rencontrais des problèmes pour avoir une clée primaire composée (id et version) dans
             * la même migration que la création de la table.
             * Après plusieures heures de debug, j'ai trouvé cette solution, avoir la création de la table dans une
             * migration et la définition de la clée primaire composée dans une autre.
             */

            $table->dropPrimary(); // Supprime la clé primaire existante
            $table->primary(['id', 'version']); // Définit la clé primaire composée sur les colonnes `id` et `version`
        });
    }

    public function down()
    {
        Schema::table('prestations', function (Blueprint $table) {
            $table->dropPrimary(); // Supprime la clé primaire composée
            $table->primary('id'); // Rétablit la clé primaire sur la colonne `id`
        });
    }
};
