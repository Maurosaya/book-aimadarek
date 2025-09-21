@extends('panel.layouts.app')

@section('title', __('panel.dashboard.title'))

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- KPIs Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Today's Bookings -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-indigo-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">{{ __('panel.dashboard.today_bookings') }}</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $kpis['today_bookings'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Week Bookings -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">{{ __('panel.dashboard.week_bookings') }}</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $kpis['week_bookings'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- No Shows -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">{{ __('panel.dashboard.no_shows') }}</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $kpis['no_shows'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">{{ __('panel.dashboard.revenue') }}</dt>
                                <dd class="text-lg font-medium text-gray-900">€{{ number_format($kpis['revenue'], 2) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Actions -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                    <!-- Filters -->
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <form method="GET" action="{{ route('panel.dashboard') }}" class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                            <!-- Date Range -->
                            <div class="flex space-x-2">
                                <input type="date" 
                                       name="start_date" 
                                       value="{{ $startDate->format('Y-m-d') }}"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <input type="date" 
                                       name="end_date" 
                                       value="{{ $endDate->format('Y-m-d') }}"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <!-- Service Filter -->
                            <select name="service_id" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">{{ __('panel.dashboard.all_services') }}</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }}
                                    </option>
                                @endforeach
                            </select>

                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('panel.dashboard.filter') }}
                            </button>
                        </form>
                    </div>

                    <!-- Actions -->
                    <div class="flex space-x-3">
                        <a href="{{ route('panel.bookings.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            {{ __('panel.dashboard.new_booking') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar Grid -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('panel.dashboard.calendar') }}</h3>
                
                <!-- Week View -->
                <div class="grid grid-cols-7 gap-1 mb-4">
                    @php
                        $weekDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                        $currentWeek = $startDate->copy()->startOfWeek();
                    @endphp
                    
                    @foreach($weekDays as $day)
                        <div class="text-center py-2 text-sm font-medium text-gray-500">
                            {{ __('panel.dashboard.' . strtolower($day)) }}
                        </div>
                    @endforeach
                    
                    @for($i = 0; $i < 7; $i++)
                        @php
                            $date = $currentWeek->copy()->addDays($i);
                            $dayBookings = $bookingsByDate->get($date->format('Y-m-d'), collect());
                            $isToday = $date->isToday();
                            $isPast = $date->isPast();
                        @endphp
                        <div class="min-h-[120px] border border-gray-200 rounded-md p-2 {{ $isToday ? 'bg-blue-50 border-blue-300' : ($isPast ? 'bg-gray-50' : 'bg-white') }}">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium {{ $isToday ? 'text-blue-600' : 'text-gray-900' }}">
                                    {{ $date->format('d') }}
                                </span>
                                @if($dayBookings->count() > 0)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ $dayBookings->count() }}
                                    </span>
                                @endif
                            </div>
                            
                            <div class="space-y-1">
                                @foreach($dayBookings->take(3) as $booking)
                                    <div class="text-xs p-1 rounded {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        <div class="font-medium">{{ $booking->start_at->format('H:i') }}</div>
                                        <div class="truncate">{{ $booking->service->name }}</div>
                                        <div class="truncate">{{ $booking->customer->name }}</div>
                                    </div>
                                @endforeach
                                
                                @if($dayBookings->count() > 3)
                                    <div class="text-xs text-gray-500 text-center">
                                        +{{ $dayBookings->count() - 3 }} {{ __('panel.dashboard.more') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endfor
                </div>

                <!-- Legend -->
                <div class="flex flex-wrap gap-4 text-sm">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-100 rounded-full mr-2"></div>
                        <span class="text-gray-600">{{ __('panel.dashboard.confirmed') }}</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-yellow-100 rounded-full mr-2"></div>
                        <span class="text-gray-600">{{ __('panel.dashboard.pending') }}</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-red-100 rounded-full mr-2"></div>
                        <span class="text-gray-600">{{ __('panel.dashboard.cancelled') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        @if($bookings->count() > 0)
            <div class="mt-6 bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('panel.dashboard.recent_bookings') }}</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('panel.dashboard.date_time') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('panel.dashboard.service') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('panel.dashboard.customer') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('panel.dashboard.status') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('panel.dashboard.actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($bookings->take(10) as $booking)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div>{{ $booking->start_at->format('M d, Y') }}</div>
                                            <div class="text-gray-500">{{ $booking->start_at->format('H:i') }} - {{ $booking->end_at->format('H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $booking->service->name }}
                                            @if($booking->party_size)
                                                <span class="text-gray-500">({{ $booking->party_size }} {{ __('panel.dashboard.guests') }})</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div>{{ $booking->customer->name }}</div>
                                            <div class="text-gray-500">{{ $booking->customer->phone }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                                   ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                   ($booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                                {{ __('panel.dashboard.status_' . $booking->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('panel.bookings.show', $booking) }}" 
                                               class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                {{ __('panel.dashboard.view') }}
                                            </a>
                                            @if($currentUser->can('update', $booking))
                                                <a href="{{ route('panel.bookings.edit', $booking) }}" 
                                                   class="text-yellow-600 hover:text-yellow-900">
                                                    {{ __('panel.dashboard.edit') }}
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($bookings->count() > 10)
                        <div class="mt-4 text-center">
                            <a href="{{ route('panel.bookings.index') }}" 
                               class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                {{ __('panel.dashboard.view_all_bookings') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
