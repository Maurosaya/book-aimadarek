@extends('landing.layout')

@section('content')
    <!-- Hero Section -->
    <section class="hero-gradient min-h-screen flex items-center justify-center relative overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="slide-up">
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                    {{ __('landing.hero.title') }}
                </h1>
                <p class="text-xl md:text-2xl text-white/90 mb-8 max-w-3xl mx-auto">
                    {{ __('landing.hero.subtitle') }}
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#demo" 
                       class="bg-white text-blue-600 hover:bg-gray-100 px-8 py-3 rounded-lg text-lg font-semibold transition-colors">
                        {{ __('landing.hero.cta_demo') }}
                    </a>
                    <a href="{{ $marketing['contact_url'] }}" 
                       class="border-2 border-white text-white hover:bg-white hover:text-blue-600 px-8 py-3 rounded-lg text-lg font-semibold transition-colors">
                        {{ __('landing.hero.cta_contact') }}
                    </a>
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
    <section id="features" class="py-20 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    {{ __('landing.features.title') }}
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                    {{ __('landing.features.subtitle') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Multitenant -->
                <div class="bg-gray-50 dark:bg-gray-800 p-8 rounded-xl hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">
                        {{ __('landing.features.multitenant.title') }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        {{ __('landing.features.multitenant.description') }}
                    </p>
                </div>

                <!-- Multi-Vertical -->
                <div class="bg-gray-50 dark:bg-gray-800 p-8 rounded-xl hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">
                        {{ __('landing.features.multi_vertical.title') }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        {{ __('landing.features.multi_vertical.description') }}
                    </p>
                </div>

                <!-- AI Integration -->
                <div class="bg-gray-50 dark:bg-gray-800 p-8 rounded-xl hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">
                        {{ __('landing.features.ai_integration.title') }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        {{ __('landing.features.ai_integration.description') }}
                    </p>
                </div>

                <!-- Notifications -->
                <div class="bg-gray-50 dark:bg-gray-800 p-8 rounded-xl hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5zM8 15v-3a2 2 0 114 0v3H8z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">
                        {{ __('landing.features.notifications.title') }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        {{ __('landing.features.notifications.description') }}
                    </p>
                </div>

                <!-- Webhooks -->
                <div class="bg-gray-50 dark:bg-gray-800 p-8 rounded-xl hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">
                        {{ __('landing.features.webhooks.title') }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        {{ __('landing.features.webhooks.description') }}
                    </p>
                </div>

                <!-- Multilingual -->
                <div class="bg-gray-50 dark:bg-gray-800 p-8 rounded-xl hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7 2a1 1 0 011 1v1h3a1 1 0 110 2H9.578a18.87 18.87 0 01-1.724 4.78c.29.354.596.696.914 1.026a1 1 0 11-1.44 1.389c-.188-.196-.373-.396-.554-.6a19.098 19.098 0 01-3.107 3.567 1 1 0 01-1.334-1.49 17.087 17.087 0 003.13-3.733 18.992 18.992 0 01-1.487-2.494 1 1 0 111.79-.89c.234.47.489.928.764 1.372.417-.934.752-1.913.997-2.927H3a1 1 0 110-2h3V3a1 1 0 011-1zm6 6a1 1 0 01.894.553l2.991 5.982a.869.869 0 01.02.037l.99 1.98a1 1 0 11-1.79.895L15.383 16h-4.764l-.724 1.447a1 1 0 11-1.788-.894l.99-1.98.019-.038 2.99-5.982A1 1 0 0113 8zm-1.382 6h2.764L13 11.236 11.618 14z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">
                        {{ __('landing.features.multilingual.title') }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        {{ __('landing.features.multilingual.description') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Demo Section -->
    <section id="demo" class="py-20 bg-gray-50 dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    {{ __('landing.demo.title') }}
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                    {{ __('landing.demo.subtitle') }}
                </p>
            </div>

            <div class="max-w-2xl mx-auto">
                <div class="widget-demo">
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                            {{ __('landing.demo.widget_title') }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300">
                            {{ __('landing.demo.widget_subtitle') }}
                        </p>
                    </div>

                    <!-- Demo mode -->
                    <div class="text-center p-8 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                            {{ __('landing.demo.demo_mode') }}
                        </h4>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">
                            {{ __('landing.demo.no_data') }}
                        </p>
                        <a href="{{ $marketing['contact_url'] }}" 
                           class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                            {{ __('landing.demo.configure_service') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section id="contact" class="py-20 bg-blue-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                {{ __('landing.cta.title') }}
            </h2>
            <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
                {{ __('landing.cta.subtitle') }}
            </p>
            <a href="{{ $marketing['contact_url'] }}" 
               class="bg-white text-blue-600 hover:bg-gray-100 px-8 py-3 rounded-lg text-lg font-semibold transition-colors">
                {{ __('landing.cta.button') }}
            </a>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    // Widget demo functionality
    document.addEventListener('DOMContentLoaded', function() {
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