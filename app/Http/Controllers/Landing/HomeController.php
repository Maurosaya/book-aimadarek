<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

/**
 * Landing Home Controller
 * 
 * Handles the main landing page with multilingual support
 * Integrates with existing API and webhook systems
 */
class HomeController extends Controller
{
    /**
     * Display the landing page
     */
    public function index(Request $request)
    {
        // Get marketing configuration from environment
        $marketingConfig = [
            'brand' => config('marketing.brand'),
            'contact_url' => config('marketing.contact_url'),
            'demo_url' => config('marketing.demo_url'),
            'docs_url' => config('marketing.docs_url'),
        ];

        // Get demo service ID if available (for widget demo)
        $demoServiceId = $this->getDemoServiceId();
        $demoTenantSlug = $this->getDemoTenantSlug();

        // Prepare data for the view
        $data = [
            'marketing' => $marketingConfig,
            'demo' => [
                'service_id' => $demoServiceId,
                'tenant_slug' => $demoTenantSlug,
                'has_demo_data' => !is_null($demoServiceId),
            ],
            'locale' => App::getLocale(),
            'supported_locales' => ['es', 'en', 'nl'],
        ];

        return view('landing.home', $data);
    }

    /**
     * Get demo service ID for widget demonstration
     * This would typically come from a demo tenant or configuration
     */
    private function getDemoServiceId(): ?string
    {
        // For now, return null to show demo mode
        // In a real implementation, you might:
        // 1. Check for a demo tenant in the database
        // 2. Use a configuration value
        // 3. Create a demo service on the fly
        
        return null; // Will trigger demo mode in the widget
    }

    /**
     * Get demo tenant slug for widget demonstration
     */
    private function getDemoTenantSlug(): ?string
    {
        // For now, return null to show demo mode
        // In a real implementation, you might return a demo tenant slug
        
        return null; // Will trigger demo mode in the widget
    }
}