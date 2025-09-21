# 🚀 API REST v1 Documentation

## Overview

The Booking System API v1 provides endpoints for checking availability, creating bookings, and managing reservations across multiple business verticals (restaurants, barber shops, beauty salons, dental clinics).

## Base URL

```
https://{tenant}.your-domain.com/api/v1
```

## Authentication

All API endpoints require authentication using Laravel Sanctum tokens. Include the token in the Authorization header:

```
Authorization: Bearer YOUR_API_TOKEN
```

## Rate Limiting

- **Rate Limit**: 60 requests per minute per user
- **Headers**: Rate limit information is included in response headers

## Multilingual Support

The API supports three languages: English (en), Spanish (es), and Dutch (nl).

### Setting Language

You can set the language in two ways:

1. **Query Parameter**: `?locale=es`
2. **Accept-Language Header**: `Accept-Language: es`

## Endpoints

### 1. Check Availability

Get available time slots for a service on a specific date.

**Endpoint**: `GET /api/v1/availability`

**Parameters**:
- `service_id` (required, UUID): Service ID
- `date` (required, date): Date in YYYY-MM-DD format
- `party_size` (optional, integer): Party size for restaurants (1-50)
- `location_id` (optional, UUID): Specific location ID
- `locale` (optional, string): Language (es, en, nl)

**Example Request**:
```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
     "https://restaurant.local.test/api/v1/availability?service_id=123e4567-e89b-12d3-a456-426614174000&date=2025-01-15&party_size=4&locale=es"
```

**Example Response**:
```json
{
    "service_id": "123e4567-e89b-12d3-a456-426614174000",
    "date": "2025-01-15",
    "slots": [
        {
            "start": "2025-01-15T19:00:00+01:00",
            "end": "2025-01-15T20:30:00+01:00"
        },
        {
            "start": "2025-01-15T20:30:00+01:00",
            "end": "2025-01-15T22:00:00+01:00"
        }
    ],
    "locale": "es"
}
```

### 2. Create Booking

Create a new booking with customer information and resource allocation.

**Endpoint**: `POST /api/v1/book`

**Request Body**:
```json
{
    "service_id": "123e4567-e89b-12d3-a456-426614174000",
    "start": "2025-01-15T19:30:00+01:00",
    "party_size": 4,
    "customer": {
        "name": "Juan Pérez",
        "email": "juan@example.com",
        "phone": "+34 123 456 789"
    },
    "notes": "Mesa cerca de la ventana",
    "source": "flowise",
    "locale": "es"
}
```

**Example Request**:
```bash
curl -X POST \
     -H "Authorization: Bearer YOUR_TOKEN" \
     -H "Content-Type: application/json" \
     -d '{
       "service_id": "123e4567-e89b-12d3-a456-426614174000",
       "start": "2025-01-15T19:30:00+01:00",
       "party_size": 4,
       "customer": {
         "name": "Juan Pérez",
         "email": "juan@example.com",
         "phone": "+34 123 456 789"
       },
       "notes": "Mesa cerca de la ventana",
       "source": "flowise",
       "locale": "es"
     }' \
     "https://restaurant.local.test/api/v1/book"
```

**Example Response**:
```json
{
    "booking_id": "550e8400-e29b-41d4-a716-446655440000",
    "status": "confirmed",
    "allocated_resources": [
        {
            "id": 1,
            "type": "TABLE",
            "label": "Mesa 4",
            "capacity": 4
        }
    ],
    "message": "Reserva confirmada exitosamente",
    "locale": "es"
}
```

### 3. Cancel Booking

Cancel an existing booking.

**Endpoint**: `POST /api/v1/bookings/{id}/cancel`

**Parameters**:
- `id` (required, UUID): Booking ID

**Request Body**:
```json
{
    "motivo": "Cambio de planes",
    "locale": "es"
}
```

**Example Request**:
```bash
curl -X POST \
     -H "Authorization: Bearer YOUR_TOKEN" \
     -H "Content-Type: application/json" \
     -d '{
       "motivo": "Cambio de planes",
       "locale": "es"
     }' \
     "https://restaurant.local.test/api/v1/bookings/550e8400-e29b-41d4-a716-446655440000/cancel"
```

**Example Response**:
```json
{
    "status": "cancelled",
    "message": "Reserva cancelada exitosamente",
    "locale": "es"
}
```

### 4. Get Booking Details

Retrieve detailed information about a specific booking.

**Endpoint**: `GET /api/v1/bookings/{id}`

**Parameters**:
- `id` (required, UUID): Booking ID

**Example Request**:
```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
     "https://restaurant.local.test/api/v1/bookings/550e8400-e29b-41d4-a716-446655440000?locale=es"
```

**Example Response**:
```json
{
    "id": "550e8400-e29b-41d4-a716-446655440000",
    "status": "confirmed",
    "start_at": "2025-01-15T19:30:00+01:00",
    "end_at": "2025-01-15T21:00:00+01:00",
    "party_size": 4,
    "source": "flowise",
    "notes": "Mesa cerca de la ventana",
    "created_at": "2025-01-10T10:30:00+01:00",
    "service": {
        "id": "123e4567-e89b-12d3-a456-426614174000",
        "name": "Cena",
        "duration_min": 90,
        "price_cents": 2500
    },
    "customer": {
        "id": 1,
        "name": "Juan Pérez",
        "email": "juan@example.com",
        "phone": "+34 123 456 789"
    },
    "allocated_resources": [
        {
            "id": 1,
            "type": "TABLE",
            "label": "Mesa 4",
            "capacity": 4,
            "location": {
                "id": 1,
                "name": "Sala Principal"
            }
        }
    ],
    "locale": "es"
}
```

## Error Responses

All error responses follow this format:

```json
{
    "error": "Error message in the requested language",
    "locale": "es"
}
```

### Common Error Codes

- **400 Bad Request**: Invalid request data or validation errors
- **401 Unauthorized**: Missing or invalid authentication token
- **403 Forbidden**: Insufficient permissions or tenant access denied
- **404 Not Found**: Resource not found
- **429 Too Many Requests**: Rate limit exceeded
- **500 Internal Server Error**: Server error

### Validation Errors

When validation fails, the response includes detailed field errors:

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "service_id": ["The service id field is required."],
        "date": ["The date must be a date after or equal to today."]
    },
    "locale": "es"
}
```

## Business Logic

### Restaurant Bookings
- Requires `party_size` parameter
- Allocates optimal table combinations
- Supports table capacity and combinability rules

### Barber/Beauty Bookings
- Allocates available staff members
- Checks for time conflicts and buffers
- No party size required

### Dental Bookings
- Requires both staff and room availability
- Allocates staff member and treatment room
- Checks for simultaneous availability

## Testing

### Creating API Tokens

Use the artisan command to create API tokens for testing:

```bash
php artisan api:create-token --tenant=your-tenant-id --name="Test User" --email="test@example.com"
```

### Example cURL Commands

**Check Availability (Spanish)**:
```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
     "https://restaurant.local.test/api/v1/availability?service_id=SERVICE_ID&date=2025-01-15&party_size=4&locale=es"
```

**Create Booking (English)**:
```bash
curl -X POST \
     -H "Authorization: Bearer YOUR_TOKEN" \
     -H "Content-Type: application/json" \
     -d '{
       "service_id": "SERVICE_ID",
       "start": "2025-01-15T19:30:00+01:00",
       "party_size": 4,
       "customer": {
         "name": "John Doe",
         "email": "john@example.com",
         "phone": "+1 555 123 4567"
       },
       "source": "flowise",
       "locale": "en"
     }' \
     "https://restaurant.local.test/api/v1/book"
```

**Cancel Booking (Dutch)**:
```bash
curl -X POST \
     -H "Authorization: Bearer YOUR_TOKEN" \
     -H "Content-Type: application/json" \
     -d '{
       "motivo": "Plannen gewijzigd",
       "locale": "nl"
     }' \
     "https://restaurant.local.test/api/v1/bookings/BOOKING_ID/cancel"
```

## Integration with Flowise

The API is designed to work seamlessly with Flowise for AI-powered booking management:

1. **Availability Check**: Flowise can check availability before suggesting times
2. **Booking Creation**: Flowise can create bookings with customer data
3. **Booking Management**: Flowise can cancel or modify existing bookings
4. **Multilingual Support**: Flowise can interact in the customer's preferred language

### Flowise Integration Example

```javascript
// Flowise can use the API like this:
const response = await fetch('https://restaurant.local.test/api/v1/availability', {
    method: 'GET',
    headers: {
        'Authorization': 'Bearer YOUR_TOKEN',
        'Accept-Language': 'es'
    },
    params: {
        service_id: 'SERVICE_ID',
        date: '2025-01-15',
        party_size: 4
    }
});
```

## Security

- All endpoints require authentication
- Tenant isolation ensures data separation
- Rate limiting prevents abuse
- Input validation prevents malicious data
- HTTPS required for production

## Support

For API support and questions, please contact the development team or refer to the main project documentation.
