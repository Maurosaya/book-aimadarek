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
        Schema::create('bookings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tenant_id'); // Will be set by tenancy
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->timestamp('start_at');
            $table->timestamp('end_at');
            $table->integer('party_size')->nullable(); // For restaurants
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'no_show'])->default('pending');
            $table->string('source')->nullable(); // e.g., 'flowise', 'widget', 'admin'
            $table->text('notes')->nullable();
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['tenant_id', 'start_at']);
            $table->index(['tenant_id', 'status']);
            $table->index(['service_id', 'start_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
