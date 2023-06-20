<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string("raisonSociale", 100);
            $table->string("nom", 100);
            $table->string("RPAP", 100);
            $table->date("dateSignature")->nullable();
            $table->date("datePremiereConnexion")->nullable();
            $table->integer("commercial");
            $table->integer("agence");
            $table->integer("nbSites");
            $table->integer("engagement");
            $table->boolean("upgrade");
            $table->boolean("nvSite");
            $table->boolean("nvClient");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
