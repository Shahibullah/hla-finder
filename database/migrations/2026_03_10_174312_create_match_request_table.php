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
        Schema::create('match_requests', function (Blueprint $table) {
            $table->id();

            // Linking the two parties
            $table->foreignId('receiver_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('donor_id')->constrained('users')->cascadeOnDelete();

            // Using unsignedTinyInteger is perfect for 0-100 values
            $table->unsignedTinyInteger('match_percentage');

            $table->enum('status', [
                'pending',
                'accepted',
                'rejected',
                'cancelled'
            ])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_requests');
    }
};