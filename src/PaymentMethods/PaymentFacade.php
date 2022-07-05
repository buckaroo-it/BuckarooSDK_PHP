<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Exceptions\SDKException;
use Buckaroo\PaymentMethods\Interfaces\Combinable;
use Buckaroo\Services\PayloadService;

class PaymentFacade
{
    private PaymentMethod $paymentMethod;

    private bool $isManually = false;

    public function __construct($client, $method)
    {
        $this->client = $client;

        $this->paymentMethod = PaymentMethodFactory::get($client, $method);
    }

    public function manually()
    {
        $this->isManually = true;

        return $this;
    }

    public function combine(Combinable $combinablePayment)
    {
        $this->paymentMethod->combinePayment($combinablePayment);

        return $this;
    }

    public function paymentMethod(): PaymentMethod
    {
        return $this->paymentMethod;
    }

    public function __call(string $name , array $arguments)
    {
        if(method_exists($this->paymentMethod, $name))
        {
            $this->paymentMethod->setPayload((new PayloadService($arguments[0] ?? []))->toArray());

            return $this->paymentMethod->manually($this->isManually)->$name();
        }

        throw new SDKException($this->client->getLogger(), "Payment method " . $name . " on payment " . $this->paymentMethod->paymentName() . " you requested does not exist.");
    }
}
