<?php
// Test tenant redirect functionality
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "<h1>Test Tenant Redirect</h1>";

try {
    // Simulate ranch tenant request
    $request = \Illuminate\Http\Request::create('https://ranch.book.aimadarek.com/', 'GET');
    
    // Check if tenant exists
    $tenant = \App\Models\Tenant::where('id', 'ranch')->first();
    if ($tenant) {
        echo "<p>✅ Tenant 'ranch' found: " . $tenant->brand_name . "</p>";
        echo "<p>Active: " . ($tenant->active ? 'Yes' : 'No') . "</p>";
        
        // Check domains
        $domains = $tenant->domains;
        echo "<p>Domains: " . $domains->count() . "</p>";
        foreach ($domains as $domain) {
            echo "<p>  - " . $domain->domain . "</p>";
        }
        
        // Initialize tenancy
        tenancy()->initialize($tenant);
        echo "<p>✅ Tenancy initialized</p>";
        
        // Test route
        $routes = \Illuminate\Support\Facades\Route::getRoutes();
        $tenantRoutes = [];
        foreach ($routes as $route) {
            if (strpos($route->uri(), '{tenant}') !== false || strpos($route->uri(), 'panel') !== false) {
                $tenantRoutes[] = $route->uri() . ' -> ' . $route->getName();
            }
        }
        
        echo "<p>Tenant routes found: " . count($tenantRoutes) . "</p>";
        echo "<ul>";
        foreach ($tenantRoutes as $route) {
            echo "<li>$route</li>";
        }
        echo "</ul>";
        
    } else {
        echo "<p>❌ Tenant 'ranch' not found</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>File: " . $e->getFile() . ":" . $e->getLine() . "</p>";
}
?>
