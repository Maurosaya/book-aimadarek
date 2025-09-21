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
        Schema::create('message_templates', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id'); // Will be set by tenancy
            $table->enum('channel', ['EMAIL', 'SMS', 'WHATSAPP']);
            $table->string('slug'); // e.g., 'booking_confirmed', 'booking_cancelled'
            $table->json('subject'); // Translatable field
            $table->json('content'); // Translatable field
            $table->boolean('active')->default(true);
            $table->timestamps();
            
            $table->unique(['tenant_id', 'channel', 'slug']);
            $table->index(['tenant_id', 'active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_templates');
    }
};
