<?php

declare(strict_types=1);

namespace Buckaroo\Helpers\Constants;

class VatCategory
{
    public const HIGH_RATE   = 1;
    public const LOW_RATE    = 2;
    public const ZERO_RATE   = 3;
    public const NULL_RATE   = 4;
    public const MIDDLE_RATE = 5;

    /**
     * Really simple and crude way to get the VatCategory
     * Should include all percentages of all countries to do an accurate guess
     * https://en.wikipedia.org/wiki/Tax_rates_in_Europe
     *
     * @param  float|null $percentage
     * @param  string     $countryIso
     * @return int
     */
    public static function getByPercentage($percentage = null, $countryIso = null)
    {
        if (!is_numeric($percentage)) {
            return static::NULL_RATE;
        }

        $percentage = floatval($percentage);

        /**
        NL
        1:High          - 21%
        2:Low           - 6%
        3:Zero          - 0%
        4:None          - geen
        5:Middle        - bestaat niet in NL
         */
        if ($countryIso == 'NL') {
            if ($percentage <= 0) {
                return static::ZERO_RATE;
            }

            if ($percentage < 10) {
                return static::LOW_RATE;
            }

            return static::HIGH_RATE;
        }

        /**
        BE
        1:High          - 21%
        2:Low           - 6%
        3:Zero          - 0%
        4:None         - geen
        5:Middle        - 12%
         */
        if ($countryIso == 'BE') {
            if ($percentage <= 0) {
                return static::ZERO_RATE;
            }

            if ($percentage < 10) {
                return static::LOW_RATE;
            }

            if ($percentage < 17) {
                return static::MIDDLE_RATE;
            }

            return static::HIGH_RATE;
        }

        if ($percentage <= 0) {
            return static::ZERO_RATE;
        }

        if ($percentage < 10) {
            return static::LOW_RATE;
        }

        if ($percentage < 17) {
            return static::MIDDLE_RATE;
        }

        return static::HIGH_RATE;
    }
}
