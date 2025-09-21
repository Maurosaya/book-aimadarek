@extends('panel.layouts.app')

@section('title', __('panel.bookings.create'))

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
                                <a href="{{ route('panel.bookings.index') }}" class="text-gray-400 hover:text-gray-500">
                                    <svg class="flex-shrink-0 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                    </svg>
                                    <span class="sr-only">{{ __('panel.bookings.title') }}</span>
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <a href="{{ route('panel.bookings.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                    {{ __('panel.bookings.title') }}
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-4 text-sm font-medium text-gray-500">{{ __('panel.bookings.create') }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2 class="mt-2 text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    {{ __('panel.bookings.create') }}
                </h2>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow rounded-lg">
            <form action="{{ route('panel.bookings.store') }}" method="POST" class="space-y-6 p-6">
                @csrf

                <!-- Service Selection -->
                <div>
                    <label for="service_id" class="block text-sm font-medium text-gray-700">
                        {{ __('panel.bookings.service') }} <span class="text-red-500">*</span>
                    </label>
                    <select id="service_id" 
                            name="service_id" 
                            required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('service_id') border-red-500 @enderror">
                        <option value="">{{ __('panel.bookings.select_service') }}</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->name }} ({{ $service->duration_min }} {{ __('panel.bookings.minutes') }})
                            </option>
                        @endforeach
                    </select>
                    @error('service_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Customer Selection -->
                <div>
                    <label for="customer_id" class="block text-sm font-medium text-gray-700">
                        {{ __('panel.bookings.customer') }} <span class="text-red-500">*</span>
                    </label>
                    <select id="customer_id" 
                            name="customer_id" 
                            required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('customer_id') border-red-500 @enderror">
                        <option value="">{{ __('panel.bookings.select_customer') }}</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} ({{ $customer->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        <a href="{{ route('panel.customers.create') }}" class="text-indigo-600 hover:text-indigo-500">
                            {{ __('panel.bookings.create_new_customer') }}
                        </a>
                    </p>
                </div>

                <!-- Date and Time -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700">
                            {{ __('panel.bookings.date') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="date" 
                               name="date" 
                               value="{{ old('date', $defaultDate) }}"
                               required
                               min="{{ date('Y-m-d') }}"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('date') border-red-500 @enderror">
                        @error('date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="time" class="block text-sm font-medium text-gray-700">
                            {{ __('panel.bookings.time') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="time" 
                               id="time" 
                               name="time" 
                               value="{{ old('time', $defaultTime) }}"
                               required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('time') border-red-500 @enderror">
                        @error('time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Hidden start_at field -->
                <input type="hidden" id="start_at" name="start_at" value="{{ old('start_at') }}">

                <!-- Party Size (conditional) -->
                <div id="party_size_field" class="hidden">
                    <label for="party_size" class="block text-sm font-medium text-gray-700">
                        {{ __('panel.bookings.party_size') }}
                    </label>
                    <input type="number" 
                           id="party_size" 
                           name="party_size" 
                           min="1" 
                           max="20"
                           value="{{ old('party_size') }}"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('party_size') border-red-500 @enderror">
                    @error('party_size')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">
                        {{ __('panel.bookings.notes') }}
                    </label>
                    <textarea id="notes" 
                              name="notes" 
                              rows="3"
                              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('notes') border-red-500 @enderror"
                              placeholder="{{ __('panel.bookings.notes_placeholder') }}">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('panel.bookings.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('panel.common.cancel') }}
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('panel.bookings.create_booking') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceSelect = document.getElementById('service_id');
    const customerSelect = document.getElementById('customer_id');
    const dateInput = document.getElementById('date');
    const timeInput = document.getElementById('time');
    const startAtInput = document.getElementById('start_at');
    const partySizeField = document.getElementById('party_size_field');
    const partySizeInput = document.getElementById('party_size');

    // Services data for reference
    const services = @json($services->keyBy('id'));

    // Update party size field visibility based on service
    function updatePartySizeField() {
        const selectedServiceId = serviceSelect.value;
        if (selectedServiceId && services[selectedServiceId]) {
            const service = services[selectedServiceId];
            const requiresTable = service.required_resource_types && 
                                service.required_resource_types.includes('TABLE');
            
            if (requiresTable) {
                partySizeField.classList.remove('hidden');
                partySizeInput.required = true;
            } else {
                partySizeField.classList.add('hidden');
                partySizeInput.required = false;
                partySizeInput.value = '';
            }
        } else {
            partySizeField.classList.add('hidden');
            partySizeInput.required = false;
        }
    }

    // Update start_at field when date or time changes
    function updateStartAt() {
        const date = dateInput.value;
        const time = timeInput.value;
        
        if (date && time) {
            const startAt = date + ' ' + time + ':00';
            startAtInput.value = startAt;
        }
    }

    // Event listeners
    serviceSelect.addEventListener('change', updatePartySizeField);
    dateInput.addEventListener('change', updateStartAt);
    timeInput.addEventListener('change', updateStartAt);

    // Initialize
    updatePartySizeField();
    updateStartAt();

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const date = dateInput.value;
        const time = timeInput.value;
        
        if (date && time) {
            const selectedDateTime = new Date(date + ' ' + time);
            const now = new Date();
            
            if (selectedDateTime <= now) {
                e.preventDefault();
                alert('{{ __('panel.bookings.future_date_required') }}');
                return false;
            }
        }
    });
});
</script>
@endsection
