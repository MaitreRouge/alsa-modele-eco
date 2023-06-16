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
        Schema::table('prestations', function (Blueprint $table) {
            $table->integer("minEngagement")->nullable()->default(null)->after("note");
            $table->integer("maxEngagement")->nullable()->default(null)->after("minEngagement");
            $table->integer("disabled")->nullable()->default(null)->after("maxEngagement");
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
