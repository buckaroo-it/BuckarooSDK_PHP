<?php

namespace Buckaroo\Services\TransactionHeaders;

class CultureHeader extends TransactionHeader
{
    public function __construct(TransactionHeader $transactionHeader, string $locale = null) {
        $this->locale = $locale;

        parent::__construct($transactionHeader);
    }

    public function getHeaders(): array {
        $headers = $this->transactionHeader->getHeaders();

        $headers[] = "Culture: " . $this->getLocale();

        return $headers;
    }

    public function getLocale(): string
    {
        switch ($this->locale) {
            case 'nl':
                return 'nl-NL';
            case 'de':
                return 'de-DE';
        }
        return 'en-GB';
    }
}
