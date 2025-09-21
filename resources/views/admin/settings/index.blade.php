@extends('admin.layouts.app')

@section('title', 'Configuración del Sistema - Super Admin')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Configuración del Sistema
                </h2>
                <p class="mt-1 text-sm text-gray-500">Gestiona la configuración global del sistema multitenant</p>
            </div>
        </div>

        <!-- System Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Tenants -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Empresas</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $system_stats['total_tenants'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Tenants -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Empresas Activas</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $system_stats['active_tenants'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Users -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Usuarios</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $system_stats['total_users'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Bookings -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Reservas</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $system_stats['total_bookings'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- System Settings -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Configuración del Sistema</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Ajustes globales del sistema</p>
                </div>
                <form method="POST" action="{{ route('admin.settings.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                        <div class="space-y-6">
                            <!-- Maintenance Mode -->
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">Modo de Mantenimiento</h4>
                                    <p class="text-sm text-gray-500">Activa el modo de mantenimiento para realizar actualizaciones</p>
                                </div>
                                <div class="ml-4">
                                    <input type="checkbox" 
                                           name="maintenance_mode" 
                                           id="maintenance_mode"
                                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                </div>
                            </div>

                            <!-- Max Tenants -->
                            <div>
                                <label for="max_tenants" class="block text-sm font-medium text-gray-700">
                                    Límite de Empresas
                                </label>
                                <div class="mt-1">
                                    <input type="number" 
                                           name="max_tenants" 
                                           id="max_tenants" 
                                           value="100"
                                           min="1"
                                           class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Número máximo de empresas permitidas</p>
                            </div>

                            <!-- Backup Frequency -->
                            <div>
                                <label for="backup_frequency" class="block text-sm font-medium text-gray-700">
                                    Frecuencia de Respaldo
                                </label>
                                <div class="mt-1">
                                    <select name="backup_frequency" 
                                            id="backup_frequency"
                                            class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option value="daily">Diario</option>
                                        <option value="weekly" selected>Semanal</option>
                                        <option value="monthly">Mensual</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Email Notifications -->
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">Notificaciones por Email</h4>
                                    <p class="text-sm text-gray-500">Recibir notificaciones sobre eventos del sistema</p>
                                </div>
                                <div class="ml-4">
                                    <input type="checkbox" 
                                           name="email_notifications" 
                                           id="email_notifications"
                                           checked
                                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" 
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Guardar Configuración
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- System Information -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Información del Sistema</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Estado actual del servidor</p>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Tiempo de Actividad</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $system_stats['system_uptime'] }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Uso de Disco</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $system_stats['disk_usage']['used'] }} / {{ $system_stats['disk_usage']['total'] }}
                                <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-red-600 h-2 rounded-full" style="width: {{ $system_stats['disk_usage']['percentage'] }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500">{{ $system_stats['disk_usage']['percentage'] }}% usado</span>
                            </dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Uso de Memoria</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $system_stats['memory_usage']['used'] }} / {{ $system_stats['memory_usage']['total'] }}
                                <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $system_stats['memory_usage']['percentage'] }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500">{{ $system_stats['memory_usage']['percentage'] }}% usado</span>
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Versión PHP</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ PHP_VERSION }}</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Versión Laravel</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ app()->version() }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Acciones Rápidas</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Herramientas de administración del sistema</p>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <!-- Backup -->
                        <a href="{{ route('admin.settings.backup') }}" 
                           class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-500 rounded-lg border border-gray-300 hover:border-gray-400">
                            <div>
                                <span class="rounded-lg inline-flex p-3 bg-red-50 text-red-700 ring-4 ring-white">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="mt-4">
                                <h3 class="text-lg font-medium">
                                    <span class="absolute inset-0" aria-hidden="true"></span>
                                    Respaldo
                                </h3>
                                <p class="mt-2 text-sm text-gray-500">Crear y gestionar respaldos del sistema</p>
                            </div>
                        </a>

                        <!-- Cache Clear -->
                        <button onclick="clearCache()" 
                                class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-500 rounded-lg border border-gray-300 hover:border-gray-400">
                            <div>
                                <span class="rounded-lg inline-flex p-3 bg-yellow-50 text-yellow-700 ring-4 ring-white">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="mt-4">
                                <h3 class="text-lg font-medium">
                                    <span class="absolute inset-0" aria-hidden="true"></span>
                                    Limpiar Cache
                                </h3>
                                <p class="mt-2 text-sm text-gray-500">Limpiar cache del sistema</p>
                            </div>
                        </button>

                        <!-- Logs -->
                        <a href="#" 
                           class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-500 rounded-lg border border-gray-300 hover:border-gray-400">
                            <div>
                                <span class="rounded-lg inline-flex p-3 bg-blue-50 text-blue-700 ring-4 ring-white">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="mt-4">
                                <h3 class="text-lg font-medium">
                                    <span class="absolute inset-0" aria-hidden="true"></span>
                                    Logs del Sistema
                                </h3>
                                <p class="mt-2 text-sm text-gray-500">Ver logs de errores y actividad</p>
                            </div>
                        </a>

                        <!-- Maintenance -->
                        <button onclick="toggleMaintenance()" 
                                class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-red-500 rounded-lg border border-gray-300 hover:border-gray-400">
                            <div>
                                <span class="rounded-lg inline-flex p-3 bg-orange-50 text-orange-700 ring-4 ring-white">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="mt-4">
                                <h3 class="text-lg font-medium">
                                    <span class="absolute inset-0" aria-hidden="true"></span>
                                    Mantenimiento
                                </h3>
                                <p class="mt-2 text-sm text-gray-500">Activar modo de mantenimiento</p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function clearCache() {
    if (confirm('¿Estás seguro de que quieres limpiar el cache del sistema?')) {
        // Aquí implementarías la llamada AJAX para limpiar el cache
        alert('Cache limpiado exitosamente');
    }
}

function toggleMaintenance() {
    if (confirm('¿Estás seguro de que quieres activar el modo de mantenimiento?')) {
        // Aquí implementarías la llamada AJAX para activar mantenimiento
        alert('Modo de mantenimiento activado');
    }
}
</script>
@endsection
