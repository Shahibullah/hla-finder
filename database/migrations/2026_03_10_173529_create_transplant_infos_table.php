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
        Schema::create('transplant_infos', function (Blueprint $table) {
            $table->id();

            // Foreign keys (users table)
            $table->unsignedBigInteger('donor_id');
            $table->unsignedBigInteger('receiver_id');
            $table->unsignedBigInteger('lab_id')->nullable();

            // Transplant details
            $table->date('transplant_date');
            $table->string('organ_type');
            $table->string('outcome'); // e.g. Success / Failed
            $table->text('condition_notes')->nullable();

            $table->timestamps();

            // Optional: Foreign key constraints
            $table->foreign('donor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('lab_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transplant_infos');
    }
};