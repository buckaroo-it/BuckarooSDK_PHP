<?php

namespace Buckaroo\Helpers;

class CultureHeader
{
    public function getHeader(string $locale = ''): string
    {
        return "Culture: " . self::getTranslatedLocale($locale);
    }

    public static function getTranslatedLocale(string $locale = ''): string
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
