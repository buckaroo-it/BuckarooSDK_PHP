<?php

namespace Buckaroo\Helpers;

class CultureHeader
{
    /**
     * @return string
     */
    public function getHeader($locale = false)
    {
        return "Culture: " . self::getTranslatedLocale($locale);
    }

    public static function getTranslatedLocale($locale = false): string
    {
        switch ($locale) {
            case 'nl':
                $translatedLocale = 'nl-NL';
                break;
            case 'de':
                $translatedLocale = 'de-DE';
                break;
            default:
                $translatedLocale = 'en-GB';
                break;
        }
        return $translatedLocale;
    }
}
