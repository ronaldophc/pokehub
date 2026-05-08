<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('market_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('species', 100);
            $table->string('name', 100)->nullable();
            $table->unsignedSmallInteger('level');
            $table->boolean('is_shiny')->default(false);
            $table->string('tm', 100)->nullable();
            $table->string('held_x_name', 50)->nullable();
            $table->unsignedTinyInteger('held_x_tier')->nullable();
            $table->string('held_y_name', 50)->nullable();
            $table->unsignedTinyInteger('held_y_tier')->nullable();
            $table->unsignedBigInteger('price');
            $table->string('server', 50);
            $table->string('contact_nick', 100);
            $table->string('contact_discord', 100)->nullable();
            $table->text('notes')->nullable();
            $table->string('screenshot_path')->nullable();
            $table->enum('status', ['active', 'sold', 'expired'])->default('active');
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->index(['status', 'expires_at']);
            $table->index('species');
            $table->index('server');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('market_listings');
    }
};
