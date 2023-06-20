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
            $table->float("pdvFAS")->nullable()->after("quantite");
            $table->float("pdvMensuel")->nullable()->after("pdvFAS");
            $table->integer("parent")->nullable()->default(null)->after("catalogueID");
            $table->integer("customName")->nullable()->default(null)->after("parent");
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
