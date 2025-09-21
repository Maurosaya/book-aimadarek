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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['TABLE', 'STAFF', 'ROOM', 'CHAIR', 'EQUIPMENT']);
            $table->json('label'); // Translatable field
            $table->integer('capacity')->nullable(); // For tables, chairs, etc.
            $table->json('combinable_with')->nullable(); // Array of resource types that can be combined
            $table->boolean('active')->default(true);
            $table->timestamps();
            
            $table->index(['location_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
