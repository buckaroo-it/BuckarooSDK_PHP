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

class Debtor extends Invoice
{
    /**
     * @var bool
     */
    protected bool $addressUnreachable;
    /**
     * @var bool
     */
    protected bool $emailUnreachable;
    /**
     * @var bool
     */
    protected bool $mobileUnreachable;
    /**
     * @var bool
     */
    protected bool $landlineUnreachable;
    /**
     * @var bool
     */
    protected bool $faxUnreachable;

    /**
     * @var array|\string[][]
     */
    protected array $groupData = [
        'address' => [
            'groupType' => 'Address',
        ],
        'company' => [
            'groupType' => 'Company',
        ],
        'person' => [
            'groupType' => 'Person',
        ],
        'debtor' => [
            'groupType' => 'Debtor',
        ],
        'email' => [
            'groupType' => 'Email',
        ],
        'phone' => [
            'groupType' => 'Phone',
        ],
        'addressUnreachable' => [
            'groupType' => 'Address',
        ],
        'emailUnreachable' => [
            'groupType' => 'Email',
        ],
        'mobileUnreachable' => [
            'groupType' => 'Phone',
        ],
        'landlineUnreachable' => [
            'groupType' => 'Phone',
        ],
        'faxUnreachable' => [
            'groupType' => 'Phone',
        ],
    ];
}
