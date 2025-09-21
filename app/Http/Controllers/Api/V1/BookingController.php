<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\BookRequest;
use App\Http\Requests\Api\V1\CancelRequest;
use App\Models\Booking;
use App\Domain\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Booking Controller
 * 
 * Handles booking creation, cancellation, and retrieval
 * Implements tenant isolation and resource allocation
 */
class BookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService
    ) {}

    /**
     * Create a new booking
     * 
     * @param BookRequest $request
     * @return JsonResponse
     */
    public function store(BookRequest $request): JsonResponse
    {
        try {
            // Get validated data
            $validated = $request->validated();
            $customerData = $request->getCustomerData();
            
            // Create booking
            $booking = $this->bookingService->createBooking($validated, $customerData);
            
            // Format allocated resources
            $allocatedResources = $booking->resources->map(function ($resource) {
                return [
                    'id' => $resource->id,
                    'type' => $resource->type,
                    'label' => $resource->label,
                    'capacity' => $resource->capacity,
                ];
            });
            
            // Return success response
            return response()->json([
                'booking_id' => $booking->id,
                'status' => $booking->status,
                'allocated_resources' => $allocatedResources,
                'message' => __('api.booking_confirmed'),
                'locale' => app()->getLocale(),
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'locale' => app()->getLocale(),
            ], 400);
        }
    }
    
    /**
     * Cancel an existing booking
     * 
     * @param Request $request
     * @param string $id Booking UUID
     * @return JsonResponse
     */
    public function cancel(Request $request, string $id): JsonResponse
    {
        try {
            // Validate cancellation request
            $cancelRequest = CancelRequest::createFrom($request);
            $cancelRequest->setContainer(app());
            $cancelRequest->validateResolved();
            
            // Find booking
            $booking = $this->bookingService->getBooking($id);
            
            // Check authorization
            $this->authorize('cancel', $booking);
            
            // Cancel booking
            $cancelledBooking = $this->bookingService->cancelBooking(
                $booking, 
                $cancelRequest->validated()['motivo'] ?? null
            );
            
            // Return success response
            return response()->json([
                'status' => $cancelledBooking->status,
                'message' => __('api.booking_cancelled'),
                'locale' => app()->getLocale(),
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'locale' => app()->getLocale(),
            ], 400);
        }
    }
    
    /**
     * Get booking details
     * 
     * @param Request $request
     * @param string $id Booking UUID
     * @return JsonResponse
     */
    public function show(Request $request, string $id): JsonResponse
    {
        try {
            // Find booking
            $booking = $this->bookingService->getBooking($id);
            
            // Check authorization
            $this->authorize('view', $booking);
            
            // Format booking data
            $bookingData = [
                'id' => $booking->id,
                'status' => $booking->status,
                'start_at' => $booking->start_at->toISOString(),
                'end_at' => $booking->end_at->toISOString(),
                'party_size' => $booking->party_size,
                'source' => $booking->source,
                'notes' => $booking->notes,
                'created_at' => $booking->created_at->toISOString(),
                'service' => [
                    'id' => $booking->service->id,
                    'name' => $booking->service->name,
                    'duration_min' => $booking->service->duration_min,
                    'price_cents' => $booking->service->price_cents,
                ],
                'customer' => [
                    'id' => $booking->customer->id,
                    'name' => $booking->customer->name,
                    'email' => $booking->customer->email,
                    'phone' => $booking->customer->phone,
                ],
                'allocated_resources' => $booking->resources->map(function ($resource) {
                    return [
                        'id' => $resource->id,
                        'type' => $resource->type,
                        'label' => $resource->label,
                        'capacity' => $resource->capacity,
                        'location' => [
                            'id' => $resource->location->id,
                            'name' => $resource->location->name,
                        ],
                    ];
                }),
                'locale' => app()->getLocale(),
            ];
            
            return response()->json($bookingData);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'locale' => app()->getLocale(),
            ], 404);
        }
    }
}
