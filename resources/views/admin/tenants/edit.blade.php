@extends('admin.layouts.app')

@section('title', __('admin.tenant_edit.title'))

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-red-600">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        {{ __('admin.nav.dashboard') }}
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('admin.tenants.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-red-600 md:ml-2">{{ __('admin.nav.companies') }}</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('admin.tenants.show', $tenant) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-red-600 md:ml-2">{{ $tenant->brand_name }}</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ __('admin.tenant_edit.edit_company') }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    {{ __('admin.tenant_edit.edit_company_title') }}: {{ $tenant->brand_name }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    {{ __('admin.tenant_edit.edit_company_subtitle') }}
                </p>
            </div>
        </div>

        <!-- Form -->
        <div class="mt-6">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <form method="POST" action="{{ route('admin.tenants.update', $tenant) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="px-4 py-5 sm:p-6">
                        <!-- Company Information -->
                        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                            <div>
                                <label for="brand_name" class="block text-sm font-medium text-gray-700">
                                    {{ __('admin.tenant_edit.company_name') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <input type="text" 
                                           name="brand_name" 
                                           id="brand_name" 
                                           value="{{ old('brand_name', $tenant->brand_name) }}"
                                           class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md @error('brand_name') border-red-500 @enderror"
                                           placeholder="{{ __('admin.tenant_edit.company_name_placeholder') }}"
                                           required>
                                </div>
                                @error('brand_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="id" class="block text-sm font-medium text-gray-700">
                                    {{ __('admin.tenant_edit.company_id') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <input type="text" 
                                           name="id" 
                                           id="id" 
                                           value="{{ old('id', $tenant->id) }}"
                                           class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md @error('id') border-red-500 @enderror"
                                           placeholder="{{ __('admin.tenant_edit.company_id_placeholder') }}"
                                           required>
                                    <p class="mt-1 text-xs text-gray-500">{{ __('admin.tenant_edit.company_id_help') }}</p>
                                </div>
                                @error('id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Localization -->
                        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
                            <div>
                                <label for="default_locale" class="block text-sm font-medium text-gray-700">
                                    {{ __('admin.tenant_edit.main_language') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <select name="default_locale" 
                                            id="default_locale"
                                            class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md @error('default_locale') border-red-500 @enderror"
                                            required>
                                        <option value="">{{ __('admin.tenant_edit.select_language') }}</option>
                                        <option value="es" {{ old('default_locale', $tenant->default_locale) === 'es' ? 'selected' : '' }}>{{ __('admin.tenant_edit.spanish') }}</option>
                                        <option value="en" {{ old('default_locale', $tenant->default_locale) === 'en' ? 'selected' : '' }}>{{ __('admin.tenant_edit.english') }}</option>
                                        <option value="nl" {{ old('default_locale', $tenant->default_locale) === 'nl' ? 'selected' : '' }}>{{ __('admin.tenant_edit.dutch') }}</option>
                                    </select>
                                </div>
                                @error('default_locale')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="supported_locales" class="block text-sm font-medium text-gray-700">
                                    {{ __('admin.tenant_edit.supported_languages') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 space-y-2">
                                    @php
                                        $current_locales = is_array($tenant->supported_locales) ? $tenant->supported_locales : json_decode($tenant->supported_locales, true);
                                        $old_locales = old('supported_locales', $current_locales);
                                    @endphp
                                    <label class="flex items-center">
                                        <input type="checkbox" name="supported_locales[]" value="es" 
                                               {{ in_array('es', $old_locales) ? 'checked' : '' }}
                                               class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-700">{{ __('admin.tenant_edit.spanish') }}</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="supported_locales[]" value="en" 
                                               {{ in_array('en', $old_locales) ? 'checked' : '' }}
                                               class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-700">{{ __('admin.tenant_edit.english') }}</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="supported_locales[]" value="nl" 
                                               {{ in_array('nl', $old_locales) ? 'checked' : '' }}
                                               class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-700">{{ __('admin.tenant_edit.dutch') }}</span>
                                    </label>
                                </div>
                                @error('supported_locales')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="timezone" class="block text-sm font-medium text-gray-700">
                                    {{ __('admin.tenant_edit.timezone') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <select name="timezone" 
                                            id="timezone"
                                            class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md @error('timezone') border-red-500 @enderror"
                                            required>
                                        <option value="">{{ __('admin.tenant_edit.select_timezone') }}</option>
                                        <option value="Europe/Madrid" {{ old('timezone', $tenant->timezone) === 'Europe/Madrid' ? 'selected' : '' }}>Europe/Madrid</option>
                                        <option value="Europe/Amsterdam" {{ old('timezone', $tenant->timezone) === 'Europe/Amsterdam' ? 'selected' : '' }}>Europe/Amsterdam</option>
                                        <option value="Europe/London" {{ old('timezone', $tenant->timezone) === 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                                        <option value="America/New_York" {{ old('timezone', $tenant->timezone) === 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                                    </select>
                                </div>
                                @error('timezone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ __('admin.tenant_edit.status') }}
                            </label>
                            <div class="mt-1">
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           name="active" 
                                           id="active" 
                                           value="1"
                                           {{ old('active', $tenant->active) ? 'checked' : '' }}
                                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                    <label for="active" class="ml-2 block text-sm text-gray-900">
                                        {{ __('admin.tenant_edit.active_company') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Current Information -->
                        <div class="mt-6 bg-gray-50 border border-gray-200 rounded-md p-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">{{ __('admin.tenant_edit.current_information') }}</h4>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">{{ __('admin.tenant_edit.current_domain') }}</span> 
                                        {{ $tenant->domains->first()->domain ?? __('admin.tenant_edit.no_domain') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">{{ __('admin.tenant_edit.current_status') }}</span> 
                                        {{ $tenant->active ? __('admin.common.active') : __('admin.common.inactive') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">{{ __('admin.tenant_edit.created') }}</span> 
                                        {{ $tenant->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">{{ __('admin.tenant_edit.last_updated') }}</span> 
                                        {{ $tenant->updated_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 sm:rounded-b-lg">
                        <a href="{{ route('admin.tenants.show', $tenant) }}" 
                           class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            {{ __('admin.tenant_edit.cancel') }}
                        </a>
                        <button type="submit" 
                                class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            {{ __('admin.tenant_edit.update_company') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
