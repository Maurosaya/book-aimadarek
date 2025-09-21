<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Resource;
use App\Domain\BookingService;
use App\Domain\CapacityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Panel Booking Controller
 * 
 * Handles CRUD operations for bookings in the tenant panel
 * Includes search, filtering, and status management
 */
class BookingController extends Controller
{
    protected $bookingService;
    protected $capacityService;

    public function __construct(BookingService $bookingService, CapacityService $capacityService)
    {
        $this->bookingService = $bookingService;
        $this->capacityService = $capacityService;
    }

    /**
     * Display a listing of bookings with filters
     */
    public function index(Request $request)
    {
        $query = Booking::with(['service', 'customer', 'resources', 'createdBy'])
            ->where('tenant_id', tenant('id'));

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        if ($request->filled('customer_search')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->customer_search . '%')
                  ->orWhere('email', 'like', '%' . $request->customer_search . '%')
                  ->orWhere('phone', 'like', '%' . $request->customer_search . '%');
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('start_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('start_at', '<=', $request->date_to);
        }

        // Sort by start_at descending by default
        $bookings = $query->orderBy('start_at', 'desc')->paginate(20);

        // Get filter options
        $services = Service::where('tenant_id', tenant('id'))
            ->where('active', true)
            ->orderBy('name')
            ->get();

        $statusOptions = [
            Booking::STATUS_PENDING => __('panel.bookings.status_pending'),
            Booking::STATUS_CONFIRMED => __('panel.bookings.status_confirmed'),
            Booking::STATUS_CANCELLED => __('panel.bookings.status_cancelled'),
            Booking::STATUS_NO_SHOW => __('panel.bookings.status_no_show'),
        ];

        return view('panel.bookings.index', compact(
            'bookings',
            'services',
            'statusOptions'
        ));
    }

    /**
     * Show the form for creating a new booking
     */
    public function create(Request $request)
    {
        // Get services and customers for dropdowns
        $services = Service::where('tenant_id', tenant('id'))
            ->where('active', true)
            ->orderBy('name')
            ->get();

        $customers = Customer::where('tenant_id', tenant('id'))
            ->orderBy('name')
            ->get();

        // Pre-fill date if provided
        $defaultDate = $request->get('date', Carbon::now()->format('Y-m-d'));
        $defaultTime = $request->get('time', '12:00');

        return view('panel.bookings.create', compact(
            'services',
            'customers',
            'defaultDate',
            'defaultTime'
        ));
    }

    /**
     * Store a newly created booking
     */
    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'customer_id' => 'required|exists:customers,id',
            'start_at' => 'required|date|after:now',
            'party_size' => 'nullable|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $service = Service::findOrFail($request->service_id);
            $startAt = Carbon::parse($request->start_at);
            $endAt = $startAt->copy()->addMinutes($service->duration_min);

            // Check availability
            $availableSlots = $this->capacityService->getAvailableSlots(
                $service,
                $startAt->format('Y-m-d'),
                $startAt->format('H:i'),
                $request->party_size ?? 1
            );

            if (empty($availableSlots)) {
                return back()->withErrors(['start_at' => __('panel.bookings.no_availability')])
                           ->withInput();
            }

            // Create booking
            $booking = $this->bookingService->createBooking([
                'service_id' => $request->service_id,
                'customer_id' => $request->customer_id,
                'start_at' => $startAt,
                'end_at' => $endAt,
                'party_size' => $request->party_size,
                'notes' => $request->notes,
                'source' => 'panel',
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return redirect()->route('panel.bookings.show', $booking)
                           ->with('success', __('panel.bookings.created_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors(['error' => $e->getMessage()])
                       ->withInput();
        }
    }

    /**
     * Display the specified booking
     */
    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);

        $booking->load(['service', 'customer', 'resources', 'createdBy']);

        return view('panel.bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified booking
     */
    public function edit(Booking $booking)
    {
        $this->authorize('update', $booking);

        $booking->load(['service', 'customer', 'resources']);

        $services = Service::where('tenant_id', tenant('id'))
            ->where('active', true)
            ->orderBy('name')
            ->get();

        $customers = Customer::where('tenant_id', tenant('id'))
            ->orderBy('name')
            ->get();

        return view('panel.bookings.edit', compact(
            'booking',
            'services',
            'customers'
        ));
    }

    /**
     * Update the specified booking
     */
    public function update(Request $request, Booking $booking)
    {
        $this->authorize('update', $booking);

        $request->validate([
            'service_id' => 'required|exists:services,id',
            'customer_id' => 'required|exists:customers,id',
            'start_at' => 'required|date',
            'party_size' => 'nullable|integer|min:1',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:' . implode(',', [
                Booking::STATUS_PENDING,
                Booking::STATUS_CONFIRMED,
                Booking::STATUS_CANCELLED,
                Booking::STATUS_NO_SHOW,
            ]),
        ]);

        try {
            DB::beginTransaction();

            $service = Service::findOrFail($request->service_id);
            $startAt = Carbon::parse($request->start_at);
            $endAt = $startAt->copy()->addMinutes($service->duration_min);

            $booking->update([
                'service_id' => $request->service_id,
                'customer_id' => $request->customer_id,
                'start_at' => $startAt,
                'end_at' => $endAt,
                'party_size' => $request->party_size,
                'notes' => $request->notes,
                'status' => $request->status,
            ]);

            DB::commit();

            return redirect()->route('panel.bookings.show', $booking)
                           ->with('success', __('panel.bookings.updated_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors(['error' => $e->getMessage()])
                       ->withInput();
        }
    }

    /**
     * Remove the specified booking
     */
    public function destroy(Booking $booking)
    {
        $this->authorize('delete', $booking);

        try {
            $this->bookingService->cancelBooking($booking, 'Deleted from panel');
            
            return redirect()->route('panel.bookings.index')
                           ->with('success', __('panel.bookings.deleted_successfully'));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Cancel a booking
     */
    public function cancel(Request $request, Booking $booking)
    {
        $this->authorize('cancel', $booking);

        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            $this->bookingService->cancelBooking($booking, $request->reason ?? 'Cancelled from panel');
            
            return back()->with('success', __('panel.bookings.cancelled_successfully'));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Mark booking as no-show
     */
    public function noShow(Request $request, Booking $booking)
    {
        $this->authorize('markNoShow', $booking);

        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $booking->update([
                'status' => Booking::STATUS_NO_SHOW,
                'notes' => ($booking->notes ? $booking->notes . "\n\n" : '') . 
                          'No-show: ' . ($request->notes ?? 'Marked as no-show from panel'),
            ]);
            
            return back()->with('success', __('panel.bookings.marked_no_show'));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Get available time slots for a service and date
     */
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date',
            'party_size' => 'nullable|integer|min:1',
        ]);

        $service = Service::findOrFail($request->service_id);
        $slots = $this->capacityService->getAvailableSlots(
            $service,
            $request->date,
            null,
            $request->party_size ?? 1
        );

        return response()->json($slots);
    }
}