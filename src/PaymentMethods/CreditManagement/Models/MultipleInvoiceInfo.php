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

namespace Buckaroo\PaymentMethods\CreditManagement\Models;

use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\Traits\CountableGroupKey;

class MultipleInvoiceInfo extends ServiceParameter
{
    use CountableGroupKey;

    /**
     * @var array|string[]
     */
    private array $countableProperties = ['invoices'];

    /**
     * @var array
     */
    protected array $invoices = [];

    /**
     * @param array|null $invoices
     * @return array
     */
    public function invoices(?array $invoices = null)
    {
        if (is_array($invoices))
        {
            foreach ($invoices as $invoice)
            {
                $this->invoices[] = new Invoice($invoice);
            }
        }

        return $this->invoices;
    }
}
