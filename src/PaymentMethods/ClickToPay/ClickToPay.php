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

namespace Buckaroo\PaymentMethods\ClickToPay;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\ClickToPay\Models\ClickToPayData;
use Buckaroo\PaymentMethods\Interfaces\Combinable;
use Buckaroo\PaymentMethods\PayablePaymentMethod;

class ClickToPay extends PayablePaymentMethod implements Combinable
{
    protected string $paymentName = 'ClickToPay';

    /**
     * Send the ClickToPay-specific service parameters (Identifier, TransientToken)
     * with the Pay action. The generic PayablePaymentMethod::pay() sends the
     * service with no parameters, which the API rejects.
     *
     * @param Model|null $model
     * @return ClickToPay|mixed
     */
    public function pay(?Model $model = null)
    {
        $clickToPayData = new ClickToPayData([
            'identifier' => $this->payload['identifier'] ?? null,
            'transientToken' => $this->payload['transientToken'] ?? null,
        ]);

        $this->setPayPayload();

        $this->setServiceList('Pay', $clickToPayData);

        return $this->postRequest();
    }
}
