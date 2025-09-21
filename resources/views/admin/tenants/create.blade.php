@extends('admin.layouts.app')

@section('title', 'Crear Nueva Empresa')

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
                                <a href="{{ route('admin.tenants.index') }}" class="text-gray-400 hover:text-gray-500">
                                    <svg class="flex-shrink-0 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <span class="sr-only">Empresas</span>
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                <span class="ml-4 text-sm font-medium text-gray-500">Nueva Empresa</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2 class="mt-2 text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Crear Nueva Empresa
                </h2>
                <p class="mt-1 text-sm text-gray-500">Registra una nueva empresa en el sistema</p>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form method="POST" action="{{ route('admin.tenants.store') }}" class="space-y-6">
                    @csrf

                    <!-- Company Information -->
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <div>
                            <label for="id" class="block text-sm font-medium text-gray-700">
                                ID de la Empresa <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <input type="text" 
                                       name="id" 
                                       id="id" 
                                       value="{{ old('id') }}"
                                       class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md @error('id') border-red-500 @enderror"
                                       placeholder="ej: mi-restaurante"
                                       required>
                                <p class="mt-1 text-xs text-gray-500">Identificador único (solo letras, números y guiones)</p>
                            </div>
                            @error('id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="brand_name" class="block text-sm font-medium text-gray-700">
                                Nombre de la Empresa <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <input type="text" 
                                       name="brand_name" 
                                       id="brand_name" 
                                       value="{{ old('brand_name') }}"
                                       class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md @error('brand_name') border-red-500 @enderror"
                                       placeholder="Mi Restaurante S.L."
                                       required>
                            </div>
                            @error('brand_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Localization -->
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                        <div>
                            <label for="default_locale" class="block text-sm font-medium text-gray-700">
                                Idioma Principal <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <select name="default_locale" 
                                        id="default_locale"
                                        class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md @error('default_locale') border-red-500 @enderror"
                                        required>
                                    <option value="">Seleccionar idioma</option>
                                    <option value="es" {{ old('default_locale') === 'es' ? 'selected' : '' }}>Español</option>
                                    <option value="en" {{ old('default_locale') === 'en' ? 'selected' : '' }}>English</option>
                                    <option value="nl" {{ old('default_locale') === 'nl' ? 'selected' : '' }}>Nederlands</option>
                                </select>
                            </div>
                            @error('default_locale')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="supported_locales" class="block text-sm font-medium text-gray-700">
                                Idiomas Soportados <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="supported_locales[]" value="es" 
                                           {{ in_array('es', old('supported_locales', [])) ? 'checked' : '' }}
                                           class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Español</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="supported_locales[]" value="en" 
                                           {{ in_array('en', old('supported_locales', [])) ? 'checked' : '' }}
                                           class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">English</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="supported_locales[]" value="nl" 
                                           {{ in_array('nl', old('supported_locales', [])) ? 'checked' : '' }}
                                           class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Nederlands</span>
                                </label>
                            </div>
                            @error('supported_locales')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="timezone" class="block text-sm font-medium text-gray-700">
                                Zona Horaria <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <select name="timezone" 
                                        id="timezone"
                                        class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md @error('timezone') border-red-500 @enderror"
                                        required>
                                    <option value="">Seleccionar zona horaria</option>
                                    <option value="Europe/Madrid" {{ old('timezone') === 'Europe/Madrid' ? 'selected' : '' }}>Europe/Madrid</option>
                                    <option value="Europe/Amsterdam" {{ old('timezone') === 'Europe/Amsterdam' ? 'selected' : '' }}>Europe/Amsterdam</option>
                                    <option value="Europe/London" {{ old('timezone') === 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                                    <option value="America/New_York" {{ old('timezone') === 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                                </select>
                            </div>
                            @error('timezone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Owner Information -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Propietario</h3>
                        
                        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                            <div>
                                <label for="owner_name" class="block text-sm font-medium text-gray-700">
                                    Nombre del Propietario <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <input type="text" 
                                           name="owner_name" 
                                           id="owner_name" 
                                           value="{{ old('owner_name') }}"
                                           class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md @error('owner_name') border-red-500 @enderror"
                                           placeholder="Juan Pérez"
                                           required>
                                </div>
                                @error('owner_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="owner_email" class="block text-sm font-medium text-gray-700">
                                    Email del Propietario <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <input type="email" 
                                           name="owner_email" 
                                           id="owner_email" 
                                           value="{{ old('owner_email') }}"
                                           class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md @error('owner_email') border-red-500 @enderror"
                                           placeholder="juan@mi-restaurante.com"
                                           required>
                                </div>
                                @error('owner_email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Preview -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Vista Previa</h4>
                        <div class="text-sm text-gray-600">
                            <p><strong>Dominio:</strong> <span id="preview-domain" class="font-mono">mi-empresa.book.aimadarek.com</span></p>
                            <p><strong>Panel:</strong> <span id="preview-panel" class="font-mono">https://mi-empresa.book.aimadarek.com/panel</span></p>
                            <p><strong>Widget:</strong> <span id="preview-widget" class="font-mono">https://mi-empresa.book.aimadarek.com</span></p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.tenants.index') }}" 
                           class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Crear Empresa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const idInput = document.getElementById('id');
    const previewDomain = document.getElementById('preview-domain');
    const previewPanel = document.getElementById('preview-panel');
    const previewWidget = document.getElementById('preview-widget');

    function updatePreview() {
        const id = idInput.value || 'mi-empresa';
        const domain = `${id}.book.aimadarek.com`;
        
        previewDomain.textContent = domain;
        previewPanel.textContent = `https://${domain}/panel`;
        previewWidget.textContent = `https://${domain}`;
    }

    idInput.addEventListener('input', updatePreview);
    updatePreview();
});
</script>
@endsection
