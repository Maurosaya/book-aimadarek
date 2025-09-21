@extends('panel.layouts.app')

@section('title', __('panel.services.title'))

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    {{ __('panel.services.title') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    {{ __('panel.services.description') }}
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('panel.services.create') }}" 
                   class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('panel.services.create') }}
                </a>
            </div>
        </div>

        <!-- Services Grid -->
        @if($services->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($services as $service)
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        {{ $service->name }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ $service->duration_min }} {{ __('panel.services.minutes') }}
                                    </p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($service->active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ __('panel.common.active') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ __('panel.common.inactive') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4">
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <span>{{ __('panel.services.duration') }}:</span>
                                    <span class="font-medium">{{ $service->duration_min }} {{ __('panel.services.minutes') }}</span>
                                </div>
                                
                                @if($service->price_cents)
                                    <div class="flex items-center justify-between text-sm text-gray-500 mt-1">
                                        <span>{{ __('panel.services.price') }}:</span>
                                        <span class="font-medium">€{{ number_format($service->price_cents / 100, 2) }}</span>
                                    </div>
                                @endif

                                <div class="flex items-center justify-between text-sm text-gray-500 mt-1">
                                    <span>{{ __('panel.services.buffers') }}:</span>
                                    <span class="font-medium">{{ $service->buffer_before_min }}/{{ $service->buffer_after_min }} {{ __('panel.services.minutes') }}</span>
                                </div>

                                <div class="mt-2">
                                    <span class="text-sm text-gray-500">{{ __('panel.services.required_resources') }}:</span>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @foreach($service->required_resource_types as $type)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ __('panel.services.resource_types.' . $type) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 flex items-center justify-between">
                                <a href="{{ route('panel.services.show', $service) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                    {{ __('panel.common.view') }}
                                </a>
                                
                                <div class="flex items-center space-x-2">
                                    @can('update', $service)
                                        <a href="{{ route('panel.services.edit', $service) }}" 
                                           class="text-yellow-600 hover:text-yellow-900 text-sm font-medium">
                                            {{ __('panel.common.edit') }}
                                        </a>
                                        
                                        <form method="POST" action="{{ route('panel.services.toggle', $service) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="text-gray-600 hover:text-gray-900 text-sm font-medium">
                                                {{ $service->active ? __('panel.services.deactivate') : __('panel.services.activate') }}
                                            </button>
                                        </form>
                                    @endcan
                                    
                                    @can('delete', $service)
                                        <button onclick="deleteService({{ $service->id }})" 
                                                class="text-red-600 hover:text-red-900 text-sm font-medium">
                                            {{ __('panel.common.delete') }}
                                        </button>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('panel.services.no_services') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('panel.services.no_services_description') }}</p>
                <div class="mt-6">
                    <a href="{{ route('panel.services.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        {{ __('panel.services.create') }}
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Delete Service Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('panel.services.delete_service') }}</h3>
            <p class="text-sm text-gray-500 mb-6">{{ __('panel.services.delete_confirmation') }}</p>
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
function deleteService(serviceId) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    form.action = `/{{ tenant('id') }}/panel/services/${serviceId}`;
    modal.classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>
@endsection
