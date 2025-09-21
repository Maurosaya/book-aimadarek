<?php

return [
    'availability' => [
        'title' => 'Available Time Slots',
        'no_slots' => 'No available time slots for the selected date.',
        'service_not_found' => 'Service not found.',
        'invalid_date' => 'Invalid date provided.',
        'date_past' => 'Cannot check availability for past dates.',
    ],
    
    'booking' => [
        'created' => 'Booking confirmed successfully',
        'cancelled' => 'Booking cancelled successfully',
        'not_found' => 'Booking not found',
        'already_cancelled' => 'Booking is already cancelled',
        'cannot_cancel' => 'Cannot cancel this booking',
        'conflict' => 'Time slot is no longer available',
        'invalid_service' => 'Invalid service selected',
        'invalid_time' => 'Invalid time slot selected',
        'party_size_required' => 'Party size is required for this service',
        'customer_required' => 'Customer information is required',
    ],
    
    'errors' => [
        'unauthorized' => 'Unauthorized access',
        'forbidden' => 'Access forbidden',
        'not_found' => 'Resource not found',
        'validation_failed' => 'Validation failed',
        'server_error' => 'Internal server error',
        'rate_limit' => 'Too many requests. Please try again later.',
    ],
    
    'messages' => [
        'booking_confirmed' => 'Your booking has been confirmed',
        'booking_cancelled' => 'Your booking has been cancelled',
        'booking_reminder' => 'Reminder: You have a booking tomorrow',
        'booking_no_show' => 'You missed your booking',
    ],
];
