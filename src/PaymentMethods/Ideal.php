<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\PaymentPayload;
use Buckaroo\Model\RefundPayload;
use Buckaroo\Model\ServiceList;

class Ideal extends PaymentMethod
{
    public const BANK_CODE_ABN = 'ABNANL2A';
    public const BANK_CODE_ASN = 'ASNBNL21';
    public const BANK_CODE_BUNQ = 'BUNQNL2A';
    public const BANK_CODE_ING = 'INGBNL2A';
    public const BANK_CODE_RABO = 'RABONL2U';
    public const BANK_CODE_REGIO = 'RBRBNL21';
    public const BANK_CODE_SNS = 'SNSBNL2A';
    public const BANK_CODE_TRIODOS = 'TRIONL2U';
    public const BANK_CODE_TEST = 'BANKNL2Y';
    public const SERVICE_VERSION = 2;

    public function setPayServiceList(array $serviceParameters = []): self
    {
        $paymentModel = new PaymentPayload($this->payload);

        $parameters = array([
            'name' => 'issuer',
            'Value' => $paymentModel->issuer
        ]);

        $serviceList = new ServiceList(
            self::IDEAL,
            self::SERVICE_VERSION,
            'Pay',
            $parameters
        );

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }

    public function setRefundServiceList(): self
    {
        $serviceList =  new ServiceList(
            self::IDEAL,
            self::SERVICE_VERSION,
            'Refund'
        );

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }
}
