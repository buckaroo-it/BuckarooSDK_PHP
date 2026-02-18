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

namespace Buckaroo\PaymentMethods\Klarna\Models;

use Buckaroo\Models\ShippingInfo as ShippingInfoModel;
use Buckaroo\PaymentMethods\Klarna\Service\ParameterKeys\ShippingInfoAdapter;

class ShippingInfo extends \Buckaroo\Models\ShippingInfo
{
    private string $type;

    protected ?ShippingInfoAdapter $shippingInfo;

    public function __construct(string $type, ?array $values = null)
    {
        $this->type = $type;

        parent::__construct($values);
    }

    public function shippingInfo($shippingInfo = null)
    {
        if (is_array($shippingInfo)) {
            $this->shippingInfo = new ShippingInfoAdapter(new ShippingInfoModel($shippingInfo), $this->type);
        }

        return $this->shippingInfo;
    }
}
