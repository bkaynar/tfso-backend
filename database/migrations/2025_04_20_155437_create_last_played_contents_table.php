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
        Schema::create('last_played_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('last_played_set_id')->nullable()->constrained('sets')->onDelete('set null');
            $table->foreignId('last_played_track_id')->nullable()->constrained('tracks')->onDelete('set null');
            $table->foreignId('last_played_radio_id')->nullable()->constrained('radios')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('last_played_contents');
    }
};
