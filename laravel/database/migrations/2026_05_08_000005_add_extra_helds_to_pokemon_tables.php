<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pokemons', function (Blueprint $table) {
            $table->json('extra_helds')->nullable()->after('held_y_tier');
        });

        Schema::table('market_listings', function (Blueprint $table) {
            $table->json('extra_helds')->nullable()->after('held_y_tier');
        });
    }

    public function down(): void
    {
        Schema::table('pokemons', fn (Blueprint $t) => $t->dropColumn('extra_helds'));
        Schema::table('market_listings', fn (Blueprint $t) => $t->dropColumn('extra_helds'));
    }
};
