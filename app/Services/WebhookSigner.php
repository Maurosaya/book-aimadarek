<?php

namespace App\Services;

/**
 * Webhook Signer Service
 * 
 * Handles HMAC SHA-256 signing of webhook payloads
 * Ensures consistent JSON canonicalization for signature verification
 */
class WebhookSigner
{
    /**
     * Sign a payload with HMAC SHA-256
     * 
     * @param array $payload The payload to sign
     * @param string $secret The webhook secret
     * @return string The signature in format "sha256=<hex>"
     */
    public function sign(array $payload, string $secret): string
    {
        $body = $this->canonicalizeJson($payload);
        $signature = hash_hmac('sha256', $body, $secret);
        
        return "sha256={$signature}";
    }

    /**
     * Verify a signature against a payload
     * 
     * @param array $payload The payload to verify
     * @param string $signature The signature to verify (format: "sha256=<hex>")
     * @param string $secret The webhook secret
     * @return bool True if signature is valid
     */
    public function verify(array $payload, string $signature, string $secret): bool
    {
        $expectedSignature = $this->sign($payload, $secret);
        
        // Use hash_equals for timing attack protection
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Canonicalize JSON to ensure consistent ordering
     * 
     * @param array $data The data to canonicalize
     * @return string Canonicalized JSON string
     */
    private function canonicalizeJson(array $data): string
    {
        // Sort array keys recursively to ensure consistent ordering
        $this->sortArrayKeys($data);
        
        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Recursively sort array keys
     * 
     * @param array &$array The array to sort (passed by reference)
     */
    private function sortArrayKeys(array &$array): void
    {
        ksort($array);
        
        foreach ($array as &$value) {
            if (is_array($value)) {
                $this->sortArrayKeys($value);
            }
        }
    }

    /**
     * Extract signature from header value
     * 
     * @param string $headerValue The X-Signature header value
     * @return string|null The signature without the "sha256=" prefix
     */
    public function extractSignature(string $headerValue): ?string
    {
        if (str_starts_with($headerValue, 'sha256=')) {
            return substr($headerValue, 7);
        }
        
        return null;
    }

    /**
     * Validate payload size
     * 
     * @param array $payload The payload to validate
     * @param int $maxSize Maximum size in bytes (default: 128KB)
     * @return bool True if payload is within size limit
     */
    public function validatePayloadSize(array $payload, int $maxSize = 131072): bool
    {
        $jsonString = json_encode($payload);
        return strlen($jsonString) <= $maxSize;
    }

    /**
     * Sanitize sensitive data from payload
     * 
     * @param array $payload The payload to sanitize
     * @return array Sanitized payload
     */
    public function sanitizePayload(array $payload): array
    {
        $sanitized = $payload;
        
        // Remove or mask sensitive fields
        $sensitiveFields = ['password', 'token', 'secret', 'key', 'ssn', 'credit_card'];
        
        $this->recursiveSanitize($sanitized, $sensitiveFields);
        
        return $sanitized;
    }

    /**
     * Recursively sanitize sensitive data
     * 
     * @param array &$data The data to sanitize (passed by reference)
     * @param array $sensitiveFields Fields to sanitize
     */
    private function recursiveSanitize(array &$data, array $sensitiveFields): void
    {
        foreach ($data as $key => &$value) {
            if (is_array($value)) {
                $this->recursiveSanitize($value, $sensitiveFields);
            } elseif (is_string($key) && in_array(strtolower($key), $sensitiveFields)) {
                $value = '[REDACTED]';
            }
        }
    }
}
