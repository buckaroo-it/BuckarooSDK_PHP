<?php

declare(strict_types=1);

namespace Buckaroo\SDK\Helpers;

use Buckaroo\SDK\PaymentMethods\Ideal;
use Buckaroo\SDK\PaymentMethods\IdealProcessing;
use Buckaroo\SDK\PaymentMethods\Bancontact;
use Buckaroo\SDK\PaymentMethods\Creditcard;
use Buckaroo\SDK\PaymentMethods\Creditcards;
use Buckaroo\SDK\PaymentMethods\AfterPay;
use Buckaroo\SDK\PaymentMethods\Sofort;
use Buckaroo\SDK\PaymentMethods\Paypal;
use Buckaroo\SDK\PaymentMethods\Transfer;
use Buckaroo\SDK\PaymentMethods\ApplePay;
use Buckaroo\SDK\PaymentMethods\Giropay;
use Buckaroo\SDK\PaymentMethods\Kbc;
use Buckaroo\SDK\PaymentMethods\SepaDirectDebit;
use Buckaroo\SDK\PaymentMethods\Payconiq;
use Buckaroo\SDK\PaymentMethods\Giftcards;
use Buckaroo\SDK\PaymentMethods\Rtp;
use Buckaroo\SDK\PaymentMethods\In3;
use Buckaroo\SDK\PaymentMethods\Eps;
use Buckaroo\SDK\PaymentMethods\P24;
use Buckaroo\SDK\PaymentMethods\Alipay;
use Buckaroo\SDK\PaymentMethods\WeChatPay;
use Buckaroo\SDK\PaymentMethods\Trustly;
use Buckaroo\SDK\PaymentMethods\Klarna;
use Buckaroo\SDK\PaymentMethods\KlarnaKp;
use Buckaroo\SDK\PaymentMethods\Klarnain;
use Buckaroo\SDK\PaymentMethods\Billink;
use Buckaroo\SDK\PaymentMethods\Belfius;

class GatewayHelper
{
    public const GATEWAYS = [
        Ideal::class,
        IdealProcessing::class,
        Bancontact::class,
        Creditcard::class,
        Creditcards::class,
        AfterPay::class,
        Sofort::class,
        Paypal::class,
        Transfer::class,
        ApplePay::class,
        Giropay::class,
        Kbc::class,
        SepaDirectDebit::class,
        Payconiq::class,
        Giftcards::class,
        Rtp::class,
        In3::class,
        Eps::class,
        P24::class,
        Alipay::class,
        WeChatPay::class,
        Trustly::class,
        Klarna::class,
        KlarnaKp::class,
        Klarnain::class,
        Billink::class,
        Belfius::class
    ];
}
