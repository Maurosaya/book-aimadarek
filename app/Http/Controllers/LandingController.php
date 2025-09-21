<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

/**
 * Landing Controller
 * 
 * Handles the landing page functionality including demo tenant selection
 */
class LandingController extends Controller
{
    /**
     * Show the landing page
     */
    public function index(Request $request)
    {
        // Get marketing configuration
        $marketing = [
            'brand' => config('marketing.brand', 'Aimadarek Book'),
            'contact_url' => config('marketing.contact_url', '#contact'),
            'demo_enabled' => config('marketing.demo_enabled', true),
        ];

        // Get demo access cards if they exist
        $demoAccessCards = $this->getDemoAccessCards();

        // Define supported locales
        $supported_locales = ['es', 'en', 'nl'];

        return view('landing.home', compact('marketing', 'demoAccessCards', 'supported_locales'));
    }

    /**
     * Get demo access cards from storage
     */
    private function getDemoAccessCards(): ?array
    {
        $demoAccessPath = storage_path('app/demo_access.json');
        
        if (File::exists($demoAccessPath)) {
            $content = File::get($demoAccessPath);
            $cards = json_decode($content, true);
            
            if (json_last_error() === JSON_ERROR_NONE && is_array($cards)) {
                return $cards;
            }
        }
        
        return null;
    }

    /**
     * Get demo access data for widget
     */
    public function getDemoAccess(Request $request)
    {
        try {
            $demoFile = storage_path('app/demo_access.json');
            
            if (!File::exists($demoFile)) {
                return response()->json(['error' => 'Demo data not found'], 404);
            }
            
            $demoData = json_decode(File::get($demoFile), true);
            
            // Return first tenant as demo (or you could add logic to select specific tenant)
            $firstTenant = reset($demoData);
            
            return response()->json($firstTenant);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error loading demo data'], 500);
        }
    }

    /**
     * Get availability for a specific tenant and service
     */
    public function getAvailability(Request $request)
    {
        $request->validate([
            'tenant' => 'required|string',
            'service_id' => 'required|integer',
            'date' => 'required|date',
        ]);

        $tenant = $request->input('tenant');
        $serviceId = $request->input('service_id');
        $date = $request->input('date');
        $locale = $request->input('locale', 'en');

        try {
            // Initialize tenancy for the specific tenant
            $tenantModel = \App\Models\Tenant::where('id', $tenant)->first();
            
            if (!$tenantModel) {
                return response()->json([
                    'error' => 'Tenant not found'
                ], 404);
            }

            // Initialize tenancy
            tenancy()->initialize($tenantModel);

            // Set locale
            app()->setLocale($locale);

            // Find the service
            $service = \App\Models\Service::find($serviceId);
            
            if (!$service) {
                tenancy()->end();
                return response()->json([
                    'error' => 'Service not found'
                ], 404);
            }

            // Use the CapacityService to get availability
            $capacityService = app(\App\Domain\CapacityService::class);
            $availability = $capacityService->getAvailableSlots($service, \Carbon\Carbon::parse($date));

            // End tenancy
            tenancy()->end();
            
            return response()->json([
                'availability' => $availability
            ]);
            
        } catch (\Exception $e) {
            // Make sure to end tenancy in case of error
            if (tenancy()->initialized) {
                tenancy()->end();
            }
            
            return response()->json([
                'error' => 'Error fetching availability: ' . $e->getMessage()
            ], 500);
        }
    }
}
