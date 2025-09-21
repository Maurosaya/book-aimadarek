@extends('admin.layouts.app')

@section('title', __('admin.backup.title') . ' - Super Admin')

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
                                <a href="{{ route('admin.settings.index') }}" class="text-gray-400 hover:text-gray-500">
                                    <svg class="flex-shrink-0 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                    </svg>
                                    <span class="sr-only">{{ __('admin.nav.settings') }}</span>
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <a href="{{ route('admin.settings.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">{{ __('admin.nav.settings') }}</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-4 text-sm font-medium text-gray-500">{{ __('admin.backup.backups') }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2 class="mt-2 text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    {{ __('admin.backup.backup_management_title') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">{{ __('admin.backup.backup_management_subtitle') }}</p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <form method="POST" action="{{ route('admin.settings.backup.create') }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" clip-rule="evenodd" />
                        </svg>
                        {{ __('admin.backup.create_backup') }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Backup Information -->
        <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">{{ __('admin.backup.backup_information') }}</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>{{ __('admin.backup.backup_information_text') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Backup Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Backups -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">{{ __('admin.backup.total_backups') }}</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ count($backups) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Last Backup -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">{{ __('admin.backup.last_backup') }}</dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    @if(count($backups) > 0)
                                        {{ \Carbon\Carbon::parse($backups[0]['created_at'])->diffForHumans() }}
                                    @else
                                        {{ __('admin.backup.never') }}
                                    @endif
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Size -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">{{ __('admin.backup.total_size') }}</dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    @php
                                        $totalSize = 0;
                                        foreach($backups as $backup) {
                                            $size = str_replace([' MB', ' KB', ' GB'], '', $backup['size']);
                                            $unit = substr($backup['size'], -2);
                                            if($unit === 'MB') $totalSize += $size;
                                            elseif($unit === 'GB') $totalSize += $size * 1024;
                                            elseif($unit === 'KB') $totalSize += $size / 1024;
                                        }
                                    @endphp
                                    {{ number_format($totalSize, 1) }} MB
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Backup List -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('admin.backup.backup_list') }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ __('admin.backup.backup_list_subtitle') }}</p>
            </div>
            @if(count($backups) > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($backups as $backup)
                <li>
                    <div class="px-4 py-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="flex items-center">
                                    <p class="text-sm font-medium text-gray-900">{{ $backup['name'] }}</p>
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ __('admin.backup.completed') }}
                                    </span>
                                </div>
                                <div class="mt-1">
                                    <p class="text-sm text-gray-500">
                                        {{ __('admin.backup.created') }} {{ \Carbon\Carbon::parse($backup['created_at'])->format('d/m/Y H:i') }}
                                        • {{ __('admin.backup.size') }} {{ $backup['size'] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <!-- Download -->
                            <button onclick="downloadBackup('{{ $backup['name'] }}')" 
                                    class="inline-flex items-center px-3 py-1 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="-ml-0.5 mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                {{ __('admin.backup.download') }}
                            </button>

                            <!-- Restore -->
                            <button onclick="restoreBackup('{{ $backup['name'] }}')" 
                                    class="inline-flex items-center px-3 py-1 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="-ml-0.5 mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                {{ __('admin.backup.restore') }}
                            </button>

                            <!-- Delete -->
                            <button onclick="deleteBackup('{{ $backup['name'] }}')" 
                                    class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="-ml-0.5 mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                {{ __('admin.backup.delete') }}
                            </button>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
            @else
            <div class="px-4 py-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('admin.backup.no_backups') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('admin.backup.no_backups_description') }}</p>
                <div class="mt-6">
                    <form method="POST" action="{{ route('admin.settings.backup.create') }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" clip-rule="evenodd" />
                            </svg>
                            {{ __('admin.backup.create_first_backup') }}
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>

        <!-- Backup Settings -->
        <div class="mt-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('admin.backup.backup_settings') }}</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ __('admin.backup.backup_settings_subtitle') }}</p>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                    <form method="POST" action="{{ route('admin.settings.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="backup_frequency" class="block text-sm font-medium text-gray-700">
                                    {{ __('admin.backup.backup_frequency') }}
                                </label>
                                <select name="backup_frequency" 
                                        id="backup_frequency"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                                    <option value="daily">{{ __('admin.backup.daily') }}</option>
                                    <option value="weekly" selected>{{ __('admin.backup.weekly') }}</option>
                                    <option value="monthly">{{ __('admin.backup.monthly') }}</option>
                                </select>
                            </div>
                            <div>
                                <label for="backup_retention" class="block text-sm font-medium text-gray-700">
                                    {{ __('admin.backup.retention_days') }}
                                </label>
                                <input type="number" 
                                       name="backup_retention" 
                                       id="backup_retention" 
                                       value="30"
                                       min="1"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                            </div>
                        </div>
                        <div class="mt-6">
                            <button type="submit" 
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                {{ __('admin.backup.save_settings') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function downloadBackup(filename) {
    // Implementar descarga del respaldo
    alert('{{ __('admin.notifications.downloading_backup', ['filename' => '']) }}' + filename);
}

function restoreBackup(filename) {
    if (confirm('{{ __('admin.backup.restore_confirmation') }}')) {
        // Implementar restauración del respaldo
        alert('{{ __('admin.notifications.restoring_backup', ['filename' => '']) }}' + filename);
    }
}

function deleteBackup(filename) {
    if (confirm('{{ __('admin.backup.delete_confirmation') }}')) {
        // Implementar eliminación del respaldo
        alert('{{ __('admin.notifications.deleting_backup', ['filename' => '']) }}' + filename);
    }
}
</script>
@endsection
