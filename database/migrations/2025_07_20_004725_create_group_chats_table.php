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
    Schema::create('group_chats', function (Blueprint $table) {
        $table->id();
        $table->foreignId('group_owner_id')->constrained('users')->onDelete('cascade');
        $table->string('group_profile')->nullable();
        $table->string('name');
        $table->text('description')->nullable();
        $table->string('group_code')->unique();
        $table->timestamp('expires_at');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_chats');
    }
};
