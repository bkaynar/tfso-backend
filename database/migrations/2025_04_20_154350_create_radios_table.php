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
        Schema::create('radios', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('image')->nullable(); // Kapak görseli
            $table->string('stream_url');
            $table->boolean('is_premium')->default(false); // Premium radyolar için
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radios');
    }
};
