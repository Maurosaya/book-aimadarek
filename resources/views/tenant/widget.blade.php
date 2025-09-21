<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ tenant('brand_name') }} - Booking Widget</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-6">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">
                    {{ tenant('brand_name') }}
                </h1>
                <p class="text-gray-600">
                    {{ __('Booking Widget') }}
                </p>
            </div>
            
            <!-- Widget Demo -->
            <div id="reservas-widget" data-demo="true"></div>
            
            <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-semibold text-blue-800">
                            {{ __('Widget Demo') }}
                        </h3>
                        <p class="text-sm text-blue-700">
                            {{ __('Tenant ID:') }} {{ tenant('id') }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 text-center">
                <a href="/panel" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    {{ __('Go to Panel') }}
                </a>
            </div>
        </div>
    </div>
    
    <script src="/widget.js"></script>
</body>
</html>
