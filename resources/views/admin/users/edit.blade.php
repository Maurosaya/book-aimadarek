@extends('admin.layouts.app')

@section('title', __('admin.user_edit.title') . ' - Super Admin')

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
                                <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-gray-500">
                                    <svg class="flex-shrink-0 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                    </svg>
                                    <span class="sr-only">{{ __('admin.nav.users') }}</span>
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <a href="{{ route('admin.users.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">{{ __('admin.nav.users') }}</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <a href="{{ route('admin.users.show', $user) }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">{{ $user->name }}</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-4 text-sm font-medium text-gray-500">{{ __('admin.user_edit.title') }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2 class="mt-2 text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    {{ __('admin.user_edit.edit_user_title', ['name' => $user->name]) }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">{{ __('admin.user_edit.edit_user_subtitle') }}</p>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow sm:rounded-lg">
            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <!-- Name -->
                        <div class="sm:col-span-3">
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                {{ __('admin.user_edit.full_name') }}
                            </label>
                            <div class="mt-1">
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       value="{{ old('name', $user->name) }}"
                                       class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md @error('name') border-red-300 @enderror"
                                       placeholder="{{ __('admin.user_edit.full_name_placeholder') }}">
                            </div>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="sm:col-span-3">
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                {{ __('admin.user_edit.email') }}
                            </label>
                            <div class="mt-1">
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       value="{{ old('email', $user->email) }}"
                                       class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md @error('email') border-red-300 @enderror"
                                       placeholder="{{ __('admin.user_edit.email_placeholder') }}">
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="sm:col-span-3">
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                {{ __('admin.user_edit.new_password') }}
                            </label>
                            <div class="mt-1">
                                <input type="password" 
                                       name="password" 
                                       id="password"
                                       class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md @error('password') border-red-300 @enderror"
                                       placeholder="{{ __('admin.user_edit.new_password_placeholder') }}">
                            </div>
                            <p class="mt-1 text-sm text-gray-500">{{ __('admin.user_edit.new_password_help') }}</p>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tenant -->
                        <div class="sm:col-span-3">
                            <label for="tenant_id" class="block text-sm font-medium text-gray-700">
                                {{ __('admin.user_edit.company') }}
                            </label>
                            <div class="mt-1">
                                <select name="tenant_id" 
                                        id="tenant_id"
                                        class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md @error('tenant_id') border-red-300 @enderror">
                                    <option value="">{{ __('admin.user_edit.select_company') }}</option>
                                    @foreach($tenants as $tenant)
                                        <option value="{{ $tenant->id }}" {{ old('tenant_id', $user->tenant_id) == $tenant->id ? 'selected' : '' }}>
                                            {{ $tenant->brand_name }} ({{ $tenant->id }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('tenant_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div class="sm:col-span-3">
                            <label for="role" class="block text-sm font-medium text-gray-700">
                                {{ __('admin.user_edit.role') }}
                            </label>
                            <div class="mt-1">
                                <select name="role" 
                                        id="role"
                                        class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md @error('role') border-red-300 @enderror">
                                    <option value="">{{ __('admin.user_edit.select_role') }}</option>
                                    <option value="owner" {{ old('role', $user->role) == 'owner' ? 'selected' : '' }}>{{ __('admin.user_edit.owner') }}</option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>{{ __('admin.user_edit.admin') }}</option>
                                    <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>{{ __('admin.user_edit.staff') }}</option>
                                </select>
                            </div>
                            @error('role')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ __('admin.user_edit.status') }}
                            </label>
                            <div class="mt-1">
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           name="active" 
                                           id="active" 
                                           value="1"
                                           {{ old('active', $user->active) ? 'checked' : '' }}
                                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                    <label for="active" class="ml-2 block text-sm text-gray-900">
                                        {{ __('admin.user_edit.active_user') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Current User Info -->
                    <div class="mt-6 bg-gray-50 border border-gray-200 rounded-md p-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">{{ __('admin.user_edit.current_information') }}</h4>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">{{ __('admin.user_edit.current_company') }}</span> 
                                    {{ $user->tenant->brand_name ?? __('admin.user_edit.no_company') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">{{ __('admin.user_edit.current_role') }}</span> 
                                    {{ ucfirst($user->role) }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">{{ __('admin.user_edit.current_status') }}</span> 
                                    {{ $user->active ? __('admin.common.active') : __('admin.common.inactive') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">{{ __('admin.user_edit.registered') }}</span> 
                                    {{ $user->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 sm:rounded-b-lg">
                    <a href="{{ route('admin.users.show', $user) }}" 
                       class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        {{ __('admin.user_edit.cancel') }}
                    </a>
                    <button type="submit" 
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        {{ __('admin.user_edit.update_user') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
