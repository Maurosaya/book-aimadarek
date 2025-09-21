@extends('panel.layouts.app')

@section('title', __('panel.onboarding.title'))

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-gray-900">{{ __('panel.onboarding.title') }}</h1>
            <p class="mt-2 text-lg text-gray-600">{{ __('panel.onboarding.subtitle') }}</p>
        </div>

        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-center space-x-4">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-8 h-8 bg-indigo-600 text-white rounded-full text-sm font-medium">
                        1
                    </div>
                    <span class="ml-2 text-sm font-medium text-indigo-600">{{ __('panel.onboarding.step_1') }}</span>
                </div>
                <div class="flex-1 h-px bg-gray-300"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-8 h-8 bg-gray-300 text-gray-600 rounded-full text-sm font-medium">
                        2
                    </div>
                    <span class="ml-2 text-sm font-medium text-gray-500">{{ __('panel.onboarding.step_2') }}</span>
                </div>
                <div class="flex-1 h-px bg-gray-300"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-8 h-8 bg-gray-300 text-gray-600 rounded-full text-sm font-medium">
                        3
                    </div>
                    <span class="ml-2 text-sm font-medium text-gray-500">{{ __('panel.onboarding.step_3') }}</span>
                </div>
            </div>
        </div>

        <!-- Coming Soon Card -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('panel.onboarding.coming_soon') }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ __('panel.onboarding.coming_soon_description') }}</p>
                    <div class="mt-6">
                        <div class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100">
                            <svg class="-ml-1 mr-2 h-5 w-5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                            {{ __('panel.onboarding.in_development') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
