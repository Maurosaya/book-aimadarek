<?php

return [
    // Meta tags
    'meta' => [
        'title' => 'Aimadarek Book - AI Agent + Multi-Vertical Booking System',
        'description' => 'Intelligent booking system for restaurants, barbershops, beauty salons, and dental clinics. AI integration, WhatsApp, webhooks, and multilingual support.',
        'keywords' => 'bookings, restaurants, barbershops, salons, dental, AI, WhatsApp, multitenant, API',
    ],

    // Navigation
    'nav' => [
        'home' => 'Home',
        'features' => 'Features',
        'demo' => 'Demo',
        'how_it_works' => 'How It Works',
        'api' => 'API',
        'contact' => 'Contact',
    ],

    // Hero section
    'hero' => [
        'title' => 'AI Agent + Booking System',
        'subtitle' => 'The intelligent solution for restaurants, barbershops, beauty salons, and dental clinics. Automate your bookings with AI, WhatsApp, and webhooks.',
        'cta_demo' => 'Try Demo',
        'cta_contact' => 'Contact',
        'scroll_down' => 'Learn More',
    ],

    // Features section
    'features' => [
        'title' => 'Key Features',
        'subtitle' => 'Everything you need to manage bookings intelligently',
        
        'multitenant' => [
            'title' => 'Multitenant',
            'description' => 'One system for multiple businesses with complete data isolation.',
        ],
        
        'multi_vertical' => [
            'title' => 'Multi-Vertical',
            'description' => 'Restaurants, barbershops, beauty salons, and dental clinics.',
        ],
        
        'ai_integration' => [
            'title' => 'AI Integration',
            'description' => 'Intelligent agent that optimizes schedules and manages bookings automatically.',
        ],
        
        'notifications' => [
            'title' => 'Smart Reminders',
            'description' => 'WhatsApp, email, and SMS with customizable templates.',
        ],
        
        'webhooks' => [
            'title' => 'HMAC Webhooks',
            'description' => 'REST API v1 with secure webhooks for integrations.',
        ],
        
        'multilingual' => [
            'title' => 'Multilingual',
            'description' => 'Full support for Spanish, English, and Dutch.',
        ],
    ],

    // Verticals section
    'verticals' => [
        'title' => 'Supported Industries',
        'subtitle' => 'Adapted for different types of businesses',
        
        'restaurant' => [
            'title' => 'Restaurants',
            'description' => 'Table management, groups, and availability with intelligent algorithms.',
        ],
        
        'barber' => [
            'title' => 'Barbershops',
            'description' => 'Staff scheduling and services with automatic reminders.',
        ],
        
        'beauty' => [
            'title' => 'Beauty Salons',
            'description' => 'Chair management, treatments, and stylist availability.',
        ],
        
        'dental' => [
            'title' => 'Dental Clinics',
            'description' => 'Room coordination, dentists, and specialized equipment.',
        ],
    ],

    // Demo section
    'demo' => [
        'title' => 'Interactive Demo',
        'subtitle' => 'Try the booking widget in action',
        'widget_title' => 'Booking System',
        'widget_subtitle' => 'Select date and time for your booking',
        'demo_mode' => 'Demo Mode',
        'demo_message' => 'This is a demo. To use in production, configure a service in the admin panel.',
        'view_schedule' => 'View Schedule',
        'no_data' => 'No demo data available',
        'configure_service' => 'Configure Service',
    ],

    // How it works section
    'how_it_works' => [
        'title' => 'How It Works',
        'subtitle' => 'Three simple steps to get started',
        
        'step1' => [
            'title' => '1. Choose Service and Date',
            'description' => 'Select the service you need and available date.',
        ],
        
        'step2' => [
            'title' => '2. Confirm Your Booking',
            'description' => 'Complete your details and confirm the booking.',
        ],
        
        'step3' => [
            'title' => '3. Receive Confirmation',
            'description' => 'Get confirmation by email and automatic reminders.',
        ],
    ],

    // API section
    'api' => [
        'title' => 'API Integration',
        'subtitle' => 'Connect your system with our REST API v1',
        'description' => 'Complete API with Sanctum authentication, HMAC webhooks, and multilingual support.',
        'view_docs' => 'View Documentation',
        'test_endpoint' => 'Test Endpoint',
    ],

    // FAQ section
    'faq' => [
        'title' => 'Frequently Asked Questions',
        'subtitle' => 'Answers to the most common questions',
        
        'q1' => [
            'question' => 'What types of businesses can use the system?',
            'answer' => 'The system is designed for restaurants, barbershops, beauty salons, and dental clinics, but is flexible to adapt to other service businesses.',
        ],
        
        'q2' => [
            'question' => 'How does WhatsApp integration work?',
            'answer' => 'The system sends automatic reminders via WhatsApp using customizable templates and webhooks for real-time notifications.',
        ],
        
        'q3' => [
            'question' => 'Is the webhook system secure?',
            'answer' => 'Yes, all webhooks are signed with HMAC SHA-256 and include automatic retries with exponential backoff.',
        ],
        
        'q4' => [
            'question' => 'Can I customize the booking widget?',
            'answer' => 'Yes, the widget is fully customizable and can be integrated into any website with multilingual support.',
        ],
        
        'q5' => [
            'question' => 'What languages does the system support?',
            'answer' => 'Currently supports Spanish, English, and Dutch, with plans to expand to more languages.',
        ],
    ],

    // CTA section
    'cta' => [
        'title' => 'Ready to Automate Your Bookings?',
        'subtitle' => 'Request a personalized demo and discover how we can help you',
        'button' => 'Request Personalized Demo',
    ],

    // Footer
    'footer' => [
        'description' => 'Intelligent booking system with AI for restaurants, barbershops, beauty salons, and dental clinics.',
        'links' => 'Links',
        'legal' => 'Legal',
        'privacy' => 'Privacy',
        'terms' => 'Terms',
        'cookies' => 'Cookies',
        'copyright' => '© :year :brand. All rights reserved.',
    ],

    // Common elements
    'common' => [
        'loading' => 'Loading...',
        'error' => 'Error',
        'success' => 'Success',
        'close' => 'Close',
        'open' => 'Open',
        'next' => 'Next',
        'previous' => 'Previous',
        'back' => 'Back',
        'continue' => 'Continue',
        'cancel' => 'Cancel',
        'save' => 'Save',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'view' => 'View',
        'download' => 'Download',
        'share' => 'Share',
        'copy' => 'Copy',
        'search' => 'Search',
        'filter' => 'Filter',
        'sort' => 'Sort',
        'refresh' => 'Refresh',
        'reload' => 'Reload',
    ],
];
