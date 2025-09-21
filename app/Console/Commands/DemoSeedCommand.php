<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

/**
 * Demo Seed Command
 * 
 * Migrates and runs the demo seeder to create 4 demo tenants with complete data
 */
class DemoSeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:seed 
                            {--fresh : Drop all tables and re-run migrations}
                            {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate and seed the database with demo tenants (ranch, beerta-barbers, glow-beauty, smile-dental)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Starting demo seed process...');
        $this->newLine();

        // Check if we should run fresh migrations
        if ($this->option('fresh')) {
            $this->info('🗑️  Dropping all tables and re-running migrations...');
            
            if ($this->option('force') || $this->confirm('This will drop all existing data. Continue?')) {
                Artisan::call('migrate:fresh', [
                    '--force' => $this->option('force'),
                ]);
                
                $this->info('✅ Database migrated fresh');
            } else {
                $this->error('❌ Operation cancelled');
                return 1;
            }
        } else {
            // Run regular migrations
            $this->info('🔄 Running migrations...');
            Artisan::call('migrate', [
                '--force' => $this->option('force'),
            ]);
            
            $this->info('✅ Database migrated');
        }

        $this->newLine();

        // Run the database seeder
        $this->info('🌱 Running demo seeder...');
        Artisan::call('db:seed', [
            '--class' => 'DatabaseSeeder',
            '--force' => $this->option('force'),
        ]);

        $this->newLine();

        // Display access cards information
        $this->displayAccessCards();

        $this->newLine();
        $this->info('🎉 Demo seed completed successfully!');
        $this->newLine();
        
        $this->displayUsageInstructions();

        return 0;
    }

    /**
     * Display access cards information
     */
    private function displayAccessCards(): void
    {
        $demoAccessPath = storage_path('app/demo_access.json');
        
        if (File::exists($demoAccessPath)) {
            $accessCards = json_decode(File::get($demoAccessPath), true);
            
            $this->info('📋 Demo Access Cards Generated:');
            $this->newLine();
            
            foreach ($accessCards as $card) {
                $this->line("🏢 <fg=cyan>{$card['brand_name']}</> ({$card['subdomain']})");
                $this->line("   📧 Email: <fg=yellow>{$card['credentials']['email']}</>");
                $this->line("   🔑 Password: <fg=yellow>Demo!1234</>");
                $this->line("   🔗 Panel: <fg=blue>{$card['panel_url']}</>");
                $this->line("   🎯 Widget: <fg=blue>{$card['widget_url']}</>");
                $this->line("   🆔 Service ID: <fg=green>{$card['service_id']}</>");
                $this->newLine();
            }
        }
    }

    /**
     * Display usage instructions
     */
    private function displayUsageInstructions(): void
    {
        $this->info('📖 Usage Instructions:');
        $this->newLine();
        
        $this->line('1. <fg=cyan>Access Demo Tenants:</fg=cyan>');
        $this->line('   - Visit the landing page to see the demo selector');
        $this->line('   - Use the credentials above to access each tenant\'s panel');
        $this->newLine();
        
        $this->line('2. <fg=cyan>Test the Widget:</fg=cyan>');
        $this->line('   - Use the "Probar Widget" button on the landing page');
        $this->line('   - Or visit the widget URLs directly');
        $this->newLine();
        
        $this->line('3. <fg=cyan>API Testing:</fg=cyan>');
        $this->line('   - Use the API tokens for testing the availability endpoints');
        $this->line('   - Example: curl -H "Authorization: Bearer TOKEN" \\');
        $this->line('            "https://tenant.book.aimadarek.com/api/v1/availability?service_id=SERVICE_ID&date=2025-01-01"');
        $this->newLine();
        
        $this->line('4. <fg=cyan>Reset Demo Data:</fg=cyan>');
        $this->line('   - Run: <fg=yellow>php artisan demo:seed --fresh</fg=yellow>');
        $this->newLine();
    }
}
