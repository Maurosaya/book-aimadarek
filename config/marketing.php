<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Marketing Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for marketing pages and demo functionality
    |
    */

    'brand' => env('MARKETING_BRAND', 'Aimadarek Book'),
    'contact_url' => env('MARKETING_CONTACT_URL', '#contact'),
    'demo_enabled' => env('MARKETING_DEMO_ENABLED', true),
    
    'social' => [
        'twitter' => env('MARKETING_TWITTER_URL', 'https://twitter.com/aimadarek'),
        'linkedin' => env('MARKETING_LINKEDIN_URL', 'https://linkedin.com/company/aimadarek'),
        'github' => env('MARKETING_GITHUB_URL', 'https://github.com/aimadarek'),
    ],
    
    'analytics' => [
        'google_analytics_id' => env('GOOGLE_ANALYTICS_ID'),
        'facebook_pixel_id' => env('FACEBOOK_PIXEL_ID'),
    ],
];