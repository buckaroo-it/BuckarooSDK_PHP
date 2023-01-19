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

namespace Buckaroo\PaymentMethods\Subscriptions\Models;

use Buckaroo\Models\ServiceParameter;

class RatePlan extends ServiceParameter
{
    /**
     * @var string
     */
    private string $type;

    /**
     * @var string
     */
    protected string $ratePlanGuid;
    /**
     * @var string
     */
    protected string $ratePlanCode;
    /**
     * @var string
     */
    protected string $startDate;
    /**
     * @var string
     */
    protected string $endDate;

    /**
     * @var Charge
     */
    protected Charge $charge;

    /**
     * @param string $type
     * @param array|null $values
     */
    public function __construct(string $type, ?array $values = null)
    {
        $this->type = $type;

        parent::__construct($values);
    }

    /**
     * @param $charge
     * @return Charge
     */
    public function charge($charge = null)
    {
        if (is_array($charge))
        {
            $this->charge = new Charge($charge);
        }

        return $this->charge;
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getGroupType(string $key): ?string
    {
        if ($key == 'charge')
        {
            return $this->type . 'RatePlanCharge';
        }

        return parent::getGroupKey($key);
    }
}
