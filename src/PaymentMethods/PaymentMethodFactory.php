<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Exceptions\SDKException;
use Buckaroo\PaymentMethods\Afterpay\Afterpay;
use Buckaroo\PaymentMethods\AfterpayDigiAccept\AfterpayDigiAccept;
use Buckaroo\PaymentMethods\Alipay\Alipay;
use Buckaroo\PaymentMethods\ApplePay\ApplePay;
use Buckaroo\PaymentMethods\Bancontact\Bancontact;
use Buckaroo\PaymentMethods\Belfius\Belfius;
use Buckaroo\PaymentMethods\Billink\Billink;
use Buckaroo\PaymentMethods\BuckarooWallet\BuckarooWallet;
use Buckaroo\PaymentMethods\CreditCard\CreditCard;
use Buckaroo\PaymentMethods\CreditClick\CreditClick;
use Buckaroo\PaymentMethods\CreditManagement\CreditManagement;
use Buckaroo\PaymentMethods\EPS\EPS;
use Buckaroo\PaymentMethods\GiftCard\GiftCard;
use Buckaroo\PaymentMethods\Giropay\Giropay;
use Buckaroo\PaymentMethods\iDeal\iDeal;
use Buckaroo\PaymentMethods\iDealQR\iDealQR;
use Buckaroo\PaymentMethods\In3\In3;
use Buckaroo\PaymentMethods\KBC\KBC;
use Buckaroo\PaymentMethods\KlarnaKP\KlarnaKP;
use Buckaroo\PaymentMethods\KlarnaPay\KlarnaPay;
use Buckaroo\PaymentMethods\Payconiq\Payconiq;
use Buckaroo\PaymentMethods\Paypal\Paypal;
use Buckaroo\PaymentMethods\PayPerEmail\PayPerEmail;
use Buckaroo\PaymentMethods\PointOfSale\PointOfSale;
use Buckaroo\PaymentMethods\Przelewy24\Przelewy24;
use Buckaroo\PaymentMethods\RequestToPay\RequestToPay;
use Buckaroo\PaymentMethods\SEPA\SEPA;
use Buckaroo\PaymentMethods\Sofort\Sofort;
use Buckaroo\PaymentMethods\Tinka\Tinka;
use Buckaroo\PaymentMethods\BankTransfer\BankTransfer;
use Buckaroo\PaymentMethods\Trustly\Trustly;
use Buckaroo\PaymentMethods\WeChatPay\WeChatPay;
use Buckaroo\Transaction\Client;

class PaymentMethodFactory
{
    private static array $payments = [
        ApplePay::class                 => ['applepay'],
        Alipay::class                   => ['alipay'],
        Afterpay::class                 => ['afterpay'],
        AfterpayDigiAccept::class       => ['afterpaydigiaccept'],
        Bancontact::class               => ['bancontactmrcash'],
        Billink::class                  => ['billink'],
        Belfius::class                  => ['belfius'],
        BuckarooWallet::class           => ['buckaroo_wallet'],
        CreditCard::class               => ['creditcard', 'mastercard', 'visa', 'amex', 'vpay', 'maestro', 'visaelectron', 'cartebleuevisa', 'cartebancaire', 'dankort', 'nexi', 'postepay'],
        CreditClick::class              => ['creditclick'],
        CreditManagement::class         => ['credit_management'],
        iDeal::class                    => ['ideal', 'idealprocessing'],
        iDealQR::class                  => ['ideal_qr'],
        In3::class                      => ['in3'],
        KlarnaPay::class                => ['klarna'],
        KlarnaKP::class                 => ['klarnakp'],
        SEPA::class                     => ['sepadirectdebit', 'sepa'],
        KBC::class                      => ['kbcpaymentbutton'],
        Paypal::class                   => ['paypal'],
        PayPerEmail::class              => ['payperemail'],
        EPS::class                      => ['eps'],
        Sofort::class                   => ['sofort', 'sofortueberweisung'],
        Tinka::class                    => ['tinka'],
        Payconiq::class                 => ['payconiq'],
        Przelewy24::class               => ['przelewy24'],
        PointOfSale::class              => ['pospayment'],
        Giropay::class                  => ['giropay'],
        GiftCard::class                 => ['giftcard'],
        Trustly::class                  => ['trustly'],
        BankTransfer::class             => ['transfer'],
        RequestToPay::class             => ['requesttopay'],
        WeChatPay::class                => ['wechatpay'],
    ];

    private Client $client;
    private string $paymentMethod;

    public function __construct(
        Client $client,
        string $paymentMethod
    ) {
        $this->client = $client;
        $this->paymentMethod = strtolower($paymentMethod);
    }

    public function getPaymentMethod() : PaymentMethod
    {
        foreach(self::$payments as $class => $alias) {
            if(in_array($this->paymentMethod, $alias)) {
                return new $class($this->client, $this->paymentMethod);
            }
        }

        throw new SDKException($this->client->getLogger(), "Wrong payment method code has been given");
    }

    public static function get(
        Client $client,
        string $paymentMethod
    ): PaymentMethod {
        $factory = new self($client, $paymentMethod);
        return $factory->getPaymentMethod();
    }
}
