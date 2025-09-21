@extends('panel.layouts.app')

@section('title', __('panel.customers.title'))

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    {{ __('panel.customers.title') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    {{ __('panel.customers.description') }}
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                <a href="{{ route('panel.customers.export') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ __('panel.customers.export') }}
                </a>
                <a href="{{ route('panel.customers.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('panel.customers.create') }}
                </a>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <form method="GET" action="{{ route('panel.customers.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('panel.customers.search') }}
                        </label>
                        <input type="text" 
                               name="search" 
                               id="search"
                               value="{{ request('search') }}"
                               placeholder="{{ __('panel.customers.search_placeholder') }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <!-- GDPR Filter -->
                    <div>
                        <label for="gdpr_optin" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('panel.customers.gdpr_optin') }}
                        </label>
                        <select name="gdpr_optin" id="gdpr_optin" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">{{ __('panel.customers.all_customers') }}</option>
                            <option value="1" {{ request('gdpr_optin') === '1' ? 'selected' : '' }}>{{ __('panel.customers.gdpr_opted_in') }}</option>
                            <option value="0" {{ request('gdpr_optin') === '0' ? 'selected' : '' }}>{{ __('panel.customers.gdpr_not_opted_in') }}</option>
                        </select>
                    </div>

                    <!-- Filter Button -->
                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            {{ __('panel.customers.search_button') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Customers Table -->
        @if($customers->count() > 0)
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @foreach($customers as $customer)
                        <li class="hover:bg-gray-50">
                            <div class="px-4 py-4 flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <span class="text-indigo-600 text-sm font-medium">
                                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="flex items-center">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $customer->name }}
                                            </p>
                                            @if($customer->gdpr_optin)
                                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ __('panel.customers.gdpr_opted_in') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="mt-1 flex items-center text-sm text-gray-500">
                                            <p>{{ $customer->email }}</p>
                                            @if($customer->phone)
                                                <span class="mx-2">•</span>
                                                <p>{{ $customer->phone }}</p>
                                            @endif
                                        </div>
                                        @if($customer->notes)
                                            <div class="mt-1 text-sm text-gray-500">
                                                <p class="truncate">{{ $customer->notes }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('panel.customers.show', $customer) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                        {{ __('panel.common.view') }}
                                    </a>
                                    @can('update', $customer)
                                        <a href="{{ route('panel.customers.edit', $customer) }}" 
                                           class="text-yellow-600 hover:text-yellow-900 text-sm font-medium">
                                            {{ __('panel.common.edit') }}
                                        </a>
                                    @endcan
                                    @can('delete', $customer)
                                        <button onclick="deleteCustomer({{ $customer->id }})" 
                                                class="text-red-600 hover:text-red-900 text-sm font-medium">
                                            {{ __('panel.common.delete') }}
                                        </button>
                                    @endcan
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <!-- Pagination -->
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        {{ $customers->links() }}
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                {{ __('panel.customers.showing') }}
                                <span class="font-medium">{{ $customers->firstItem() }}</span>
                                {{ __('panel.customers.to') }}
                                <span class="font-medium">{{ $customers->lastItem() }}</span>
                                {{ __('panel.customers.of') }}
                                <span class="font-medium">{{ $customers->total() }}</span>
                                {{ __('panel.customers.results') }}
                            </p>
                        </div>
                        <div>
                            {{ $customers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('panel.customers.no_customers') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('panel.customers.no_customers_description') }}</p>
                <div class="mt-6">
                    <a href="{{ route('panel.customers.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        {{ __('panel.customers.create') }}
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Delete Customer Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('panel.customers.delete_customer') }}</h3>
            <p class="text-sm text-gray-500 mb-6">{{ __('panel.customers.delete_confirmation') }}</p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeDeleteModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('panel.common.cancel') }}
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        {{ __('panel.common.delete') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function deleteCustomer(customerId) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    form.action = `/{{ tenant('id') }}/panel/customers/${customerId}`;
    modal.classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>
@endsection
