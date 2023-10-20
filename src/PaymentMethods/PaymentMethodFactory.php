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
use Buckaroo\PaymentMethods\MBWay\MBWay;
use Buckaroo\PaymentMethods\GiftCard\GiftCard;
use Buckaroo\PaymentMethods\Giropay\Giropay;
use Buckaroo\PaymentMethods\iDeal\iDeal;
use Buckaroo\PaymentMethods\iDealQR\iDealQR;
use Buckaroo\PaymentMethods\iDin\iDin;
use Buckaroo\PaymentMethods\In3\In3;
use Buckaroo\PaymentMethods\In3Old\In3Old;
use Buckaroo\PaymentMethods\KBC\KBC;
use Buckaroo\PaymentMethods\KlarnaKP\KlarnaKP;
use Buckaroo\PaymentMethods\KlarnaPay\KlarnaPay;
use Buckaroo\PaymentMethods\Marketplaces\Marketplaces;
use Buckaroo\PaymentMethods\Multibanco\Multibanco;
use Buckaroo\PaymentMethods\NoServiceSpecifiedPayment\NoServiceSpecifiedPayment;
use Buckaroo\PaymentMethods\Payconiq\Payconiq;
use Buckaroo\PaymentMethods\PaymentInitiation\PaymentInitiation;
use Buckaroo\PaymentMethods\Paypal\Paypal;
use Buckaroo\PaymentMethods\PayPerEmail\PayPerEmail;
use Buckaroo\PaymentMethods\PointOfSale\PointOfSale;
use Buckaroo\PaymentMethods\Przelewy24\Przelewy24;
use Buckaroo\PaymentMethods\SEPA\SEPA;
use Buckaroo\PaymentMethods\Sofort\Sofort;
use Buckaroo\PaymentMethods\Subscriptions\Subscriptions;
use Buckaroo\PaymentMethods\Surepay\Surepay;
use Buckaroo\PaymentMethods\Thunes\Thunes;
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
        Bancontact::class => ['bancontact', 'bancontactmrcash'],
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
        In3Old::class => ['in3old'],
        KlarnaPay::class => ['klarna', 'klarnain'],
        KlarnaKP::class => ['klarnakp'],
        Multibanco::class => ['multibanco'],
        MBWay::class => ['mbway'],
        Surepay::class => ['surepay'],
        Subscriptions::class => ['subscriptions'],
        SEPA::class => ['sepadirectdebit', 'sepa'],
        KBC::class => ['kbc', 'kbcpaymentbutton'],
        Paypal::class => ['paypal'],
        PayPerEmail::class => ['payperemail'],
        PaymentInitiation::class => ['paymentinitiation','paybybank'],
        EPS::class => ['eps'],
        Emandates::class => ['emandates'],
        Sofort::class => ['sofort', 'sofortueberweisung'],
        Tinka::class => ['tinka'],
        Marketplaces::class => ['marketplaces'],
        NoServiceSpecifiedPayment::class => ['noservice'],
        Payconiq::class => ['payconiq'],
        Przelewy24::class => ['przelewy24'],
        PointOfSale::class => ['pospayment'],
        Giropay::class => ['giropay'],
        NoServiceSpecifiedPayment::class => ['noservice'],
        GiftCard::class => [
            'giftcard', 'westlandbon', 'babygiftcard', 'babyparkgiftcard',
            'beautywellness', 'boekenbon', 'boekenvoordeel',
            'designshopsgiftcard', 'fashioncheque', 'fashionucadeaukaart',
            'fijncadeau', 'koffiecadeau', 'kokenzo',
            'kookcadeau', 'nationaleentertainmentcard', 'naturesgift',
            'podiumcadeaukaart', 'shoesaccessories', 'webshopgiftcard',
            'wijncadeau', 'wonenzo', 'yourgift',
            'vvvgiftcard', 'parfumcadeaukaart',
        ],
        Thunes::class => [
            'thunes', 'monizzemealvoucher', 'monizzeecovoucher', 'monizzegiftvoucher',
            'sodexomealvoucher', 'sodexoecovoucher', 'sodexogiftvoucher',
        ],
        Trustly::class => ['trustly'],
        BankTransfer::class => ['transfer'],
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
    private ?string $paymentMethod;

    /**
     * @param Client $client
     * @param string|null $paymentMethod
     */
    public function __construct(Client $client, ?string $paymentMethod)
    {
        $this->client = $client;
        $this->paymentMethod = ($paymentMethod)? strtolower($paymentMethod) : null;
    }

    /**
     * @return PaymentMethod
     * @throws BuckarooException
     */
    public function getPaymentMethod(): PaymentMethod
    {
        if ($this->paymentMethod)
        {
            foreach (self::$payments as $class => $alias)
            {
                if (in_array($this->paymentMethod, $alias))
                {
                    return new $class($this->client, $this->paymentMethod);
                }
            }

            throw new BuckarooException(
                $this->client->config()->getLogger(),
                "Wrong payment method code has been given"
            );
        }

        return new NoServiceSpecifiedPayment($this->client, $this->paymentMethod);
    }

    /**
     * @param Client $client
     * @param string|null $paymentMethod
     * @return PaymentMethod
     * @throws BuckarooException
     */
    public static function get(Client $client, ?string $paymentMethod): PaymentMethod
    {
        $factory = new self($client, $paymentMethod);

        return $factory->getPaymentMethod();
    }
}
