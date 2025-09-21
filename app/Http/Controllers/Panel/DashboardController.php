<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Panel Dashboard Controller
 * 
 * Provides dashboard data and calendar view for tenant panel
 * Includes KPIs and booking management functionality
 */
class DashboardController extends Controller
{
    /**
     * Show dashboard with calendar and KPIs
     */
    public function index(Request $request)
    {
        $tenant = tenancy()->tenant;
        $currentUser = auth()->user();

        // Get date range (default to current week)
        $startDate = $request->get('start_date', Carbon::now()->startOfWeek());
        $endDate = $request->get('end_date', Carbon::now()->endOfWeek());
        
        // Convert string dates to Carbon instances
        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate);
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate);
        }

        // Get bookings for the date range
        $bookings = $this->getBookingsForDateRange($startDate, $endDate, $request->get('service_id'));

        // Calculate KPIs
        $kpis = $this->calculateKPIs($startDate, $endDate);

        // Get services for filter dropdown
        $services = Service::where('tenant_id', $tenant->id)
            ->where('active', true)
            ->orderBy('name')
            ->get();

        // Group bookings by date for calendar view
        $bookingsByDate = $bookings->groupBy(function ($booking) {
            return $booking->start_at->format('Y-m-d');
        });

        return view('panel.dashboard', compact(
            'tenant',
            'currentUser',
            'bookings',
            'bookingsByDate',
            'kpis',
            'services',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Get bookings for date range with filters
     */
    private function getBookingsForDateRange(Carbon $startDate, Carbon $endDate, ?string $serviceId = null)
    {
        $query = Booking::with(['service', 'customer', 'resources'])
            ->where('tenant_id', tenant('id'))
            ->whereBetween('start_at', [$startDate, $endDate])
            ->orderBy('start_at');

        if ($serviceId) {
            $query->where('service_id', $serviceId);
        }

        return $query->get();
    }

    /**
     * Calculate KPIs for dashboard
     */
    private function calculateKPIs(Carbon $startDate, Carbon $endDate): array
    {
        $tenantId = tenant('id');
        $today = Carbon::today();

        // Today's bookings
        $todayBookings = Booking::where('tenant_id', $tenantId)
            ->whereDate('start_at', $today)
            ->count();

        // This week's bookings
        $weekBookings = Booking::where('tenant_id', $tenantId)
            ->whereBetween('start_at', [$startDate, $endDate])
            ->count();

        // No-shows this week
        $noShows = Booking::where('tenant_id', $tenantId)
            ->whereBetween('start_at', [$startDate, $endDate])
            ->where('status', Booking::STATUS_NO_SHOW)
            ->count();

        // Cancelled this week
        $cancelled = Booking::where('tenant_id', $tenantId)
            ->whereBetween('start_at', [$startDate, $endDate])
            ->where('status', Booking::STATUS_CANCELLED)
            ->count();

        // Revenue this week (if prices are set)
        $revenue = Booking::where('bookings.tenant_id', $tenantId)
            ->whereBetween('bookings.start_at', [$startDate, $endDate])
            ->where('bookings.status', Booking::STATUS_CONFIRMED)
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->sum('services.price_cents') / 100; // Convert cents to euros

        return [
            'today_bookings' => $todayBookings,
            'week_bookings' => $weekBookings,
            'no_shows' => $noShows,
            'cancelled' => $cancelled,
            'revenue' => $revenue,
        ];
    }

    /**
     * Get calendar data for AJAX requests
     */
    public function calendar(Request $request)
    {
        $startDate = Carbon::parse($request->get('start', Carbon::now()->startOfMonth()));
        $endDate = Carbon::parse($request->get('end', Carbon::now()->endOfMonth()));
        
        $bookings = Booking::with(['service', 'customer'])
            ->where('tenant_id', tenant('id'))
            ->whereBetween('start_at', [$startDate, $endDate])
            ->get();

        $events = $bookings->map(function ($booking) {
            return [
                'id' => $booking->id,
                'title' => $booking->service->name . ' - ' . $booking->customer->name,
                'start' => $booking->start_at->toISOString(),
                'end' => $booking->end_at->toISOString(),
                'status' => $booking->status,
                'color' => $this->getStatusColor($booking->status),
                'extendedProps' => [
                    'service' => $booking->service->name,
                    'customer' => $booking->customer->name,
                    'phone' => $booking->customer->phone,
                    'notes' => $booking->notes,
                    'party_size' => $booking->party_size,
                ]
            ];
        });

        return response()->json($events);
    }

    /**
     * Get color for booking status
     */
    private function getStatusColor(string $status): string
    {
        return match ($status) {
            Booking::STATUS_PENDING => '#fbbf24', // yellow
            Booking::STATUS_CONFIRMED => '#10b981', // green
            Booking::STATUS_CANCELLED => '#ef4444', // red
            Booking::STATUS_NO_SHOW => '#6b7280', // gray
            default => '#3b82f6', // blue
        };
    }
}