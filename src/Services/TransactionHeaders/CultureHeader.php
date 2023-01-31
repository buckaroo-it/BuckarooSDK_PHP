<?php
/*
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * It is available through the world-wide-web at this URL:
 * https://tldrlegal.com/license/mit-license
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to support@buckaroo.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact support@buckaroo.nl for more information.
 *
 * @copyright Copyright (c) Buckaroo B.V.
 * @license   https://tldrlegal.com/license/mit-license
 */

namespace Buckaroo\Services\TransactionHeaders;

class CultureHeader extends TransactionHeader
{
    /**
     * @param TransactionHeader $transactionHeader
     * @param string|null $locale
     */
    public function __construct(TransactionHeader $transactionHeader, string $locale = null)
    {
        $this->locale = $locale;

        parent::__construct($transactionHeader);
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        $headers = $this->transactionHeader->getHeaders();

        $headers[] = "Culture: " . $this->getLocale();

        return $headers;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        switch ($this->locale)
        {
            case 'nl':
                return 'nl-NL';
            case 'de':
                return 'de-DE';
        }

        return 'en-GB';
    }
}
