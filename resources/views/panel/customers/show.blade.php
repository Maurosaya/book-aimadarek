@extends('panel.layouts.app')

@section('title', $customer->name)

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-4">
                        <li>
                            <div>
                                <a href="{{ route('panel.customers.index') }}" class="text-gray-400 hover:text-gray-500">
                                    <svg class="flex-shrink-0 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                    </svg>
                                    <span class="sr-only">{{ __('panel.customers.title') }}</span>
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <a href="{{ route('panel.customers.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                    {{ __('panel.customers.title') }}
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-4 text-sm font-medium text-gray-500">{{ $customer->name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2 class="mt-2 text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    {{ $customer->name }}
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('panel.customers.edit', $customer) }}" 
                   class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                    </svg>
                    {{ __('panel.common.edit') }}
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Customer Information -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            {{ __('panel.customers.information') }}
                        </h3>
                        
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('panel.customers.name') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $customer->name }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('panel.customers.email') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <a href="mailto:{{ $customer->email }}" class="text-indigo-600 hover:text-indigo-500">
                                        {{ $customer->email }}
                                    </a>
                                </dd>
                            </div>
                            
                            @if($customer->phone)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('panel.customers.phone') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <a href="tel:{{ $customer->phone }}" class="text-indigo-600 hover:text-indigo-500">
                                        {{ $customer->phone }}
                                    </a>
                                </dd>
                            </div>
                            @endif
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('panel.customers.created_at') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $customer->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('panel.customers.last_booking') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if($customer->bookings()->exists())
                                        {{ $customer->bookings()->latest('start_at')->first()->start_at->format('d/m/Y H:i') }}
                                    @else
                                        {{ __('panel.customers.no_bookings') }}
                                    @endif
                                </dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">{{ __('panel.customers.gdpr_optin') }}</dt>
                                <dd class="mt-1">
                                    @if($customer->gdpr_optin)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ __('panel.customers.gdpr_opted_in') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ __('panel.customers.gdpr_not_opted_in') }}
                                        </span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                        
                        @if($customer->notes)
                        <div class="mt-6">
                            <dt class="text-sm font-medium text-gray-500">{{ __('panel.customers.notes') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-md">
                                {{ $customer->notes }}
                            </dd>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Booking History -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            {{ __('panel.customers.booking_history') }}
                            <span class="text-sm font-normal text-gray-500">({{ $customer->bookings()->count() }} {{ __('panel.customers.bookings') }})</span>
                        </h3>
                        
                        @if($customer->bookings()->exists())
                            <div class="space-y-4">
                                @foreach($customer->bookings()->with(['service', 'resources'])->latest('start_at')->limit(10)->get() as $booking)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <h4 class="text-sm font-medium text-gray-900">
                                                    {{ $booking->service->name }}
                                                </h4>
                                                <p class="text-sm text-gray-500">
                                                    {{ $booking->start_at->format('d/m/Y H:i') }} - {{ $booking->end_at->format('H:i') }}
                                                </p>
                                                @if($booking->resources->isNotEmpty())
                                                    <p class="text-xs text-gray-400 mt-1">
                                                        {{ __('panel.customers.resources') }}: {{ $booking->resources->pluck('label')->join(', ') }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($booking->status === \App\Models\Booking::STATUS_CONFIRMED) bg-green-100 text-green-800
                                                    @elseif($booking->status === \App\Models\Booking::STATUS_PENDING) bg-yellow-100 text-yellow-800
                                                    @elseif($booking->status === \App\Models\Booking::STATUS_CANCELLED) bg-red-100 text-red-800
                                                    @elseif($booking->status === \App\Models\Booking::STATUS_NO_SHOW) bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ __('panel.bookings.status_' . $booking->status) }}
                                                </span>
                                                <a href="{{ route('panel.bookings.show', $booking) }}" 
                                                   class="text-indigo-600 hover:text-indigo-500 text-sm">
                                                    {{ __('panel.common.view') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            @if($customer->bookings()->count() > 10)
                                <div class="mt-4 text-center">
                                    <a href="{{ route('panel.customers.index', ['search' => $customer->email]) }}" 
                                       class="text-sm text-indigo-600 hover:text-indigo-500">
                                        {{ __('panel.customers.view_all_bookings') }}
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('panel.customers.no_bookings_title') }}</h3>
                                <p class="mt-1 text-sm text-gray-500">{{ __('panel.customers.no_bookings_description') }}</p>
                                <div class="mt-6">
                                    <a href="{{ route('panel.bookings.create', ['customer_id' => $customer->id]) }}" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                        </svg>
                                        {{ __('panel.customers.create_first_booking') }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
