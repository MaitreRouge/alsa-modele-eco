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
        Schema::table('devis', function (Blueprint $table) {
            $table->float("prixBrut")->nullable()->after("quantite");
            $table->float("prixMensuel")->nullable()->after("prixBrut");
            $table->float("prixFraisInstalation")->nullable()->after("prixMensuel");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
