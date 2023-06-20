<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('prestations', function (Blueprint $table) {
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
