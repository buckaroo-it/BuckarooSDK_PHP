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

namespace Buckaroo\PaymentMethods\AfterpayDigiAccept\Models;

use Buckaroo\Models\Article;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\AfterpayDigiAccept\Service\ParameterKeys\ArticleAdapter;
use Buckaroo\PaymentMethods\Traits\CountableGroupKey;

/**
 *
 */
class Refund extends ServiceParameter
{
    use CountableGroupKey;

    /**
     * @var float
     */
    protected float $shippingCosts;

    /**
     * @var array|string[]
     */
    private array $countableProperties = ['articles'];
    /**
     * @var array
     */
    protected array $articles = [];

    /**
     * @param array|null $articles
     * @return array
     */
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
