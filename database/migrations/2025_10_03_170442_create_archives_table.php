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
        Schema::create('archives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // original user ID
            $table->string('name');
            $table->string('username')->nullable();
            $table->string('email');
            $table->enum('role', ['user', 'admin', 'superadmin', 'org'])->default('user');
            $table->enum('status', ['pending', 'approved', 'rejected', 'banned'])->default('pending');
            $table->string('document')->nullable();
            $table->string('profile_image')->nullable();
            $table->timestamp('archived_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archives');
    }
};
