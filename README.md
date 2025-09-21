# Booking System - Multitenant Reservation Management

A comprehensive Laravel 11 multitenant booking system supporting restaurants, barber shops, beauty salons, and dental clinics with full multilingual support (ES/EN/NL).

## Features

- **Multitenant Architecture**: Single deployment for multiple businesses using subdomains
- **Universal Resource Model**: Supports tables, staff, rooms, chairs, and equipment
- **Smart Availability Engine**: Handles different business types with appropriate resource allocation
- **Multilingual Support**: Complete i18n for ES/EN/NL in UI, API, emails, and widget
- **REST API v1**: Secure API with Sanctum authentication and rate limiting
- **Webhook System**: Outgoing webhooks with HMAC signature for integrations
- **Embeddable Widget**: JavaScript widget for easy integration
- **Admin Panel**: Tenant-specific management interface
- **Queue Processing**: Background job processing with Horizon
- **Comprehensive Testing**: Pest-based test suite

## Quick Start

### Prerequisites

- Docker and Docker Compose
- Make (optional, for convenience commands)

### Installation

1. **Clone and setup**:
   ```bash
   git clone <repository-url>
   cd booking-system
   make install
   ```

2. **Start services**:
   ```bash
   make up
   ```

3. **Setup database**:
   ```bash
   make migrate
   make seed
   ```

4. **Access the system**:
   - Main app: http://localhost
   - Demo tenants: 
     - http://ranch.local.test (Restaurant)
     - http://beerta-barbers.local.test (Barber)
     - http://glow-beauty.local.test (Beauty)
     - http://smile-dental.local.test (Dental)

### Development Commands

```bash
make help          # Show all available commands
make up            # Start all services
make down          # Stop all services
make migrate       # Run migrations
make seed          # Seed demo data
make test          # Run tests
make shell         # Open shell in app container
make logs          # Show logs
```

## Architecture

### Business Types Supported

1. **Restaurants**: Table allocation with party size and capacity management
2. **Barber Shops**: Staff scheduling with buffer times
3. **Beauty Salons**: Staff and equipment allocation
4. **Dental Clinics**: Simultaneous staff and room booking

### Core Components

- **Domain Services**: `CapacityService`, `TableAllocator`, `BookingService`
- **Models**: Tenant-aware models with translatable fields
- **API**: RESTful endpoints with locale support
- **Webhooks**: Event-driven notifications with retry logic
- **Widget**: Embeddable JavaScript component

## API Usage

### Authentication

All API endpoints require a Bearer token:

```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
  "https://tenant.local.test/api/v1/availability?service_id=1&date=2025-09-22"
```

### Available Endpoints

- `GET /api/v1/availability` - Get available time slots
- `POST /api/v1/book` - Create a new booking
- `POST /api/v1/bookings/{id}/cancel` - Cancel a booking
- `GET /api/v1/bookings/{id}` - Get booking details

### Example: Check Availability (Dutch)

```bash
curl -H "Authorization: Bearer TENANT_TOKEN" \
  "https://ranch.local.test/api/v1/availability?service_id=svc_dinner&date=2025-09-22&party_size=4&locale=nl"
```

### Example: Create Booking (Spanish)

```bash
curl -X POST -H "Authorization: Bearer TENANT_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "service_id": "svc_dinner",
    "party_size": 4,
    "start": "2025-09-22T19:30:00+02:00",
    "customer": {
      "name": "Juan",
      "email": "juan@mail.com",
      "phone": "+31..."
    },
    "source": "flowise",
    "locale": "es"
  }' \
  "https://ranch.local.test/api/v1/book"
```

## Widget Integration

### Basic Usage

```html
<div id="reservas-widget" 
     data-tenant="ranch" 
     data-service="svc_dinner" 
     data-locale="es">
</div>
<script async src="https://app.tudominio.com/widget.js"></script>
```

### Configuration Options

- `data-tenant`: Tenant identifier
- `data-service`: Service ID to book
- `data-locale`: Language (es, en, nl)
- `data-location`: Optional location ID
- `data-party-size`: Default party size

## Webhook Integration

### Supported Events

- `booking.created` - New booking confirmed
- `booking.cancelled` - Booking cancelled
- `booking.no_show` - Customer no-show

### Webhook Payload Example

```json
{
  "event": "booking.created",
  "booking_id": "bk_123",
  "tenant": "ranch",
  "service_id": "svc_dinner",
  "start": "2025-09-22T19:30:00+02:00",
  "end": "2025-09-22T21:00:00+02:00",
  "customer": {
    "name": "Juan",
    "phone": "+31..."
  },
  "allocated_resources": [
    {"id": "T4-1", "type": "TABLE"}
  ],
  "locale": "es"
}
```

### HMAC Signature

Webhooks include an `X-Signature` header with HMAC-SHA256 signature:

```
X-Signature: sha256=abc123...
```

## Multilingual Support

### Supported Languages

- **Spanish (es)**: Complete translations
- **English (en)**: Complete translations  
- **Dutch (nl)**: Complete translations

### Locale Detection

The system detects locale in this order:

1. Query parameter: `?locale=es`
2. Accept-Language header
3. Tenant default locale
4. Global default (en)

### Translatable Content

- UI text and labels
- Validation messages
- Email templates
- API responses
- Widget interface

## Tenant Management

### Creating Tenants

```bash
make create-tenant TENANT=my-restaurant
```

### Tenant Configuration

Each tenant can configure:

- Brand name and settings
- Default locale and supported languages
- Timezone
- Availability rules
- Resources and services
- Webhook endpoints

## Testing

### Run Tests

```bash
make test
```

### Test Coverage

```bash
make test-coverage
```

### API Testing

```bash
make test-api
```

## Production Deployment

### Build and Deploy

```bash
make build
make deploy
```

### Environment Variables

Key production variables:

```env
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=pgsql
REDIS_HOST=redis
TENANCY_DOMAIN_ROOT=yourdomain.com
WEBHOOK_SECRET=your-secret-key
```

## Contributing

1. Follow SOLID principles
2. Write comprehensive tests
3. Use clear, descriptive comments
4. Maintain multilingual support
5. Follow Laravel conventions

## License

This project is proprietary software. All rights reserved.

## Support

For technical support or questions, please contact the development team.