@extends('panel.layouts.app')

@section('title', __('panel.nav.profile'))

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    {{ __('panel.nav.profile') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">{{ __('panel.settings.profile_description') }}</p>
            </div>
        </div>

        <!-- Profile Card -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="text-center py-8">
                    <div class="mx-auto h-20 w-20 bg-indigo-100 rounded-full flex items-center justify-center">
                        <span class="text-2xl font-medium text-indigo-600">{{ substr($currentUser->name, 0, 1) }}</span>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">{{ $currentUser->name }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ $currentUser->email }}</p>
                    <p class="mt-1 text-sm text-gray-500">{{ ucfirst($currentUser->role) }}</p>
                    
                    <div class="mt-6">
                        <div class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100">
                            <svg class="-ml-1 mr-2 h-5 w-5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                            {{ __('panel.settings.profile_editing_coming_soon') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
