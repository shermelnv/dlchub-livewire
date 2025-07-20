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
    Schema::create('advertisements', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('category'); // internship, event, job, scholarship
        $table->text('description')->nullable();
        $table->string('organization')->nullable();
        $table->string('location')->nullable();
        $table->date('event_date')->nullable();
        $table->time('time')->nullable();
        $table->date('deadline')->nullable();
        $table->string('tags')->nullable(); // comma separated string
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
