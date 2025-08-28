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
  Schema::create('feeds', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
    $table->foreignId('org_id')->nullable()->constrained('orgs')->onDelete('cascade');



    $table->string('type')->nullable();
    $table->string('title');
    $table->enum('privacy', ['public', 'private'])->default('public');
    $table->text('content');
    $table->string('photo_url')->nullable();
    $table->date('published_at')->nullable(); // for sorting/filter
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feeds');
    }
};
