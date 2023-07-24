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

namespace Buckaroo\PaymentMethods\Trustly;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\PayablePaymentMethod;
use Buckaroo\PaymentMethods\Trustly\Models\Pay;
use Buckaroo\PaymentMethods\Trustly\Service\ParameterKeys\PayAdapter;
use Buckaroo\Transaction\Response\TransactionResponse;

class Trustly extends PayablePaymentMethod
{
    protected string $paymentName = 'Trustly';

    public function pay(?Model $model = null): TransactionResponse
    {
        return parent::pay($model ?? new PayAdapter(new Pay($this->payload)));
    }
    
    /**
     * @param Model|null $model
     * @return TransactionResponse
     */
    public function payRemainder(?Model $model = null): TransactionResponse
    {
        return parent::payRemainder($model ?? new PayAdapter(new Pay($this->payload)));
    }
}
