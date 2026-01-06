<?php

declare(strict_types=1);

namespace Tests\Support;

/**
 * Test helper utilities for generating signatures and headers
 * that match Buckaroo's authentication mechanisms
 */
class TestHelpers
{
    /**
     * Generate HTTP POST signature (SHA1) for webhook/push validation
     * Mirrors the logic in Handlers\Reply\HttpPost
     *
     * @param array $data Form data with brq_*, add_*, cust_* fields
     * @param string|null $secretKey Optional secret key override
     * @return string SHA1 signature
     */
    public static function generateHttpPostSignature(array $data, ?string $secretKey = null): string
    {
        $secretKey = $secretKey ?? $_ENV['BPE_SECRET_KEY'];

        $filtered = array_filter($data, function ($key) {
            $acceptable = ['brq', 'add', 'cust', 'BRQ', 'ADD', 'CUST'];
            $prefix = explode('_', $key)[0];

            return $key !== 'brq_signature' && $key !== 'BRQ_SIGNATURE'
                && in_array($prefix, $acceptable);
        }, ARRAY_FILTER_USE_KEY);

        ksort($filtered, SORT_FLAG_CASE | SORT_STRING);

        $dataString = '';
        foreach ($filtered as $key => $value) {
            $dataString .= $key . '=' . html_entity_decode((string) $value);
        }

        return sha1($dataString . $secretKey);
    }

    /**
     * Generate HMAC authentication header for API requests
     * Mirrors the logic in Handlers\HMAC\Generator
     *
     * @param array $data Request payload
     * @param string $uri Request URI
     * @param string|null $secretKey Optional secret key override
     * @param string|null $websiteKey Optional website key override
     * @return string HMAC header in format: websiteKey:hmac:nonce:timestamp
     */
    public static function generateHmacHeader(array $data, string $uri, ?string $secretKey = null, ?string $websiteKey = null): string
    {
        $secretKey = $secretKey ?? $_ENV['BPE_SECRET_KEY'];
        $websiteKey = $websiteKey ?? $_ENV['BPE_WEBSITE_KEY'];

        $nonce = bin2hex(random_bytes(16));
        $time = (string) time();
        $method = 'POST';

        $jsonData = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION);
        $base64Data = base64_encode(md5(mb_convert_encoding($jsonData, 'UTF-8', 'auto'), true));

        $normalizedUri = strtolower(urlencode(preg_replace('#^[^:/.]*[:/]+#i', '', $uri)));
        $hmacData = $websiteKey . $method . $normalizedUri . $time . $nonce . $base64Data;
        $hmac = base64_encode(hash_hmac('sha256', $hmacData, $secretKey, true));

        return "{$websiteKey}:{$hmac}:{$nonce}:{$time}";
    }

    /**
     * Create a successful transaction response fixture
     *
     * @param array $overrides Override specific fields
     * @return array
     */
    public static function successResponse(array $overrides = []): array
    {
        return array_merge([
            'Key' => 'TEST_TX_' . uniqid(),
            'Status' => [
                'Code' => ['Code' => 190, 'Description' => 'Success'],
                'SubCode' => ['Code' => 'S001', 'Description' => 'Transaction successful'],
                'DateTime' => date('Y-m-d\TH:i:s'),
            ],
            'RequiredAction' => null,
            'Services' => [],
            'Invoice' => 'INV-' . uniqid(),
            'ServiceCode' => 'creditcard',
            'IsTest' => true,
            'Currency' => 'EUR',
            'AmountDebit' => 10.00,
        ], $overrides);
    }

    /**
     * Create a redirect response fixture (e.g., 3D Secure, iDEAL)
     *
     * @param string $url Redirect URL
     * @param array $overrides Override specific fields
     * @return array
     */
    public static function redirectResponse(string $url, array $overrides = []): array
    {
        return self::successResponse(array_merge([
            'Status' => [
                'Code' => ['Code' => 791, 'Description' => 'Pending input'],
                'SubCode' => ['Code' => 'S002', 'Description' => 'Waiting for user'],
                'DateTime' => date('Y-m-d\TH:i:s'),
            ],
            'RequiredAction' => [
                'Name' => 'Redirect',
                'RedirectURL' => $url,
            ],
        ], $overrides));
    }

    /**
     * Create a pending response fixture
     *
     * @param array $overrides Override specific fields
     * @return array
     */
    public static function pendingResponse(array $overrides = []): array
    {
        return self::successResponse(array_merge([
            'Status' => [
                'Code' => ['Code' => 792, 'Description' => 'Pending processing'],
                'SubCode' => ['Code' => 'S003', 'Description' => 'Pending processing'],
                'DateTime' => date('Y-m-d\TH:i:s'),
            ],
        ], $overrides));
    }

    /**
     * Create a failed response fixture
     *
     * @param string $error Error message
     * @param array $overrides Override specific fields
     * @return array
     */
    public static function failedResponse(string $error = 'Transaction failed', array $overrides = []): array
    {
        return self::successResponse(array_merge([
            'Status' => [
                'Code' => ['Code' => 490, 'Description' => 'Failed'],
                'SubCode' => ['Code' => 'F001', 'Description' => $error],
                'DateTime' => date('Y-m-d\TH:i:s'),
            ],
        ], $overrides));
    }
}
