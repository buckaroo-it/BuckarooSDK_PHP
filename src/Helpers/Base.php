<?php

declare(strict_types=1);

namespace Buckaroo\Helpers;

use Closure;
use Countable;
use InvalidArgumentException;

class Base
{
    public static function blank($value): bool
    {
        if (is_null($value)) {
            return true;
        }

        if (is_string($value)) {
            return trim($value) === '';
        }

        if (is_numeric($value) || is_bool($value)) {
            return false;
        }

        if ($value instanceof Countable) {
            return count($value) === 0;
        }

        return empty($value);
    }

    public static function stringRandom(int $length = 16): string
    {
        $chars = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789');
        $str   = "";

        for ($i = 0; $i < $length; $i++) {
            $key = array_rand($chars);
            $str .= $chars[$key];
        }

        return $str;
    }

    public static function stringRemoveStart(string $haystack, string $needle): string
    {
        if (static::stringStartsWith($haystack, $needle)) {
            return mb_substr($haystack, mb_strlen($needle));
        }

        return $haystack;
    }

    public static function stringContains(string $haystack, string $needle): bool
    {
        return mb_stripos($haystack, $needle) !== false;
    }

    public static function stringContainsDigits(string $haystack): bool
    {
        return !!preg_match('/\\d/', $haystack);
    }

    public static function stringContainsAlpha(string $haystack): bool
    {
        return !!preg_match('/[a-zA-Z]/', $haystack);
    }

    public static function stringStartsWith(string $haystack, string $needle): bool
    {
        return mb_substr($haystack, 0, mb_strlen($needle)) == $needle;
    }

    public static function stringGetDigits(string $haystack): array
    {
        preg_match_all('!\d+!', $haystack, $matches);
        return static::arrayFlatten($matches);
    }

    public static function stringGetAlpha(string $haystack): array
    {
        preg_match_all('![a-zA-Z]+!', $haystack, $matches);
        return static::arrayFlatten($matches);
    }

    public static function stringUnderscoreToCamelCase(string $str): string
    {
        $func = function ($c) {
            return strtoupper($c[1]);
        };

        return preg_replace_callback('/_([a-zA-Z])/', $func, $str);
    }

    public static function stringCamelCaseToUnderscore(string $str): string
    {
        return ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', $str)), '_');
    }

    public static function stringFormatPhone(string $phone): string
    {
        $digits = implode('', static::stringGetDigits($phone));
        return $digits;
    }

    public static function priceToFloat(string $price): float
    {
        return floatval(str_replace(',', '.', $price));
    }

    public static function floatToPrice(float $number, string $decimal = ','): string
    {
        if (!in_array($decimal, ['.', ','])) {
            throw new InvalidArgumentException('floatToPrice should have a dot or comma as decimal point');
        }

        $number = (string) round($number, 2);

        // Number is:
        // 23
        // 23.6
        // 23.64

        $number = str_replace('.', $decimal, $number);

        $index = stripos($number, $decimal);

        // 23
        if ($index === false) {
            return $number . $decimal . '00';
        }

        // 23.64
        if ((strlen($number) - $index) === 3) {
            return $number;
        }

        // 23.6
        return $number . '0';
    }

    public static function stringTransliteration(string $str): string
    {
        $oldLocale = setlocale(LC_CTYPE, 0);
        setlocale(LC_CTYPE, 'en_US.UTF8');

        $newStr = iconv('utf-8', 'ascii//TRANSLIT', $str);

        setlocale(LC_CTYPE, $oldLocale);

        return $newStr;
    }

    /**
     * Map over an array
     * (But with logical argument order and expose key in callback)
     *
     * @param  array  $array
     * @param  Closure $callback function($value, $key)
     * @return mixed
     */
    public static function arrayMap(array $array, Closure $callback)
    {
        $newAttributes = [];

        foreach ($array as $key => $value) {
            $newAttributes[$key] = call_user_func($callback, $value, $key);
        }

        return $newAttributes;
    }

    public static function arrayFlatten(array $arr): array
    {
        return array_reduce($arr, function ($a, $item) {
            if (is_array($item)) {
                $item = static::arrayFlatten($item);
            }

            return array_merge($a, (array) $item);
        }, []);
    }

    public static function getRemoteIp(): string
    {
        $headers = function_exists('apache_request_headers') ? apache_request_headers() : $_SERVER;

        /**
         * Get the forwarded IP if it exists
         */
        if (!empty($headers['X-Forwarded-For']) && filter_var($headers['X-Forwarded-For'], FILTER_VALIDATE_IP)) {
            return $headers['X-Forwarded-For'];
        } elseif (
            !empty($headers['HTTP_X_FORWARDED_FOR'])
            && filter_var($headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)
        ) {
            return $headers['HTTP_X_FORWARDED_FOR'];
        }

        if (!empty($_SERVER['REMOTE_ADDR'])) {
            return filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);
        }

        return '127.0.0.1';
    }

    public static function getRemoteUserAgent(): string
    {
        if (!empty($_SERVER['HTTP_USER_AGENT'])) {
            return $_SERVER['HTTP_USER_AGENT'];
        }
        return '';
    }

    public static function validateSignature(array $postData, string $secretKey): bool
    {
        if (!isset($postData['brq_signature'])) {
            return false;
        }

        $signature = self::calculateSignature($postData, $secretKey);

        if ($signature !== $postData['brq_signature']) {
            return false;
        }

        return true;
    }

    protected static function calculateSignature(array $postData, string $secretKey): string
    {
        $copyData = $postData;
        unset($copyData['brq_signature']);

        $sortableArray = self::buckarooArraySort($copyData);

        $signatureString = '';

        foreach ($sortableArray as $brq_key => $value) {
            $value = self::decodePushValue($brq_key, $value);

            $signatureString .= $brq_key . '=' . $value;
        }

        $signatureString .= $secretKey;

        $signature = SHA1($signatureString);

        return $signature;
    }

    protected static function buckarooArraySort(array $arrayToUse): array
    {
        $arrayToSort   = [];
        $originalArray = [];

        foreach ($arrayToUse as $key => $value) {
            $arrayToSort[strtolower($key)]   = $value;
            $originalArray[strtolower($key)] = $key;
        }

        ksort($arrayToSort);

        $sortableArray = [];

        foreach ($arrayToSort as $key => $value) {
            $key                 = $originalArray[$key];
            $sortableArray[$key] = $value;
        }

        return $sortableArray;
    }

    private static function decodePushValue(string $brq_key, string $brq_value): string
    {
        switch ($brq_key) {
            case 'brq_SERVICE_payconiq_PayconiqAndroidUrl':
            case 'brq_SERVICE_payconiq_PayconiqIosUrl':
            case 'brq_SERVICE_payconiq_PayconiqUrl':
            case 'brq_SERVICE_payconiq_QrUrl':
            case 'brq_SERVICE_masterpass_CustomerPhoneNumber':
            case 'brq_SERVICE_masterpass_ShippingRecipientPhoneNumber':
            case 'brq_InvoiceDate':
            case 'brq_DueDate':
            case 'brq_PreviousStepDateTime':
            case 'brq_EventDateTime':
            case 'brq_customer_name':
                $decodedValue = $brq_value;
                break;
            default:
                $decodedValue = urldecode($brq_value);
        }

        return $decodedValue;
    }
}
