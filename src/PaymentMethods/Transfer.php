<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods;

use Buckaroo\Model\PaymentPayload;
use Buckaroo\Model\ServiceList;

class Transfer extends PaymentMethod
{
    public const SERVICE_VERSION = 1;

    public function setPayServiceList(array $serviceParameters = []): self
    {
        $paymentModel = new PaymentPayload($this->payload);
    
        $parameters = [
            ['name' => 'customergender', 'Value' => $paymentModel->customerGender],
            ['name' => 'customerFirstName', 'Value' => $paymentModel->customerFirstName],
            ['name' => 'customerLastName', 'Value' => $paymentModel->customerLastName],
            ['name' => 'customeremail', 'Value' => $paymentModel->customerEmail],
            ['name' => 'customercountry', 'Value' => $paymentModel->customerCountry],
            ['name' => 'DateDue', 'Value' => $paymentModel->dueDate],
            ['name' => 'SendMail', 'Value' => $paymentModel->sendMail]
        ];

        $serviceList = new ServiceList(
            self::TRANSFER,
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
            self::TRANSFER,
            self::SERVICE_VERSION,
            'Refund'
        );

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }
}
