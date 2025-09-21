<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Marketing Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for marketing-related settings used in the landing page
    | and other marketing materials.
    |
    */

    'brand' => env('MARKETING_BRAND', env('APP_NAME', 'Aimadarek Book')),
    
    'contact_url' => env('MARKETING_CONTACT_URL', 'mailto:hola@aimadarek.com'),
    
    'demo_url' => env('MARKETING_DEMO_URL', ''),
    
    'docs_url' => env('MARKETING_DOCS_URL', '/api/health'),
    
    'webhook_fallback_secret' => env('WEBHOOK_FALLBACK_SECRET', 'default-webhook-secret'),
];
