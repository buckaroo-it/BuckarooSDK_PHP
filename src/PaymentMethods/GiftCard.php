<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\PaymentPayload;
use Buckaroo\Model\ServiceList;

class GiftCard extends PaymentMethod
{
    public const SERVICE_VERSION = 0;
    public const PAYMENT_NAME = 'giftcard';

    public function setPayServiceList(array $serviceParameters = []): self
    {
        if(!isset($serviceParameters['voucher'])) {
            throw new \Exception("Missing voucher payload in serviceParameters.");
        }

        $parameters = array([
            'Name' => 'IntersolveCardnumber',
            'Value' => $serviceParameters['voucher']['intersolveCardnumber'] ?? ''
        ],[
            'Name' => 'IntersolvePin',
            'Value' => $serviceParameters['voucher']['intersolvePin'] ?? ''
        ]);

        $serviceList = new ServiceList(
            $this->paymentName(),
            $this->serviceVersion(),
            'Pay',
            $parameters
        );

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }

    public function paymentName(): string
    {
        if(isset($this->payload['serviceParameters']['name']))
        {
            return $this->payload['serviceParameters']['name'];
        }

        throw new \Exception('Missing voucher name');
    }

    public function serviceVersion(): int
    {
        return self::SERVICE_VERSION;
    }
}