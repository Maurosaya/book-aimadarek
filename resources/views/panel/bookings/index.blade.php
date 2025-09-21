@extends('panel.layouts.app')

@section('title', __('panel.bookings.title'))

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    {{ __('panel.bookings.title') }}
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('panel.bookings.create') }}" 
                   class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('panel.bookings.create') }}
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <form method="GET" action="{{ route('panel.bookings.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('panel.bookings.status') }}
                        </label>
                        <select name="status" id="status" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">{{ __('panel.bookings.all_statuses') }}</option>
                            @foreach($statusOptions as $value => $label)
                                <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Service Filter -->
                    <div>
                        <label for="service_id" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('panel.bookings.service') }}
                        </label>
                        <select name="service_id" id="service_id" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">{{ __('panel.bookings.all_services') }}</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Customer Search -->
                    <div>
                        <label for="customer_search" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('panel.bookings.search_customer') }}
                        </label>
                        <input type="text" 
                               name="customer_search" 
                               id="customer_search"
                               value="{{ request('customer_search') }}"
                               placeholder="{{ __('panel.bookings.customer_name_email_phone') }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <!-- Date From -->
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('panel.bookings.date_from') }}
                        </label>
                        <input type="date" 
                               name="date_from" 
                               id="date_from"
                               value="{{ request('date_from') }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <!-- Date To -->
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('panel.bookings.date_to') }}
                        </label>
                        <input type="date" 
                               name="date_to" 
                               id="date_to"
                               value="{{ request('date_to') }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <!-- Filter Button -->
                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            {{ __('panel.common.filter') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bookings Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            @if($bookings->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($bookings as $booking)
                        <li class="hover:bg-gray-50">
                            <div class="px-4 py-4 flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="flex items-center">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $booking->customer->name }}
                                            </p>
                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                                   ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                   ($booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                                {{ __('panel.bookings.status_' . $booking->status) }}
                                            </span>
                                        </div>
                                        <div class="mt-1 flex items-center text-sm text-gray-500">
                                            <p>{{ $booking->service->name }}</p>
                                            <span class="mx-2">•</span>
                                            <p>{{ $booking->start_at->format('M d, Y') }} at {{ $booking->start_at->format('H:i') }}</p>
                                            @if($booking->party_size)
                                                <span class="mx-2">•</span>
                                                <p>{{ $booking->party_size }} {{ __('panel.bookings.guests') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('panel.bookings.show', $booking) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                        {{ __('panel.common.view') }}
                                    </a>
                                    @can('update', $booking)
                                        <a href="{{ route('panel.bookings.edit', $booking) }}" 
                                           class="text-yellow-600 hover:text-yellow-900 text-sm font-medium">
                                            {{ __('panel.common.edit') }}
                                        </a>
                                    @endcan
                                    @can('cancel', $booking)
                                        @if($booking->status !== 'cancelled')
                                            <button onclick="cancelBooking({{ $booking->id }})" 
                                                    class="text-red-600 hover:text-red-900 text-sm font-medium">
                                                {{ __('panel.bookings.cancel') }}
                                            </button>
                                        @endif
                                    @endcan
                                    @can('markNoShow', $booking)
                                        @if($booking->status === 'confirmed' && $booking->start_at->isPast())
                                            <button onclick="markNoShow({{ $booking->id }})" 
                                                    class="text-orange-600 hover:text-orange-900 text-sm font-medium">
                                                {{ __('panel.bookings.no_show') }}
                                            </button>
                                        @endif
                                    @endcan
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <!-- Pagination -->
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        {{ $bookings->links() }}
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                {{ __('panel.bookings.showing') }}
                                <span class="font-medium">{{ $bookings->firstItem() }}</span>
                                {{ __('panel.bookings.to') }}
                                <span class="font-medium">{{ $bookings->lastItem() }}</span>
                                {{ __('panel.bookings.of') }}
                                <span class="font-medium">{{ $bookings->total() }}</span>
                                {{ __('panel.bookings.results') }}
                            </p>
                        </div>
                        <div>
                            {{ $bookings->links() }}
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('panel.bookings.no_bookings') }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ __('panel.bookings.no_bookings_description') }}</p>
                    <div class="mt-6">
                        <a href="{{ route('panel.bookings.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            {{ __('panel.bookings.create') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Cancel Booking Modal -->
<div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" x-data="{ show: false }">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('panel.bookings.cancel_booking') }}</h3>
            <form id="cancelForm" method="POST">
                @csrf
                @method('POST')
                <div class="mb-4">
                    <label for="cancel_reason" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('panel.bookings.cancel_reason') }}
                    </label>
                    <textarea id="cancel_reason" 
                              name="reason" 
                              rows="3" 
                              class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                              placeholder="{{ __('panel.bookings.cancel_reason_placeholder') }}"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeCancelModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('panel.common.cancel') }}
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        {{ __('panel.bookings.cancel_booking') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function cancelBooking(bookingId) {
    const modal = document.getElementById('cancelModal');
    const form = document.getElementById('cancelForm');
    form.action = `/{{ tenant('id') }}/panel/bookings/${bookingId}/cancel`;
    modal.classList.remove('hidden');
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
}

function markNoShow(bookingId) {
    if (confirm('{{ __('panel.bookings.confirm_no_show') }}')) {
        fetch(`/{{ tenant('id') }}/panel/bookings/${bookingId}/no-show`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __('panel.common.error') }}');
        });
    }
}
</script>
@endsection
