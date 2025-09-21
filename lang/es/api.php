<?php

return [
    'availability' => [
        'title' => 'Horarios Disponibles',
        'no_slots' => 'No hay horarios disponibles para la fecha seleccionada.',
        'service_not_found' => 'Servicio no encontrado.',
        'invalid_date' => 'Fecha inválida proporcionada.',
        'date_past' => 'No se puede verificar disponibilidad para fechas pasadas.',
    ],
    
    'booking' => [
        'created' => 'Reserva confirmada exitosamente',
        'cancelled' => 'Reserva cancelada exitosamente',
        'not_found' => 'Reserva no encontrada',
        'already_cancelled' => 'La reserva ya está cancelada',
        'cannot_cancel' => 'No se puede cancelar esta reserva',
        'conflict' => 'El horario ya no está disponible',
        'invalid_service' => 'Servicio inválido seleccionado',
        'invalid_time' => 'Horario inválido seleccionado',
        'party_size_required' => 'El número de personas es requerido para este servicio',
        'customer_required' => 'La información del cliente es requerida',
    ],
    
    'errors' => [
        'unauthorized' => 'Acceso no autorizado',
        'forbidden' => 'Acceso prohibido',
        'not_found' => 'Recurso no encontrado',
        'validation_failed' => 'Validación fallida',
        'server_error' => 'Error interno del servidor',
        'rate_limit' => 'Demasiadas solicitudes. Por favor, inténtalo de nuevo más tarde.',
    ],
    
    'messages' => [
        'booking_confirmed' => 'Tu reserva ha sido confirmada',
        'booking_cancelled' => 'Tu reserva ha sido cancelada',
        'booking_reminder' => 'Recordatorio: Tienes una reserva mañana',
        'booking_no_show' => 'Perdiste tu reserva',
    ],
];
