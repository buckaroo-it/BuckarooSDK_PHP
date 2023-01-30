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

namespace Buckaroo\PaymentMethods;

use Buckaroo\Exceptions\BuckarooException;
use Buckaroo\PaymentMethods\Afterpay\Afterpay;
use Buckaroo\PaymentMethods\AfterpayDigiAccept\AfterpayDigiAccept;
use Buckaroo\PaymentMethods\Alipay\Alipay;
use Buckaroo\PaymentMethods\ApplePay\ApplePay;
use Buckaroo\PaymentMethods\Bancontact\Bancontact;
use Buckaroo\PaymentMethods\BankTransfer\BankTransfer;
use Buckaroo\PaymentMethods\Belfius\Belfius;
use Buckaroo\PaymentMethods\Billink\Billink;
use Buckaroo\PaymentMethods\BuckarooVoucher\BuckarooVoucher;
use Buckaroo\PaymentMethods\BuckarooWallet\BuckarooWallet;
use Buckaroo\PaymentMethods\CreditCard\CreditCard;
use Buckaroo\PaymentMethods\CreditClick\CreditClick;
use Buckaroo\PaymentMethods\CreditManagement\CreditManagement;
use Buckaroo\PaymentMethods\Emandates\Emandates;
use Buckaroo\PaymentMethods\EPS\EPS;
use Buckaroo\PaymentMethods\GiftCard\GiftCard;
use Buckaroo\PaymentMethods\Giropay\Giropay;
use Buckaroo\PaymentMethods\iDeal\iDeal;
use Buckaroo\PaymentMethods\iDealQR\iDealQR;
use Buckaroo\PaymentMethods\iDin\iDin;
use Buckaroo\PaymentMethods\In3\In3;
use Buckaroo\PaymentMethods\KBC\KBC;
use Buckaroo\PaymentMethods\KlarnaKP\KlarnaKP;
use Buckaroo\PaymentMethods\KlarnaPay\KlarnaPay;
use Buckaroo\PaymentMethods\Marketplaces\Marketplaces;
use Buckaroo\PaymentMethods\Payconiq\Payconiq;
use Buckaroo\PaymentMethods\Paypal\Paypal;
use Buckaroo\PaymentMethods\PayPerEmail\PayPerEmail;
use Buckaroo\PaymentMethods\PointOfSale\PointOfSale;
use Buckaroo\PaymentMethods\Przelewy24\Przelewy24;
use Buckaroo\PaymentMethods\RequestToPay\RequestToPay;
use Buckaroo\PaymentMethods\SEPA\SEPA;
use Buckaroo\PaymentMethods\Sofort\Sofort;
use Buckaroo\PaymentMethods\Subscriptions\Subscriptions;
use Buckaroo\PaymentMethods\Surepay\Surepay;
use Buckaroo\PaymentMethods\Tinka\Tinka;
use Buckaroo\PaymentMethods\Trustly\Trustly;
use Buckaroo\PaymentMethods\WeChatPay\WeChatPay;
use Buckaroo\Transaction\Client;

class PaymentMethodFactory
{
    /**
     * @var array|\string[][]
     */
    private static array $payments = [
        ApplePay::class => ['applepay'],
        Alipay::class => ['alipay'],
        Afterpay::class => ['afterpay'],
        AfterpayDigiAccept::class => ['afterpaydigiaccept'],
        Bancontact::class => ['bancontactmrcash'],
        Billink::class => ['billink'],
        Belfius::class => ['belfius'],
        BuckarooWallet::class => ['buckaroo_wallet'],
        CreditCard::class =>
            [
                'creditcard', 'mastercard', 'visa',
                'amex', 'vpay', 'maestro',
                'visaelectron', 'cartebleuevisa',
                'cartebancaire', 'dankort', 'nexi',
                'postepay',
            ],
        CreditClick::class => ['creditclick'],
        CreditManagement::class => ['credit_management'],
        iDeal::class => ['ideal', 'idealprocessing'],
        iDealQR::class => ['ideal_qr'],
        iDin::class => ['idin'],
        In3::class => ['in3'],
        KlarnaPay::class => ['klarna', 'klarnain'],
        KlarnaKP::class => ['klarnakp'],
        Surepay::class => ['surepay'],
        Subscriptions::class => ['subscriptions'],
        SEPA::class => ['sepadirectdebit', 'sepa'],
        KBC::class => ['kbcpaymentbutton'],
        Paypal::class => ['paypal'],
        PayPerEmail::class => ['payperemail'],
        EPS::class => ['eps'],
        Emandates::class => ['emandates'],
        Sofort::class => ['sofort', 'sofortueberweisung'],
        Tinka::class => ['tinka'],
        Marketplaces::class => ['marketplaces'],
        Payconiq::class => ['payconiq'],
        Przelewy24::class => ['przelewy24'],
        PointOfSale::class => ['pospayment'],
        Giropay::class => ['giropay'],
        GiftCard::class => [
            'giftcard', 'westlandbon', 'ideal',
            'ippies', 'babygiftcard', 'babyparkgiftcard',
            'beautywellness', 'boekenbon', 'boekenvoordeel',
            'designshopsgiftcard', 'fashioncheque', 'fashionucadeaukaart',
            'fijncadeau', 'koffiecadeau', 'kokenzo',
            'kookcadeau', 'nationaleentertainmentcard', 'naturesgift',
            'podiumcadeaukaart', 'shoesaccessories', 'webshopgiftcard',
            'wijncadeau', 'wonenzo', 'yourgift',
            'vvvgiftcard', 'parfumcadeaukaart',
        ],
        Trustly::class => ['trustly'],
        BankTransfer::class => ['transfer'],
        RequestToPay::class => ['requesttopay'],
        WeChatPay::class => ['wechatpay'],
        BuckarooVoucher::class => ['buckaroovoucher'],
    ];

    /**
     * @var Client
     */
    private Client $client;
    /**
     * @var string
     */
    private string $paymentMethod;

    /**
     * @param Client $client
     * @param string $paymentMethod
     */
    public function __construct(Client $client, string $paymentMethod)
    {
        $this->client = $client;
        $this->paymentMethod = strtolower($paymentMethod);
    }

    /**
     * @return PaymentMethod
     */
    public function getPaymentMethod(): PaymentMethod
    {
        foreach (self::$payments as $class => $alias)
        {
            if (in_array($this->paymentMethod, $alias))
            {
                return new $class($this->client, $this->paymentMethod);
            }
        }

        throw new BuckarooException($this->client->config()->getLogger(), "Wrong payment method code has been given");
    }

    /**
     * @param Client $client
     * @param string $paymentMethod
     * @return PaymentMethod
     */
    public static function get(Client $client, string $paymentMethod): PaymentMethod
    {
        $factory = new self($client, $paymentMethod);

        return $factory->getPaymentMethod();
    }
}
