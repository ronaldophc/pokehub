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
        Schema::table('pokemons', function (Blueprint $table) {
            $table->string('name', 60)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pokemons', function (Blueprint $table) {
            $table->string('name', 60)->nullable(false)->change();
        });
    }
};
