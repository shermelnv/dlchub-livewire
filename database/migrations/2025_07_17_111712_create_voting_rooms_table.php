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
Schema::create('voting_rooms', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('description')->nullable();
    // CREATOR ID
    $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
    $table->enum('status', ['Pending', 'Ongoing', 'Closed'])->default('Pending');
    $table->timestamp('start_time')->nullable();
    $table->timestamp('end_time')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voting_rooms');
    }
};
