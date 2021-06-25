<?php

declare(strict_types=1);

namespace Buckaroo\SDK\Helpers;

use Closure;
use Countable;
use InvalidArgumentException;

class Helpers
{
    /**
     * Determine if the given value is "blank".
     *
     * @param  mixed   $value
     * @return boolean
     */
    public static function blank($value)
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

    /**
     * Return a default value if value not set
     *
     * @param  mixed $value
     * @param  mixed $default
     * @return mixed
     */
    public static function def($value, $default = null)
    {
        if (static::blank($value)) {
            return $default;
        }

        return $value;
    }

    /**
     * Generate a random string
     *
     * @param  integer $length Length of the random string
     * @return string          Random string
     */
    public static function stringRandom($length = 16)
    {
        $chars = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789');
        $str   = "";

        for ($i = 0; $i < $length; $i++) {
            $key = array_rand($chars);
            $str .= $chars[$key];
        }

        return $str;
    }

    /**
     * Remove substring from the start of a string if found
     *
     * @param  string $haystack String to find substring in
     * @param  string $needle   Substring to find
     * @return string           String without the substring if found at the start
     */
    public static function stringRemoveStart($haystack, $needle)
    {
        if (static::stringStartsWith($haystack, $needle)) {
            return mb_substr($haystack, mb_strlen($needle));
        }

        return $haystack;
    }

    /**
     * Check if a string contains a substring
     *
     * @param  string $haystack String to find substring in
     * @param  string $needle   Substring to find
     * @return string
     */
    public static function stringContains($haystack, $needle)
    {
        return mb_stripos($haystack, $needle) !== false;
    }

    /**
     * Check if a string contains digits
     *
     * @param  string $haystack String to find digits in
     * @return boolean
     */
    public static function stringContainsDigits($haystack)
    {
        return !!preg_match('/\\d/', $haystack);
    }

    /**
     * Check if a string contains alpha characters
     *
     * @param  string $haystack String to find digits in
     * @return boolean
     */
    public static function stringContainsAlpha($haystack)
    {
        return !!preg_match('/[a-zA-Z]/', $haystack);
    }

    /**
     * Check if a string begins with a substring
     *
     * @param  string $haystack String to find substring in
     * @param  string $needle   Substring to find
     * @return string
     */
    public static function stringStartsWith($haystack, $needle)
    {
        return mb_substr($haystack, 0, mb_strlen($needle)) == $needle;
    }

    /**
     * Get all digits from a string
     *
     * @param  string $haystack
     * @return array [ int, int ]
     */
    public static function stringGetDigits($haystack)
    {
        preg_match_all('!\d+!', $haystack, $matches);
        return static::arrayFlatten($matches);
    }

    /**
     * Get all alpha-characters from a string
     *
     * @param  string $haystack
     * @return array [ string, string ]
     */
    public static function stringGetAlpha($haystack)
    {
        preg_match_all('![a-zA-Z]+!', $haystack, $matches);
        return static::arrayFlatten($matches);
    }

    /**
     * Converts underscore separated string into a camelCase separated string
     *
     * @param string $str
     * @return string
     */
    public static function stringUnderscoreToCamelCase($str)
    {
        $func = function ($c) {
            return strtoupper($c[1]);
        };

        return preg_replace_callback('/_([a-zA-Z])/', $func, $str);
    }

    /**
     * Convert a camelcase string to underscore
     *
     * @param  string $str
     * @return string
     */
    public static function stringCamelCaseToUnderscore($str)
    {
        return ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', $str)), '_');
    }

    /**
     * Format the phone number for Klarna
     *
     * @param  string $phone
     * @return string
     */
    public static function stringFormatPhone($phone)
    {

        // get all digits as string
        $digits = implode('', static::stringGetDigits($phone));

        // pad to at least 10 characters
        // $digits = str_pad($digits, 10, '0', STR_PAD_LEFT);

        return $digits;
    }

    /**
     * Return float from price string
     *
     * @param  string $price
     * @return float
     */
    public static function priceToFloat($price)
    {
        return floatval(str_replace(',', '.', $price));
    }

    /**
     * Return price string from float
     *
     * @param  float $number
     * @param  string $decimal (. or ,)
     * @return string
     */
    public static function floatToPrice($number, $decimal = ',')
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

    /**
     * Convert special character to normal variant
     *
     * @param  string $str
     * @return string
     */
    public static function stringTransliteration($str)
    {
        $oldLocale = setlocale(LC_CTYPE, 0);
        setlocale(LC_CTYPE, 'en_US.UTF8');

        $newStr = iconv('utf-8', 'ascii//TRANSLIT', $str);

        setlocale(LC_CTYPE, $oldLocale);

        return $newStr;
    }

    /**
     * Find first matching value in array
     *
     * @param  array  $array
     * @param  Closure $callback function($value, $key)
     * @return mixed
     */
    public static function arrayFind($array, Closure $callback)
    {
        foreach ($array as $key => $value) {
            if (call_user_func($callback, $value, $key)) {
                return $value;
            }
        }

        return null;
    }

    /**
     * Map over an array
     * (But with logical argument order and expose key in callback)
     *
     * @param  array  $array
     * @param  Closure $callback function($value, $key)
     * @return mixed
     */
    public static function arrayMap($array, Closure $callback)
    {
        $newAttributes = [];

        foreach ($array as $key => $value) {
            $newAttributes[$key] = call_user_func($callback, $value, $key);
        }

        return $newAttributes;
    }

    /**
     * Flatten an array to create a one-dimensional array
     *
     * @param  array $arr multi-dimenional array
     * @return array      flat array
     */
    public static function arrayFlatten(array $arr)
    {
        return array_reduce($arr, function ($a, $item) {
            if (is_array($item)) {
                $item = static::arrayFlatten($item);
            }

            return array_merge($a, (array) $item);
        }, []);
    }

    /**
     * Find differences between two arrays recursively
     *
     * @param  array  $array1
     * @param  array  $array2
     * @param  bool   $traverseObjects  Boolean to indicate whether to also diff objects
     * @return array
     */
    public static function arrayDiffDeep(array $array1, array $array2, $traverseObjects = false)
    {
        $diff    = [];
        $objects = [];

        foreach ($array1 as $key => $value) {
            if (array_key_exists($key, $array2)) {
                $className = '';
                $isObject  = is_object($value);

                if ($traverseObjects && $isObject && !isset($objects[spl_object_hash($value)])) {
                    // prevent infinite loops, by checking if object has already been traversed
                    $objects[spl_object_hash($value)] = $value;

                    $className = get_class($value);
                    $value     = (array) $value;
                }

                if (is_array($value)) {
                    $deepDiff = static::arrayDiffDeep($value, $array2[$key]);

                    if (count($deepDiff)) {
                        $diff[$key] = $deepDiff;
                    }
                } else {
                    if ($value != $array2[$key]) {
                        $diff[$key] = $value;
                    }
                }

                if ($isObject && array_key_exists($key, $diff)) {
                    $diff[$key]['___object_classname___'] = $className;
                }
            } else {
                $diff[$key] = $value;
            }
        }

        return $diff;
    }

    /**
     * Get IP of the connected client
     *
     * @return string ClientIP
     */
    public static function getRemoteIp()
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
        return false;
    }

    /**
     * Get user agent
     *
     * @return string
     */
    public static function getRemoteUserAgent()
    {
        if (!empty($_SERVER['HTTP_USER_AGENT'])) {
            return $_SERVER['HTTP_USER_AGENT'];
        }
        return '';
    }

    public static function validateSignature($postData, $secretKey)
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

    /**
     * Determines the signature using array sorting and the SHA1 hash algorithm
     *
     * @param $postData
     *
     * @return string
     */
    protected static function calculateSignature($postData, $secretKey)
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

    /**
     * Sort the array so that the signature can be calculated identical to the way buckaroo does.
     *
     * @param $arrayToUse
     *
     * @return array $sortableArray
     */
    protected static function buckarooArraySort($arrayToUse)
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

    /**
     * @param string $brq_key
     * @param string $brq_value
     *
     * @return string
     */
    private static function decodePushValue($brq_key, $brq_value)
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
