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
            $table->boolean('is_shiny')->default(false)->after('sprite_url');
            $table->string('tm', 100)->nullable()->after('notes');
            $table->string('held_x_name', 50)->nullable()->after('tm');
            $table->unsignedTinyInteger('held_x_tier')->nullable()->after('held_x_name');
            $table->string('held_y_name', 50)->nullable()->after('held_x_tier');
            $table->unsignedTinyInteger('held_y_tier')->nullable()->after('held_y_name');
        });
    }

    public function down(): void
    {
        Schema::table('pokemons', function (Blueprint $table) {
            $table->dropColumn(['is_shiny', 'tm', 'held_x_name', 'held_x_tier', 'held_y_name', 'held_y_tier']);
        });
    }
};
