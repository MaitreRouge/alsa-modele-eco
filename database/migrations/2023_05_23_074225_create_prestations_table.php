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
            $table->increments("id")->index();;
            $table->integer("version");
            $table->string("label", 250);
            $table->float("prixMensuel")->default(null)->nullable();
            $table->float("prixFAS")->default(null)->nullable();
            $table->text("note")->nullable()->default(null);
            $table->integer("minEngagement")->nullable()->default(null);
            $table->integer("maxEngagement")->nullable()->default(null);
            $table->integer("disabled")->nullable()->default(null);
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
