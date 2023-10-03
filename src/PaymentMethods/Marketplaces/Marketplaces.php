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

namespace Buckaroo\PaymentMethods\Marketplaces;

use Buckaroo\Models\Payload\DataRequestPayload;
use Buckaroo\PaymentMethods\Interfaces\Combinable;
use Buckaroo\PaymentMethods\Marketplaces\Models\ServiceList;
use Buckaroo\PaymentMethods\PaymentMethod;

class Marketplaces extends PaymentMethod implements Combinable
{
    /**
     * @var string
     */
    protected string $paymentName = 'Marketplaces';

    /**
     * @return Marketplaces|mixed
     */
    public function split()
    {
        $serviceList = new ServiceList($this->payload);

        $this->setServiceList('Split', $serviceList);

        $this->request->setPayload(new DataRequestPayload($this->payload));

        return $this->dataRequest();
    }

    /**
     * @return Marketplaces|mixed
     */
    public function transfer()
    {
        $serviceList = new ServiceList($this->payload);

        $this->setServiceList('Transfer', $serviceList);

        $this->request->setPayload(new DataRequestPayload($this->payload));

        return $this->dataRequest();
    }

    /**
     * @return Marketplaces|mixed
     */
    public function refundSupplementary()
    {
        $serviceList = new ServiceList($this->payload);

        $this->setServiceList('RefundSupplementary', $serviceList);

        $this->request->setPayload(new DataRequestPayload($this->payload));

        return $this->dataRequest();
    }
}
