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

declare(strict_types=1);

namespace Buckaroo\PaymentMethods\SEPA;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\Interfaces\Combinable;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\PaymentMethods\SEPA\Models\ExtraInfo;
use Buckaroo\PaymentMethods\SEPA\Models\Pay;
use Buckaroo\PaymentMethods\SEPA\Service\ParameterKeys\PayAdapter;
use Buckaroo\Transaction\Response\TransactionResponse;

class SEPA extends PayablePaymentMethod implements Combinable
{
    /**
     * @var string
     */
    protected string $paymentName = 'SepaDirectDebit';
    /**
     * @var int
     */
    protected int $serviceVersion = 1;

    /**
     * @param Model|null $model
     * @return PayablePaymentMethod|mixed
     */
    public function pay(?Model $model = null)
    {
        return parent::pay($model ?? new PayAdapter(new Pay($this->payload)));
    }

    /**
     * @return TransactionResponse
     */
    public function authorize(): TransactionResponse
    {
        $pay = new PayAdapter(new Pay($this->payload));

        $this->setPayPayload();

        $this->setServiceList('Authorize', $pay);

        return $this->postRequest();
    }

    /**
     * @return SEPA|mixed
     */
    public function payRecurrent()
    {
        $pay = new PayAdapter(new Pay($this->payload));

        $this->setPayPayload();

        $this->setServiceList('PayRecurrent', $pay);

        return $this->postRequest();
    }

    /**
     * @return SEPA|mixed
     */
    public function extraInfo()
    {
        $extraInfo = new PayAdapter(new ExtraInfo($this->payload));

        $this->setPayPayload();

        $this->setServiceList('Pay,ExtraInfo', $extraInfo);

        return $this->postRequest();
    }

    /**
     * @return SEPA|mixed
     */
    public function payWithEmandate()
    {
        $pay = new Pay($this->payload);

        $this->setPayPayload();

        $this->setServiceList('PayWithEmandate', $pay);

        return $this->postRequest();
    }
}
