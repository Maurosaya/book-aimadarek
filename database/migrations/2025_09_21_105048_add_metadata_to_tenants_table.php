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
        Schema::table('tenants', function (Blueprint $table) {
            // Add metadata columns for tenant configuration
            $table->string('brand_name')->nullable();
            $table->string('default_locale', 5)->default('en');
            $table->json('supported_locales')->default('["en"]');
            $table->string('timezone')->default('UTC');
            $table->json('settings')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['brand_name', 'default_locale', 'supported_locales', 'timezone', 'settings']);
        });
    }
};
