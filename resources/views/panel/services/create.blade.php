@extends('panel.layouts.app')

@section('title', __('panel.services.create'))

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-4">
                        <li>
                            <div>
                                <a href="{{ route('panel.services.index') }}" class="text-gray-400 hover:text-gray-500">
                                    <svg class="flex-shrink-0 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                    </svg>
                                    <span class="sr-only">{{ __('panel.services.title') }}</span>
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <a href="{{ route('panel.services.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                    {{ __('panel.services.title') }}
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-4 text-sm font-medium text-gray-500">{{ __('panel.services.create') }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2 class="mt-2 text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    {{ __('panel.services.create') }}
                </h2>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow rounded-lg">
            <form action="{{ route('panel.services.store') }}" method="POST" class="space-y-6 p-6">
                @csrf

                <!-- Service Name Translations -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('panel.services.name_translations') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Spanish -->
                        <div>
                            <label for="name_es" class="block text-sm font-medium text-gray-700">
                                {{ __('panel.services.name_es') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name_es" 
                                   name="name_es" 
                                   value="{{ old('name_es') }}"
                                   required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('name_es') border-red-500 @enderror">
                            @error('name_es')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- English -->
                        <div>
                            <label for="name_en" class="block text-sm font-medium text-gray-700">
                                {{ __('panel.services.name_en') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name_en" 
                                   name="name_en" 
                                   value="{{ old('name_en') }}"
                                   required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('name_en') border-red-500 @enderror">
                            @error('name_en')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dutch -->
                        <div>
                            <label for="name_nl" class="block text-sm font-medium text-gray-700">
                                {{ __('panel.services.name_nl') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name_nl" 
                                   name="name_nl" 
                                   value="{{ old('name_nl') }}"
                                   required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('name_nl') border-red-500 @enderror">
                            @error('name_nl')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Duration and Buffers -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('panel.services.duration_buffers') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Duration -->
                        <div>
                            <label for="duration_min" class="block text-sm font-medium text-gray-700">
                                {{ __('panel.services.duration') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   id="duration_min" 
                                   name="duration_min" 
                                   value="{{ old('duration_min', 60) }}"
                                   min="1" 
                                   max="1440"
                                   required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('duration_min') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">{{ __('panel.services.duration_help') }}</p>
                            @error('duration_min')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buffer Before -->
                        <div>
                            <label for="buffer_before_min" class="block text-sm font-medium text-gray-700">
                                {{ __('panel.services.buffer_before') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   id="buffer_before_min" 
                                   name="buffer_before_min" 
                                   value="{{ old('buffer_before_min', 5) }}"
                                   min="0" 
                                   max="120"
                                   required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('buffer_before_min') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">{{ __('panel.services.buffer_before_help') }}</p>
                            @error('buffer_before_min')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buffer After -->
                        <div>
                            <label for="buffer_after_min" class="block text-sm font-medium text-gray-700">
                                {{ __('panel.services.buffer_after') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   id="buffer_after_min" 
                                   name="buffer_after_min" 
                                   value="{{ old('buffer_after_min', 15) }}"
                                   min="0" 
                                   max="120"
                                   required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('buffer_after_min') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">{{ __('panel.services.buffer_after_help') }}</p>
                            @error('buffer_after_min')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Price -->
                <div>
                    <label for="price_cents" class="block text-sm font-medium text-gray-700">
                        {{ __('panel.services.price') }}
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">€</span>
                        </div>
                        <input type="number" 
                               id="price_cents" 
                               name="price_cents" 
                               value="{{ old('price_cents') }}"
                               min="0" 
                               step="0.01"
                               placeholder="0.00"
                               class="pl-7 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('price_cents') border-red-500 @enderror">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">{{ __('panel.services.price_help') }}</p>
                    @error('price_cents')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Required Resource Types -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('panel.services.required_resources') }} <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach($resourceTypes as $type => $label)
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="required_resource_types[]" 
                                       value="{{ $type }}"
                                       {{ in_array($type, old('required_resource_types', ['TABLE'])) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="mt-1 text-xs text-gray-500">{{ __('panel.services.required_resources_help') }}</p>
                    @error('required_resource_types')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="active" 
                           name="active" 
                           value="1"
                           {{ old('active', '1') ? 'checked' : '' }}
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="active" class="ml-2 block text-sm text-gray-900">
                        {{ __('panel.services.active') }}
                    </label>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('panel.services.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('panel.common.cancel') }}
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('panel.services.create') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Convert price input to cents
    const priceInput = document.getElementById('price_cents');
    
    priceInput.addEventListener('input', function() {
        const value = parseFloat(this.value) || 0;
        this.value = Math.round(value * 100);
    });
    
    // Initialize with cents conversion
    const initialValue = priceInput.value;
    if (initialValue) {
        priceInput.value = (parseFloat(initialValue) / 100).toFixed(2);
    }
});
</script>
@endsection
