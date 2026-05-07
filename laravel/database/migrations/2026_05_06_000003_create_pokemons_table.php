<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pokemons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('house_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('species');
            $table->unsignedSmallInteger('level')->default(1);
            $table->string('sprite_url')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('current_holder_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('held_since')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pokemons');
    }
};
