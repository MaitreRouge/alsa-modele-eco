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
        Schema::create('prestations', function (Blueprint $table) {
            $table->id();
            $table->integer("version");
            $table->string("label", 250);
            $table->float("prixBrut")->default(0.00)->nullable();
            $table->float("prixMensuel")->default(0.00)->nullable();
            $table->float("prixFraisInstalation")->default(0.00)->nullable();
            $table->boolean("needPrixVente")->default(false);
            $table->integer("idCategorie");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestations');
    }
};
