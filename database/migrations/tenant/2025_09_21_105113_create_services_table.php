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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id'); // Will be set by tenancy
            $table->json('name'); // Translatable field
            $table->integer('duration_min'); // Duration in minutes
            $table->integer('buffer_before_min')->default(0); // Buffer before service
            $table->integer('buffer_after_min')->default(0); // Buffer after service
            $table->integer('price_cents')->nullable(); // Price in cents
            $table->json('required_resource_types'); // Array of required resource types
            $table->boolean('active')->default(true);
            $table->timestamps();
            
            $table->index(['tenant_id', 'active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
