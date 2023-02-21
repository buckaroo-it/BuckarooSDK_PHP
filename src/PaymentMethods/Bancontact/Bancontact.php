<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods\Bancontact;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\Bancontact\Models\Authenticate;
use Buckaroo\PaymentMethods\Bancontact\Models\Pay;
use Buckaroo\PaymentMethods\Bancontact\Models\PayEncrypted;
use Buckaroo\PaymentMethods\Interfaces\Combinable;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\Transaction\Response\TransactionResponse;

class Bancontact extends PayablePaymentMethod implements Combinable
{
    /**
     * @var string
     */
    protected string $paymentName = 'bancontactmrcash';
    /**
     * @var int
     */
    protected int $serviceVersion = 0;

    /**
     * @param Model|null $model
     * @return TransactionResponse
     */
    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay($model ?? new Pay($this->payload));
    }

    /**
     * @return TransactionResponse
     */
    public function payEncrypted(): TransactionResponse
    {
        $payEncrypted = new PayEncrypted($this->payload);

        $this->setPayPayload();

        $this->setServiceList('PayEncrypted', $payEncrypted);

        return $this->postRequest();
    }

    /**
     * @return TransactionResponse
     */
    public function payRecurring(): TransactionResponse
    {
        $this->setPayPayload();

        $this->setServiceList('PayRecurring');

        return $this->postRequest();
    }

    /**
     * @return TransactionResponse
     */
    public function payOneClick(): TransactionResponse
    {
        $this->setPayPayload();

        $this->setServiceList('PayOneClick');

        return $this->postRequest();
    }

    /**
     * @return TransactionResponse
     */
    public function authenticate(): TransactionResponse
    {
        $authenticate = new Authenticate($this->payload);

        $this->setPayPayload();

        $this->setServiceList('Authenticate', $authenticate);

        return $this->postRequest();
    }
}
