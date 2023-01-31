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

namespace Buckaroo\PaymentMethods\SEPA\Models;

use Buckaroo\PaymentMethods\Paypal\Models\Address;
use Buckaroo\PaymentMethods\SEPA\Service\ParameterKeys\AddressAdapter;

class ExtraInfo extends Pay
{
    /**
     * @var AddressAdapter
     */
    protected AddressAdapter $address;

    /**
     * @var string
     */
    protected string $customerReferencePartyName;
    /**
     * @var string
     */
    protected string $customerReferencePartyCode;
    /**
     * @var string
     */
    protected string $customercode;
    /**
     * @var string
     */
    protected string $contractID;

    /**
     * @param $address
     * @return AddressAdapter
     */
    public function address($address = null)
    {
        if (is_array($address))
        {
            $this->address = new AddressAdapter(new Address($address));
        }

        return $this->address;
    }
}
