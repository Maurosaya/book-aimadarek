<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Tenant;

/**
 * Update Users With Tenant Data Command
 * 
 * Updates existing users with tenant_id and role information
 * Based on their email domain or manual mapping
 */
class UpdateUsersWithTenantData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:update-tenant-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update existing users with tenant_id and role information';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Updating users with tenant data...');

        // Map of email domains to tenant IDs
        $emailToTenantMap = [
            'ranch@demo.com' => 'ranch',
            'beerta@demo.com' => 'beerta-barbers',
            'glow@demo.com' => 'glow-beauty',
            'smile@demo.com' => 'smile-dental',
        ];

        $updated = 0;

        foreach ($emailToTenantMap as $email => $tenantId) {
            $user = User::where('email', $email)->first();
            
            if ($user) {
                // Check if user already has tenant data
                if (!$user->tenant_id) {
                    $user->update([
                        'tenant_id' => $tenantId,
                        'role' => User::ROLE_OWNER,
                        'active' => true,
                    ]);
                    
                    $this->info("✅ Updated user {$user->name} ({$user->email}) with tenant {$tenantId}");
                    $updated++;
                } else {
                    $this->warn("⚠️  User {$user->name} ({$user->email}) already has tenant data");
                }
            } else {
                $this->error("❌ User with email {$email} not found");
            }
        }

        // Update any remaining users without tenant_id (set as inactive)
        $usersWithoutTenant = User::whereNull('tenant_id')->get();
        
        foreach ($usersWithoutTenant as $user) {
            $user->update([
                'active' => false,
                'role' => null,
            ]);
            $this->warn("⚠️  Deactivated user {$user->name} ({$user->email}) - no tenant assigned");
        }

        $this->info("🎉 Updated {$updated} users with tenant data");
        
        return Command::SUCCESS;
    }
}