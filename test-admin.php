<?php
// Test file to verify admin functionality
echo "<h1>Test Admin Access</h1>";
echo "<p>If you can see this, the server is working.</p>";

// Test if we can load Laravel
try {
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    echo "<p>✅ Laravel loaded successfully</p>";
    
    // Test controller
    $controller = new \App\Http\Controllers\Admin\SuperAdminController();
    echo "<p>✅ SuperAdminController loaded successfully</p>";
    
    // Test route
    $routes = \Illuminate\Support\Facades\Route::getRoutes();
    $adminRoutes = [];
    foreach ($routes as $route) {
        if (strpos($route->uri(), 'admin') !== false) {
            $adminRoutes[] = $route->uri();
        }
    }
    
    echo "<p>✅ Admin routes found: " . count($adminRoutes) . "</p>";
    echo "<ul>";
    foreach ($adminRoutes as $route) {
        echo "<li>$route</li>";
    }
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>File: " . $e->getFile() . ":" . $e->getLine() . "</p>";
}
?>
