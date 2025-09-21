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
        Schema::table('users', function (Blueprint $table) {
            // Add tenant relationship
            $table->string('tenant_id')->nullable()->after('id');
            $table->index(['tenant_id']);
            
            // Add role field for tenant users
            $table->enum('role', ['owner', 'manager', 'staff'])->nullable()->after('tenant_id');
            
            // Add user status
            $table->boolean('active')->default(true)->after('role');
            
            // Add last login tracking
            $table->timestamp('last_login_at')->nullable()->after('active');
            
            // Add user preferences
            $table->json('preferences')->nullable()->after('last_login_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['tenant_id']);
            $table->dropColumn([
                'tenant_id',
                'role',
                'active',
                'last_login_at',
                'preferences'
            ]);
        });
    }
};