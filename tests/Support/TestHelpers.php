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
     * Generate a realistic Buckaroo transaction key
     * Format: 32-character alphanumeric string (uppercase)
     *
     * @param string|null $suffix Optional suffix for specific test scenarios
     * @return string Transaction key in Buckaroo format
     */
    public static function generateTransactionKey(?string $suffix = null): string
    {
        return strtoupper(bin2hex(random_bytes(16)));
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
            'Key' => self::generateTransactionKey(),
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
