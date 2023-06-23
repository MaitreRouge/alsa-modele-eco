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
        Schema::create('devis', function (Blueprint $table) {
            $table->id();
            $table->integer("version");
            $table->float("quantite")->nullable();
            $table->float("pdvFAS")->nullable();
            $table->float("pdvMensuel")->nullable();
            $table->integer("optLinked")->nullable()->default(null);
            $table->integer("parent")->nullable()->default(null);
            $table->string("customName", 100)->nullable()->default(null);
            $table->integer("clientID");
            $table->integer("catalogueID");
            $table->boolean("conflict")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devis');
    }
};
