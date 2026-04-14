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
        Schema::create('google_reviews_cache', function (Blueprint $table) {
            $table->id();
            $table->string('place_id')->unique();
            $table->json('reviews_data'); // Store the reviews as JSON
            $table->timestamp('fetched_at'); // When data was fetched from Google
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('google_reviews_cache');
    }
};
