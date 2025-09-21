<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- SEO Meta Tags -->
    <title>{{ __('landing.meta.title') }}</title>
    <meta name="description" content="{{ __('landing.meta.description') }}">
    <meta name="keywords" content="{{ __('landing.meta.keywords') }}">
    <meta name="author" content="{{ $marketing['brand'] }}">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ __('landing.meta.title') }}">
    <meta property="og:description" content="{{ __('landing.meta.description') }}">
    <meta property="og:image" content="{{ asset('landing/og-image.jpg') }}">
    <meta property="og:locale" content="{{ app()->getLocale() }}">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ __('landing.meta.title') }}">
    <meta property="twitter:description" content="{{ __('landing.meta.description') }}">
    <meta property="twitter:image" content="{{ asset('landing/og-image.jpg') }}">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('landing/apple-touch-icon.png') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Critical CSS -->
    <style>
        /* Critical CSS for above-the-fold content */
        body { 
            font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif; 
            line-height: 1.6; 
            color: #1f2937; 
            background-color: #ffffff;
        }
        .dark body { 
            color: #f9fafb; 
            background-color: #111827; 
        }
        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .dark .hero-gradient {
            background: linear-gradient(135deg, #1e3a8a 0%, #7c3aed 100%);
        }
        .loading { opacity: 0; transition: opacity 0.3s ease; }
        .loaded { opacity: 1; }
    </style>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional Styles -->
    <style>
        /* Smooth scrolling and focus styles */
        html { scroll-behavior: smooth; }
        *:focus { outline: 2px solid #3b82f6; outline-offset: 2px; }
        
        /* Language selector styles */
        .lang-selector {
            position: relative;
            display: inline-block;
        }
        .lang-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            z-index: 50;
            min-width: 120px;
        }
        .dark .lang-dropdown {
            background: #1f2937;
            border-color: #374151;
        }
        .lang-option {
            display: block;
            width: 100%;
            padding: 0.5rem 1rem;
            text-align: left;
            border: none;
            background: none;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .lang-option:hover {
            background-color: #f3f4f6;
        }
        .dark .lang-option:hover {
            background-color: #374151;
        }
        
        /* Widget demo styles */
        .widget-demo {
            border: 2px solid #e5e7eb;
            border-radius: 1rem;
            padding: 2rem;
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .dark .widget-demo {
            background: #1f2937;
            border-color: #374151;
        }
        
        /* Animation classes */
        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .slide-up {
            animation: slideUp 0.8s ease-out;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body class="antialiased loading" data-theme="auto">
    <!-- Skip to main content for accessibility -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-blue-600 text-white px-4 py-2 rounded-md z-50">
        Skip to main content
    </a>

    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-40 bg-white/80 backdrop-blur-md border-b border-gray-200 dark:bg-gray-900/80 dark:border-gray-700" role="navigation" aria-label="Main navigation">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('landing') }}" class="flex items-center space-x-2" aria-label="{{ $marketing['brand'] }}">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-gray-900 dark:text-white">{{ $marketing['brand'] }}</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-8">
                        <a href="#features" class="text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400 px-3 py-2 text-sm font-medium transition-colors">{{ __('landing.nav.features') }}</a>
                        <a href="#demo" class="text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400 px-3 py-2 text-sm font-medium transition-colors">{{ __('landing.nav.demo') }}</a>
                        <a href="#how-it-works" class="text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400 px-3 py-2 text-sm font-medium transition-colors">{{ __('landing.nav.how_it_works') }}</a>
                        <a href="#api" class="text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400 px-3 py-2 text-sm font-medium transition-colors">{{ __('landing.nav.api') }}</a>
                        <a href="#contact" class="text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400 px-3 py-2 text-sm font-medium transition-colors">{{ __('landing.nav.contact') }}</a>
                    </div>
                </div>

                <!-- Language Selector & CTA -->
                <div class="flex items-center space-x-4">
                    <!-- Language Selector -->
                    <div class="lang-selector">
                        <button id="lang-toggle" class="flex items-center space-x-1 text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400 px-3 py-2 text-sm font-medium transition-colors" aria-label="Select language">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z" clip-rule="evenodd"/>
                            </svg>
                            <span class="uppercase">{{ app()->getLocale() }}</span>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div id="lang-dropdown" class="lang-dropdown hidden">
                            @foreach($supported_locales as $locale)
                                <a href="{{ request()->fullUrlWithQuery(['locale' => $locale]) }}" 
                                   class="lang-option {{ app()->getLocale() === $locale ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' : '' }}">
                                    {{ strtoupper($locale) }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <a href="{{ $marketing['contact_url'] }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        {{ __('landing.hero.cta_contact') }}
                    </a>

                    <!-- Mobile menu button -->
                    <button id="mobile-menu-toggle" class="md:hidden p-2 rounded-md text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400" aria-label="Menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="md:hidden hidden bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="#features" class="block px-3 py-2 text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400">{{ __('landing.nav.features') }}</a>
                <a href="#demo" class="block px-3 py-2 text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400">{{ __('landing.nav.demo') }}</a>
                <a href="#how-it-works" class="block px-3 py-2 text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400">{{ __('landing.nav.how_it_works') }}</a>
                <a href="#api" class="block px-3 py-2 text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400">{{ __('landing.nav.api') }}</a>
                <a href="#contact" class="block px-3 py-2 text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400">{{ __('landing.nav.contact') }}</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main id="main-content" role="main">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white" role="contentinfo">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Brand -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold">{{ $marketing['brand'] }}</span>
                    </div>
                    <p class="text-gray-400 mb-4">{{ __('landing.footer.description') }}</p>
                </div>

                <!-- Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Links</h3>
                    <ul class="space-y-2">
                        <li><a href="#features" class="text-gray-400 hover:text-white transition-colors">Features</a></li>
                        <li><a href="#demo" class="text-gray-400 hover:text-white transition-colors">Demo</a></li>
                        <li><a href="#api" class="text-gray-400 hover:text-white transition-colors">API</a></li>
                        <li><a href="#contact" class="text-gray-400 hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Legal</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Privacy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Terms</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Cookies</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                <p class="text-gray-400">{{ __('landing.footer.copyright', ['year' => date('Y'), 'brand' => $marketing['brand']]) }}</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Theme detection and language selector
        document.addEventListener('DOMContentLoaded', function() {
            // Remove loading class
            document.body.classList.remove('loading');
            document.body.classList.add('loaded');

            // Language selector
            const langToggle = document.getElementById('lang-toggle');
            const langDropdown = document.getElementById('lang-dropdown');
            
            if (langToggle && langDropdown) {
                langToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    langDropdown.classList.toggle('hidden');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!langToggle.contains(e.target) && !langDropdown.contains(e.target)) {
                        langDropdown.classList.add('hidden');
                    }
                });
            }

            // Mobile menu
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuToggle && mobileMenu) {
                mobileMenuToggle.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }

            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Intersection Observer for animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('fade-in');
                    }
                });
            }, observerOptions);

            // Observe elements for animation
            document.querySelectorAll('section').forEach(section => {
                observer.observe(section);
            });
        });

        // Dark mode detection
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        }

        // Listen for theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            if (e.matches) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        });
    </script>

    @yield('scripts')
</body>
</html>
