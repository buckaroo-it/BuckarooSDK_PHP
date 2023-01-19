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

use Buckaroo\Models\Debtor;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\CreditManagement\Service\ParameterKeys\DebtorInfoAdapter;

class DebtorInfo extends ServiceParameter
{
    /**
     * @var DebtorInfoAdapter
     */
    protected DebtorInfoAdapter $debtor;

    /**
     * @var array|\string[][]
     */
    protected array $groupData = [
        'debtor' => [
            'groupType' => 'Debtor',
        ],
    ];

    /**
     * @param $debtor
     * @return DebtorInfoAdapter
     */
    public function debtor($debtor = null)
    {
        if (is_array($debtor))
        {
            $this->debtor = new DebtorInfoAdapter(new Debtor($debtor));
        }

        return $this->debtor;
    }
}
