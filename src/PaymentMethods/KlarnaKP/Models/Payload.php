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

namespace Buckaroo\PaymentMethods\KlarnaKP\Models;

use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\KlarnaKP\Service\ParameterKeys\ArticleAdapter;
use Buckaroo\PaymentMethods\Traits\CountableGroupKey;

class Payload extends ServiceParameter
{
    use CountableGroupKey;

    private array $countableProperties = ['articles'];

    protected array $articles = [];

    protected Recipient $billingRecipient;
    protected Recipient $shippingRecipient;

    protected int $gender;
    protected string $operatingCountry;
    protected string $pno;
    protected string $reservationNumber;
    protected bool $shippingSameAsBilling = true;

    protected array $groupData = [
        'articles' => [
            'groupType' => 'Article',
        ],
    ];

    public function billing($billing = null)
    {
        if (is_array($billing))
        {
            $this->billingRecipient = new Recipient('Billing', $billing);
            $this->shippingRecipient = new Recipient('Shipping', $billing);
        }

        return $this->billingRecipient;
    }

    public function shipping($shipping = null)
    {
        if (is_array($shipping))
        {
            $this->shippingSameAsBilling = false;

            $this->shippingRecipient = new Recipient('Shipping', $shipping);
        }

        return $this->shippingRecipient;
    }

    public function articles(?array $articles = null)
    {
        if (is_array($articles))
        {
            foreach ($articles as $article)
            {
                $this->articles[] = new ArticleAdapter(new Article($article));
            }
        }

        return $this->articles;
    }
}
