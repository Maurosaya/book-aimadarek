<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Create API Token Command
 * 
 * Creates a new user and API token for testing the API
 */
class CreateApiToken extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'api:create-token 
                            {--tenant= : Tenant ID to create token for}
                            {--name= : User name}
                            {--email= : User email}
                            {--password= : User password}';

    /**
     * The console command description.
     */
    protected $description = 'Create a new API token for testing';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $tenantId = $this->option('tenant');
        $name = $this->option('name') ?? 'API User';
        $email = $this->option('email') ?? 'api@example.com';
        $password = $this->option('password') ?? Str::random(12);

        if (!$tenantId) {
            $this->error('Tenant ID is required. Use --tenant=your-tenant-id');
            return 1;
        }

        // Create user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'tenant_id' => $tenantId,
            'email_verified_at' => now(),
        ]);

        // Create API token
        $token = $user->createToken('api-token', ['*']);

        $this->info('API Token created successfully!');
        $this->line('');
        $this->line('User Details:');
        $this->line('  Name: ' . $user->name);
        $this->line('  Email: ' . $user->email);
        $this->line('  Tenant ID: ' . $user->tenant_id);
        $this->line('');
        $this->line('API Token:');
        $this->line('  ' . $token->plainTextToken);
        $this->line('');
        $this->line('Usage Example:');
        $this->line('  curl -H "Authorization: Bearer ' . $token->plainTextToken . '" \\');
        $this->line('       "https://your-tenant.local.test/api/v1/availability?service_id=xxx&date=2025-01-01"');

        return 0;
    }
}
