<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('market_listings', function (Blueprint $table) {
            $table->string('price', 100)->change();
            $table->string('sprite_url')->nullable()->after('species');
        });
    }

    public function down(): void
    {
        Schema::table('market_listings', function (Blueprint $table) {
            $table->unsignedBigInteger('price')->change();
            $table->dropColumn('sprite_url');
        });
    }
};
