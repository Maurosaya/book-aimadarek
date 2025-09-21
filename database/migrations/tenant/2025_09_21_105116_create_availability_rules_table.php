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
        Schema::create('availability_rules', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id'); // Will be set by tenancy
            $table->foreignId('location_id')->nullable()->constrained()->onDelete('cascade');
            $table->tinyInteger('day_of_week'); // 0 = Sunday, 1 = Monday, etc.
            $table->time('start_time');
            $table->time('end_time');
            $table->json('exceptions')->nullable(); // Array of date exceptions
            $table->integer('max_covers_slot')->nullable(); // Max covers per time slot
            $table->boolean('active')->default(true);
            $table->timestamps();
            
            $table->index(['tenant_id', 'day_of_week', 'active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availability_rules');
    }
};
