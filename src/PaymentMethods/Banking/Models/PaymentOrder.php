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

namespace Buckaroo\PaymentMethods\Banking\Models;

use Buckaroo\Models\ServiceParameter;

class PaymentOrder extends ServiceParameter
{
    /**
     * @var string
     */
    protected string $accountHolderName;

    /**
     * @var string
     */
    protected string $iban;

    /**
     * @var string|null
     */
    protected ?string $processingDate;

    /**
     * @var string|null
     */
    protected ?string $bic;

    /**
     * @var string|null
     */
    protected ?string $purpose;

    /**
     * @var string|null
     */
    protected ?string $structuredIssuerType;

    /**
     * @var string|null
     */
    protected ?string $structuredReference;
}
