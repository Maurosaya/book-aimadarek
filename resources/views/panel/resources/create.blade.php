@extends('panel.layouts.app')

@section('title', __('panel.resources.create'))

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
                                <a href="{{ route('panel.resources.index') }}" class="text-gray-400 hover:text-gray-500">
                                    <svg class="flex-shrink-0 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                    </svg>
                                    <span class="sr-only">{{ __('panel.resources.title') }}</span>
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <a href="{{ route('panel.resources.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                    {{ __('panel.resources.title') }}
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-4 text-sm font-medium text-gray-500">{{ __('panel.resources.create') }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2 class="mt-2 text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    {{ __('panel.resources.create') }}
                </h2>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow rounded-lg">
            <form action="{{ route('panel.resources.store') }}" method="POST" class="space-y-6 p-6">
                @csrf

                <!-- Location and Type -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Location -->
                    <div>
                        <label for="location_id" class="block text-sm font-medium text-gray-700">
                            {{ __('panel.resources.location') }} <span class="text-red-500">*</span>
                        </label>
                        <select id="location_id" 
                                name="location_id" 
                                required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('location_id') border-red-500 @enderror">
                            <option value="">{{ __('panel.resources.select_location') }}</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('location_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">
                            {{ __('panel.resources.type') }} <span class="text-red-500">*</span>
                        </label>
                        <select id="type" 
                                name="type" 
                                required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('type') border-red-500 @enderror">
                            <option value="">{{ __('panel.resources.select_type') }}</option>
                            @foreach($resourceTypes as $value => $label)
                                <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Resource Label Translations -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('panel.resources.label_translations') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Spanish -->
                        <div>
                            <label for="label_es" class="block text-sm font-medium text-gray-700">
                                {{ __('panel.resources.label_es') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="label_es" 
                                   name="label_es" 
                                   value="{{ old('label_es') }}"
                                   required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('label_es') border-red-500 @enderror">
                            @error('label_es')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- English -->
                        <div>
                            <label for="label_en" class="block text-sm font-medium text-gray-700">
                                {{ __('panel.resources.label_en') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="label_en" 
                                   name="label_en" 
                                   value="{{ old('label_en') }}"
                                   required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('label_en') border-red-500 @enderror">
                            @error('label_en')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dutch -->
                        <div>
                            <label for="label_nl" class="block text-sm font-medium text-gray-700">
                                {{ __('panel.resources.label_nl') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="label_nl" 
                                   name="label_nl" 
                                   value="{{ old('label_nl') }}"
                                   required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('label_nl') border-red-500 @enderror">
                            @error('label_nl')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Capacity -->
                <div>
                    <label for="capacity" class="block text-sm font-medium text-gray-700">
                        {{ __('panel.resources.capacity') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           id="capacity" 
                           name="capacity" 
                           value="{{ old('capacity', 1) }}"
                           min="1" 
                           max="100"
                           required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('capacity') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">{{ __('panel.resources.capacity_help') }}</p>
                    @error('capacity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Combinable With -->
                <div id="combinable_section">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('panel.resources.combinable_with') }}
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        @foreach($combinationTypes as $type => $label)
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="combinable_with[]" 
                                       value="{{ $type }}"
                                       {{ in_array($type, old('combinable_with', [])) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="mt-1 text-xs text-gray-500">{{ __('panel.resources.combinable_help') }}</p>
                    @error('combinable_with')
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
                        {{ __('panel.resources.active') }}
                    </label>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('panel.resources.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('panel.common.cancel') }}
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('panel.resources.create') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const combinableSection = document.getElementById('combinable_section');
    
    // Show/hide combinable section based on type
    function updateCombinableSection() {
        const selectedType = typeSelect.value;
        
        // Only show combinable section for TABLE, STAFF, and ROOM types
        if (['TABLE', 'STAFF', 'ROOM'].includes(selectedType)) {
            combinableSection.style.display = 'block';
        } else {
            combinableSection.style.display = 'none';
            // Uncheck all combinable checkboxes
            const checkboxes = combinableSection.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => checkbox.checked = false);
        }
    }
    
    // Update capacity label based on type
    function updateCapacityLabel() {
        const selectedType = typeSelect.value;
        const capacityInput = document.getElementById('capacity');
        const capacityLabel = document.querySelector('label[for="capacity"]');
        
        if (selectedType === 'TABLE') {
            capacityLabel.textContent = '{{ __("panel.resources.capacity") }} ({{ __("panel.resources.people") }}) *';
            capacityInput.placeholder = '4';
        } else if (selectedType === 'STAFF') {
            capacityLabel.textContent = '{{ __("panel.resources.capacity") }} ({{ __("panel.resources.people") }}) *';
            capacityInput.placeholder = '1';
        } else if (selectedType === 'ROOM') {
            capacityLabel.textContent = '{{ __("panel.resources.capacity") }} ({{ __("panel.resources.people") }}) *';
            capacityInput.placeholder = '2';
        } else {
            capacityLabel.textContent = '{{ __("panel.resources.capacity") }} *';
            capacityInput.placeholder = '1';
        }
    }
    
    typeSelect.addEventListener('change', function() {
        updateCombinableSection();
        updateCapacityLabel();
    });
    
    // Initialize
    updateCombinableSection();
    updateCapacityLabel();
});
</script>
@endsection
