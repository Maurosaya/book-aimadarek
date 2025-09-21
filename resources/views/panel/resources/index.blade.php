@extends('panel.layouts.app')

@section('title', __('panel.resources.title'))

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    {{ __('panel.resources.title') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    {{ __('panel.resources.description') }}
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('panel.resources.create') }}" 
                   class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('panel.resources.create') }}
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <form method="GET" action="{{ route('panel.resources.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Type Filter -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('panel.resources.type') }}
                        </label>
                        <select name="type" id="type" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">{{ __('panel.resources.all_types') }}</option>
                            @foreach($resourceTypes as $value => $label)
                                <option value="{{ $value }}" {{ request('type') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Location Filter -->
                    <div>
                        <label for="location_id" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('panel.resources.location') }}
                        </label>
                        <select name="location_id" id="location_id" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">{{ __('panel.resources.all_locations') }}</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
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

        <!-- Resources Grid -->
        @if($resources->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($resources as $resource)
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        {{ $resource->label }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ $resource->location->name }}
                                    </p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $resourceTypes[$resource->type] }}
                                    </span>
                                    @if($resource->active)
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
                                    <span>{{ __('panel.resources.capacity') }}:</span>
                                    <span class="font-medium">{{ $resource->capacity }} {{ __('panel.resources.people') }}</span>
                                </div>

                                @if(count($resource->combinable_with) > 0)
                                    <div class="mt-2">
                                        <span class="text-sm text-gray-500">{{ __('panel.resources.combinable_with') }}:</span>
                                        <div class="flex flex-wrap gap-1 mt-1">
                                            @foreach($resource->combinable_with as $type)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    {{ $resourceTypes[$type] }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-6 flex items-center justify-between">
                                <a href="{{ route('panel.resources.show', $resource) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                    {{ __('panel.common.view') }}
                                </a>
                                
                                <div class="flex items-center space-x-2">
                                    @can('update', $resource)
                                        <a href="{{ route('panel.resources.edit', $resource) }}" 
                                           class="text-yellow-600 hover:text-yellow-900 text-sm font-medium">
                                            {{ __('panel.common.edit') }}
                                        </a>
                                        
                                        <form method="POST" action="{{ route('panel.resources.toggle', $resource) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="text-gray-600 hover:text-gray-900 text-sm font-medium">
                                                {{ $resource->active ? __('panel.resources.deactivate') : __('panel.resources.activate') }}
                                            </button>
                                        </form>
                                    @endcan
                                    
                                    @can('delete', $resource)
                                        <button onclick="deleteResource({{ $resource->id }})" 
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('panel.resources.no_resources') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('panel.resources.no_resources_description') }}</p>
                <div class="mt-6">
                    <a href="{{ route('panel.resources.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        {{ __('panel.resources.create') }}
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Delete Resource Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('panel.resources.delete_resource') }}</h3>
            <p class="text-sm text-gray-500 mb-6">{{ __('panel.resources.delete_confirmation') }}</p>
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
function deleteResource(resourceId) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    form.action = `/{{ tenant('id') }}/panel/resources/${resourceId}`;
    modal.classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>
@endsection
