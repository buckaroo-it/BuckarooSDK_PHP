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

namespace Buckaroo\PaymentMethods\Marketplaces\Models;

use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\Traits\CountableGroupKey;

class ServiceList extends ServiceParameter
{
    use CountableGroupKey;

    /**
     * @var array|string[]
     */
    private array $countableProperties = ['sellers'];

    /**
     * @var string
     */
    protected string $daysUntilTransfer;

    /**
     * @var Marketplace
     */
    protected Marketplace $marketplace;
    /**
     * @var array
     */
    protected array $sellers = [];

    /**
     * @var array|\string[][]
     */
    protected array $groupData = [
        'marketplace' => [
            'groupType' => 'Marketplace',
        ],
        'sellers' => [
            'groupType' => 'Seller',
        ],
    ];

    /**
     * @param $marketplace
     * @return Marketplace
     */
    public function marketplace($marketplace = null)
    {
        if (is_array($marketplace))
        {
            $this->marketplace = new Marketplace($marketplace);
        }

        return $this->marketplace;
    }

    /**
     * @param $sellers
     * @return array
     */
    public function sellers($sellers = null)
    {
        if (is_array($sellers))
        {
            foreach ($sellers as $seller)
            {
                $this->sellers[] = new Seller($seller);
            }
        }

        return $this->sellers;
    }
}
