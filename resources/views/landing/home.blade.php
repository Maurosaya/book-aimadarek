@extends('landing.layout')

@section('content')
    <!-- Hero Section -->
    <section class="hero-gradient py-16 md:py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <!-- Text Content -->
                <div class="text-center md:text-left">
                    <div class="slide-up">
                        <h1 class="text-4xl md:text-5xl font-bold text-white leading-tight mb-4">
                            {{ __('landing.hero.title') }}
                        </h1>
                        <p class="text-base md:text-lg text-white/90 mb-8 max-w-2xl">
                            {{ __('landing.hero.subtitle') }}
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                            <a href="#demo" 
                               class="bg-white text-blue-600 hover:bg-gray-100 px-6 py-3 rounded-lg font-semibold transition-colors w-full md:w-auto text-center">
                                {{ __('landing.hero.cta_demo') }}
                            </a>
                            <a href="{{ $marketing['contact_url'] }}" 
                               class="border-2 border-white text-white hover:bg-white hover:text-blue-600 px-6 py-3 rounded-lg font-semibold transition-colors w-full md:w-auto text-center">
                                {{ __('landing.hero.cta_contact') }}
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Image/Illustration -->
                <div class="flex justify-center md:justify-end">
                    <div class="w-full h-auto max-h-[360px] md:max-h-[420px] flex items-center justify-center">
                        <!-- Placeholder for hero illustration -->
                        <div class="w-full h-full max-h-[360px] md:max-h-[420px] bg-white/10 rounded-xl shadow-lg flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-32 h-32 md:w-40 md:h-40 text-white/60" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Scroll indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <a href="#features" class="text-white/70 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                </svg>
            </a>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-12 md:py-16 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white mb-4">
                    {{ __('landing.features.title') }}
                </h2>
                <p class="text-base md:text-lg text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                    {{ __('landing.features.subtitle') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Multitenant -->
                <div class="rounded-2xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all bg-white dark:bg-gray-800">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">
                        {{ __('landing.features.multitenant.title') }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        {{ __('landing.features.multitenant.description') }}
                    </p>
                </div>

                <!-- Multi-Vertical -->
                <div class="rounded-2xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all bg-white dark:bg-gray-800">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">
                        {{ __('landing.features.multi_vertical.title') }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        {{ __('landing.features.multi_vertical.description') }}
                    </p>
                </div>

                <!-- AI Integration -->
                <div class="rounded-2xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all bg-white dark:bg-gray-800">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">
                        {{ __('landing.features.ai_integration.title') }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        {{ __('landing.features.ai_integration.description') }}
                    </p>
                </div>

                <!-- Notifications -->
                <div class="rounded-2xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all bg-white dark:bg-gray-800">
                    <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5zM8 15v-3a2 2 0 114 0v3H8z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">
                        {{ __('landing.features.notifications.title') }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        {{ __('landing.features.notifications.description') }}
                    </p>
                </div>

                <!-- Webhooks -->
                <div class="rounded-2xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all bg-white dark:bg-gray-800">
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">
                        {{ __('landing.features.webhooks.title') }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        {{ __('landing.features.webhooks.description') }}
                    </p>
                </div>

                <!-- Multilingual -->
                <div class="rounded-2xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all bg-white dark:bg-gray-800">
                    <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7 2a1 1 0 011 1v1h3a1 1 0 110 2H9.578a18.87 18.87 0 01-1.724 4.78c.29.354.596.696.914 1.026a1 1 0 11-1.44 1.389c-.188-.196-.373-.396-.554-.6a19.098 19.098 0 01-3.107 3.567 1 1 0 01-1.334-1.49 17.087 17.087 0 003.13-3.733 18.992 18.992 0 01-1.487-2.494 1 1 0 111.79-.89c.234.47.489.928.764 1.372.417-.934.752-1.913.997-2.927H3a1 1 0 110-2h3V3a1 1 0 011-1zm6 6a1 1 0 01.894.553l2.991 5.982a.869.869 0 01.02.037l.99 1.98a1 1 0 11-1.79.895L15.383 16h-4.764l-.724 1.447a1 1 0 11-1.788-.894l.99-1.98.019-.038 2.99-5.982A1 1 0 0113 8zm-1.382 6h2.764L13 11.236 11.618 14z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">
                        {{ __('landing.features.multilingual.title') }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        {{ __('landing.features.multilingual.description') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Demo Section -->
    <section id="demo" class="py-12 md:py-16 bg-gray-50 dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white mb-4">
                    {{ __('landing.demo.title') }}
                </h2>
                <p class="text-base md:text-lg text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                    {{ __('landing.demo.subtitle') }}
                </p>
            </div>

            @if($demoAccessCards && count($demoAccessCards) > 0)
                <!-- Demo Tenant Selector -->
                <div class="max-w-4xl mx-auto mb-8">
                    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 text-center">
                            {{ __('landing.demo.select_tenant') }}
                        </h3>
                        
                        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                            @foreach($demoAccessCards as $card)
                                <div class="tenant-card border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:border-blue-500 transition-colors cursor-pointer" 
                                     data-tenant="{{ $card['subdomain'] }}" 
                                     data-service-id="{{ $card['service_id'] }}"
                                     data-panel-url="{{ $card['panel_url'] }}"
                                     data-widget-url="{{ $card['widget_url'] }}">
                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-2">
                                        {{ $card['brand_name'] }}
                                    </h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                        {{ $card['subdomain'] }}.book.aimadarek.com
                                    </p>
                                    <div class="flex flex-col space-y-2">
                                        <button class="open-panel-btn bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm font-medium transition-colors">
                                            {{ __('landing.demo.open_panel') }}
                                        </button>
                                        <button class="test-widget-btn bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-sm font-medium transition-colors">
                                            {{ __('landing.demo.test_widget') }}
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Demo Info -->
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-200 mb-1">
                                        {{ __('landing.demo.demo_credentials') }}
                                    </h4>
                                    <p class="text-sm text-blue-700 dark:text-blue-300">
                                        {{ __('landing.demo.demo_credentials_text') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Widget Demo Results -->
                <div id="widget-demo-results" class="max-w-2xl mx-auto hidden">
                    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                            {{ __('landing.demo.widget_results') }}
                        </h3>
                        <div id="availability-results" class="space-y-3">
                            <!-- Results will be populated here -->
                        </div>
                        <div class="mt-4 text-center">
                            <button id="close-results" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm font-medium transition-colors">
                                {{ __('landing.demo.close_results') }}
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <!-- No Demo Data Available -->
                <div class="max-w-2xl mx-auto">
                    <div class="widget-demo">
                        <div class="text-center mb-6">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                                {{ __('landing.demo.widget_title') }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                {{ __('landing.demo.widget_subtitle') }}
                            </p>
                        </div>

                        <div class="text-center p-6 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                {{ __('landing.demo.demo_mode') }}
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
                                {{ __('landing.demo.no_data') }}
                            </p>
                            <a href="{{ $marketing['contact_url'] }}" 
                               class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors w-full md:w-auto">
                                {{ __('landing.demo.configure_service') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section id="contact" class="py-12 md:py-16 bg-blue-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl md:text-3xl font-semibold text-white mb-4">
                {{ __('landing.cta.title') }}
            </h2>
            <p class="text-base md:text-lg text-blue-100 mb-8 max-w-3xl mx-auto">
                {{ __('landing.cta.subtitle') }}
            </p>
            <a href="{{ $marketing['contact_url'] }}" 
               class="bg-white text-blue-600 hover:bg-gray-100 px-6 py-3 rounded-lg font-semibold transition-colors w-full md:w-auto inline-block">
                {{ __('landing.cta.button') }}
            </a>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    // Demo functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Handle panel button clicks
        document.querySelectorAll('.open-panel-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const card = this.closest('.tenant-card');
                const panelUrl = card.dataset.panelUrl;
                
                if (panelUrl) {
                    window.open(panelUrl, '_blank');
                }
            });
        });

        // Handle widget test button clicks
        document.querySelectorAll('.test-widget-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const card = this.closest('.tenant-card');
                const tenant = card.dataset.tenant;
                const serviceId = card.dataset.serviceId;
                
                if (tenant && serviceId) {
                    testWidget(tenant, serviceId);
                }
            });
        });

        // Handle close results button
        const closeResultsBtn = document.getElementById('close-results');
        if (closeResultsBtn) {
            closeResultsBtn.addEventListener('click', function() {
                const resultsDiv = document.getElementById('widget-demo-results');
                if (resultsDiv) {
                    resultsDiv.classList.add('hidden');
                }
            });
        }

        // Function to test widget availability
        async function testWidget(tenant, serviceId) {
            const resultsDiv = document.getElementById('widget-demo-results');
            const resultsContent = document.getElementById('availability-results');
            
            if (!resultsDiv || !resultsContent) return;

            // Show loading state
            resultsDiv.classList.remove('hidden');
            resultsContent.innerHTML = `
                <div class="flex items-center justify-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <span class="ml-3 text-gray-600 dark:text-gray-400">Loading availability...</span>
                </div>
            `;

            try {
                // Get tomorrow's date
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                const dateStr = tomorrow.toISOString().split('T')[0];

                // Make API request
                const response = await fetch(`/demo/availability?tenant=${tenant}&service_id=${serviceId}&date=${dateStr}&locale={{ app()->getLocale() }}`);
                const data = await response.json();

                if (response.ok && data.availability) {
                    // Display availability results
                    resultsContent.innerHTML = `
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-2">
                                Availability for ${tenant} - ${dateStr}
                            </h4>
                        </div>
                        <div class="space-y-2">
                            ${data.availability.map(slot => `
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                        ${slot.time}
                                    </span>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        Available
                                    </span>
                                </div>
                            `).join('')}
                        </div>
                    `;
                } else {
                    // Show error message
                    resultsContent.innerHTML = `
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-red-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-red-600 dark:text-red-400">
                                ${data.error || 'Failed to load availability data'}
                            </p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error testing widget:', error);
                resultsContent.innerHTML = `
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-red-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-red-600 dark:text-red-400">
                            Network error. Please try again.
                        </p>
                    </div>
                `;
            }
        }

        // Check if widget.js exists and load it
        const widgetScript = document.createElement('script');
        widgetScript.src = '/widget.js';
        widgetScript.onerror = function() {
            console.log('Widget script not found - running in demo mode');
        };
        document.head.appendChild(widgetScript);
    });
</script>
@endsection