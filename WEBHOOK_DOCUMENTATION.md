# 🔗 Webhook System Documentation

## Overview

The Booking System includes a comprehensive webhook system that sends real-time notifications when booking events occur. The system supports HMAC signature verification, automatic retries with exponential backoff, and detailed logging.

## Features

- **HMAC SHA-256 Signatures**: All webhooks are signed for security
- **Automatic Retries**: Failed deliveries are retried with exponential backoff (1min → 5min → 15min)
- **Detailed Logging**: All webhook attempts are logged with response codes and timestamps
- **Multi-endpoint Support**: Each tenant can configure multiple webhook endpoints
- **Event Filtering**: Endpoints can subscribe to specific events
- **Multilingual Support**: Webhook payloads include locale information

## Events

The system sends webhooks for the following events:

### 1. `booking.created`
Triggered when a new booking is confirmed.

**Payload Example**:
```json
{
    "event": "booking.created",
    "booking_id": "550e8400-e29b-41d4-a716-446655440000",
    "tenant": "restaurant-ranch",
    "service_id": "123e4567-e89b-12d3-a456-426614174000",
    "start": "2025-01-15T19:30:00+01:00",
    "end": "2025-01-15T21:00:00+01:00",
    "customer": {
        "name": "Juan Pérez",
        "phone": "+34 123 456 789",
        "email": "juan@example.com"
    },
    "allocated_resources": [
        {
            "id": 1,
            "type": "TABLE",
            "label": "Mesa 4",
            "capacity": 4
        }
    ],
    "party_size": 4,
    "source": "flowise",
    "notes": "Mesa cerca de la ventana",
    "created_at": "2025-01-10T10:30:00+01:00",
    "locale": "es"
}
```

### 2. `booking.cancelled`
Triggered when a booking is cancelled.

**Payload Example**:
```json
{
    "event": "booking.cancelled",
    "booking_id": "550e8400-e29b-41d4-a716-446655440000",
    "tenant": "restaurant-ranch",
    "service_id": "123e4567-e89b-12d3-a456-426614174000",
    "start": "2025-01-15T19:30:00+01:00",
    "end": "2025-01-15T21:00:00+01:00",
    "customer": {
        "name": "Juan Pérez",
        "phone": "+34 123 456 789",
        "email": "juan@example.com"
    },
    "allocated_resources": [
        {
            "id": 1,
            "type": "TABLE",
            "label": "Mesa 4",
            "capacity": 4
        }
    ],
    "party_size": 4,
    "source": "flowise",
    "notes": "Mesa cerca de la ventana",
    "cancelled_at": "2025-01-12T14:20:00+01:00",
    "original_created_at": "2025-01-10T10:30:00+01:00",
    "locale": "es"
}
```

### 3. `booking.no_show`
Triggered when a booking is marked as no-show (future implementation).

**Payload Example**:
```json
{
    "event": "booking.no_show",
    "booking_id": "550e8400-e29b-41d4-a716-446655440000",
    "tenant": "restaurant-ranch",
    "service_id": "123e4567-e89b-12d3-a456-426614174000",
    "start": "2025-01-15T19:30:00+01:00",
    "end": "2025-01-15T21:00:00+01:00",
    "customer": {
        "name": "Juan Pérez",
        "phone": "+34 123 456 789",
        "email": "juan@example.com"
    },
    "allocated_resources": [
        {
            "id": 1,
            "type": "TABLE",
            "label": "Mesa 4",
            "capacity": 4
        }
    ],
    "party_size": 4,
    "source": "flowise",
    "notes": "Mesa cerca de la ventana",
    "no_show_at": "2025-01-15T19:45:00+01:00",
    "original_created_at": "2025-01-10T10:30:00+01:00",
    "locale": "es"
}
```

## Security

### HMAC Signature Verification

All webhooks are signed using HMAC SHA-256. The signature is included in the `X-Signature` header:

```
X-Signature: sha256=abc123def456...
```

**Verification Process**:

1. **Extract the signature** from the `X-Signature` header
2. **Canonicalize the JSON payload** (sort keys recursively)
3. **Compute HMAC SHA-256** using your webhook secret
4. **Compare signatures** using constant-time comparison

**Example Verification (PHP)**:
```php
function verifyWebhookSignature($payload, $signature, $secret) {
    $expectedSignature = 'sha256=' . hash_hmac('sha256', json_encode($payload, JSON_UNESCAPED_SLASHES), $secret);
    return hash_equals($expectedSignature, $signature);
}
```

**Example Verification (Node.js)**:
```javascript
const crypto = require('crypto');

function verifyWebhookSignature(payload, signature, secret) {
    const expectedSignature = 'sha256=' + crypto
        .createHmac('sha256', secret)
        .update(JSON.stringify(payload))
        .digest('hex');
    
    return crypto.timingSafeEqual(
        Buffer.from(signature),
        Buffer.from(expectedSignature)
    );
}
```

### Webhook Secrets

Each tenant can have a custom webhook secret. If not set, the system uses a fallback secret from configuration.

**Getting the Secret**:
- **Tenant-specific**: Use the secret configured for your tenant
- **Fallback**: Use `config('app.webhook_fallback_secret')` for testing

## Delivery and Retries

### Delivery Process

1. **Event Triggered**: Booking event occurs
2. **Endpoint Selection**: Find active endpoints subscribed to the event
3. **Payload Creation**: Build webhook payload with event data
4. **Signature Generation**: Sign payload with HMAC SHA-256
5. **HTTP POST**: Send to endpoint with 8-second timeout
6. **Logging**: Record attempt in webhook_logs table

### Retry Logic

Failed webhooks are automatically retried with exponential backoff:

- **Attempt 1**: Immediate
- **Attempt 2**: After 1 minute
- **Attempt 3**: After 5 minutes  
- **Attempt 4**: After 15 minutes
- **Final**: Mark as failed after 3 retries

**Retryable Errors**:
- HTTP 5xx server errors
- Network timeouts
- Connection errors

**Non-retryable Errors**:
- HTTP 4xx client errors
- Invalid payload format
- Authentication failures

## API Management

### Create Webhook Endpoint

```bash
curl -X POST \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "url": "https://your-app.com/webhooks/booking",
    "events": ["booking.created", "booking.cancelled"],
    "secret": "your-webhook-secret",
    "active": true
  }' \
  "https://your-tenant.local.test/api/v1/webhook-endpoints"
```

### List Webhook Endpoints

```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
  "https://your-tenant.local.test/api/v1/webhook-endpoints"
```

### Update Webhook Endpoint

```bash
curl -X PUT \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "url": "https://your-app.com/webhooks/booking",
    "events": ["booking.created"],
    "active": false
  }' \
  "https://your-tenant.local.test/api/v1/webhook-endpoints/1"
```

### Delete Webhook Endpoint

```bash
curl -X DELETE \
  -H "Authorization: Bearer YOUR_TOKEN" \
  "https://your-tenant.local.test/api/v1/webhook-endpoints/1"
```

### Test Webhook Endpoint

```bash
curl -X POST \
  -H "Authorization: Bearer YOUR_TOKEN" \
  "https://your-tenant.local.test/api/v1/webhook-endpoints/1/test"
```

## Testing

### Test Endpoints

The system provides test endpoints for development:

- **Success**: `POST /api/webhook-test/success` - Always returns 200
- **Error**: `POST /api/webhook-test/error` - Always returns 500
- **Verify**: `POST /api/webhook-test/verify` - Validates HMAC signature
- **Timeout**: `POST /api/webhook-test/timeout` - Simulates timeout

### Command Line Testing

```bash
# Test all endpoints for a tenant
php artisan webhook:test --tenant=your-tenant-id --event=booking.created

# Test specific endpoint
php artisan webhook:test --tenant=your-tenant-id --endpoint=1 --verify

# Test custom URL
php artisan webhook:test --tenant=your-tenant-id --url=https://httpbin.org/post --verify
```

### Example Test Payload

```json
{
    "event": "booking.created",
    "booking_id": "test-booking-123",
    "tenant": "your-tenant-id",
    "service_id": "test-service-123",
    "start": "2025-01-15T19:30:00+01:00",
    "end": "2025-01-15T21:00:00+01:00",
    "customer": {
        "name": "Test Customer",
        "phone": "+1234567890",
        "email": "test@example.com"
    },
    "allocated_resources": [
        {
            "id": 1,
            "type": "TABLE",
            "label": "Test Table",
            "capacity": 4
        }
    ],
    "party_size": 4,
    "source": "test",
    "notes": "This is a test webhook",
    "created_at": "2025-01-10T10:30:00+01:00",
    "locale": "en"
}
```

## Monitoring and Logs

### Webhook Logs

All webhook attempts are logged in the `webhook_logs` table:

```sql
SELECT 
    wl.id,
    we.url,
    wl.event,
    wl.response_code,
    wl.delivered_at,
    wl.retries,
    wl.created_at
FROM webhook_logs wl
JOIN webhook_endpoints we ON wl.endpoint_id = we.id
WHERE we.tenant_id = 'your-tenant-id'
ORDER BY wl.created_at DESC;
```

### Log Fields

- **endpoint_id**: Webhook endpoint ID
- **event**: Event type (booking.created, etc.)
- **payload**: Full webhook payload (JSON)
- **response_code**: HTTP response code
- **response_body**: Response body from endpoint
- **signature**: HMAC signature used
- **delivered_at**: Timestamp when successfully delivered
- **retries**: Number of retry attempts
- **created_at**: When the attempt was made

### Monitoring Queries

**Failed Deliveries**:
```sql
SELECT * FROM webhook_logs 
WHERE delivered_at IS NULL 
AND retries >= 3;
```

**Success Rate**:
```sql
SELECT 
    COUNT(*) as total_attempts,
    COUNT(delivered_at) as successful_deliveries,
    ROUND(COUNT(delivered_at) * 100.0 / COUNT(*), 2) as success_rate
FROM webhook_logs 
WHERE created_at >= NOW() - INTERVAL 24 HOUR;
```

## Best Practices

### Endpoint Implementation

1. **Verify Signatures**: Always verify HMAC signatures
2. **Idempotency**: Handle duplicate webhooks gracefully
3. **Fast Response**: Respond within 5 seconds
4. **Error Handling**: Return appropriate HTTP status codes
5. **Logging**: Log received webhooks for debugging

### Security

1. **Use HTTPS**: Always use secure endpoints
2. **Validate Payloads**: Verify payload structure and data
3. **Rate Limiting**: Implement rate limiting on your endpoints
4. **Secret Management**: Store webhook secrets securely

### Performance

1. **Async Processing**: Process webhooks asynchronously
2. **Queue Management**: Use job queues for heavy processing
3. **Database Optimization**: Index webhook logs appropriately
4. **Monitoring**: Set up alerts for failed deliveries

## Troubleshooting

### Common Issues

**Signature Verification Fails**:
- Check JSON canonicalization (key ordering)
- Verify webhook secret is correct
- Ensure no extra whitespace in payload

**Webhooks Not Delivered**:
- Check endpoint URL is accessible
- Verify endpoint returns 2xx status codes
- Check webhook_logs for error details

**Retries Not Working**:
- Ensure queue workers are running
- Check job queue configuration
- Verify retry logic in WebhookDispatchJob

### Debug Commands

```bash
# Check webhook logs
php artisan tinker
>>> App\Models\WebhookLog::latest()->take(10)->get();

# Test signature verification
php artisan webhook:test --tenant=your-tenant --verify

# Check queue status
php artisan queue:work --once
```

## Integration Examples

### Flowise Integration

```javascript
// Flowise webhook handler
app.post('/webhooks/booking', (req, res) => {
    const signature = req.headers['x-signature'];
    const payload = req.body;
    
    // Verify signature
    if (!verifyWebhookSignature(payload, signature, process.env.WEBHOOK_SECRET)) {
        return res.status(401).json({ error: 'Invalid signature' });
    }
    
    // Process booking event
    if (payload.event === 'booking.created') {
        // Update Flowise knowledge base
        // Send notification to staff
        // Update analytics
    }
    
    res.json({ status: 'received' });
});
```

### Zapier Integration

1. **Create Zapier Webhook**: Set up a webhook trigger in Zapier
2. **Configure Endpoint**: Use the Zapier webhook URL in your booking system
3. **Set Events**: Subscribe to `booking.created` and `booking.cancelled`
4. **Add Actions**: Connect to your preferred apps (Slack, email, CRM, etc.)

### Custom Application

```python
# Flask webhook handler
from flask import Flask, request, jsonify
import hmac
import hashlib
import json

app = Flask(__name__)

def verify_signature(payload, signature, secret):
    expected = 'sha256=' + hmac.new(
        secret.encode(),
        json.dumps(payload, separators=(',', ':')).encode(),
        hashlib.sha256
    ).hexdigest()
    return hmac.compare_digest(signature, expected)

@app.route('/webhooks/booking', methods=['POST'])
def handle_webhook():
    signature = request.headers.get('X-Signature')
    payload = request.get_json()
    
    if not verify_signature(payload, signature, 'your-webhook-secret'):
        return jsonify({'error': 'Invalid signature'}), 401
    
    # Process the webhook
    if payload['event'] == 'booking.created':
        # Handle new booking
        pass
    elif payload['event'] == 'booking.cancelled':
        # Handle cancellation
        pass
    
    return jsonify({'status': 'received'})

if __name__ == '__main__':
    app.run()
```

This webhook system provides a robust, secure, and reliable way to integrate the booking system with external applications and services.
