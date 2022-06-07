<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Exceptions\SDKException;
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
        CreditCard::class               => ['creditcard', 'mastercard', 'visa', 'amex', 'vpay', 'maestro', 'visaelectron', 'cartebleuevisa', 'cartebancaire', 'dankort', 'nexi', 'postepay'],
        CreditClick::class              => ['creditclick'],
        Ideal::class                    => ['ideal', 'idealprocessing'],
        IdealQR::class                  => ['ideal_qr'],
        In3::class                      => ['in3'],
        KlarnaPay::class                => ['klarna'],
        KlarnaKP::class                 => ['klarnakp'],
        Sepa::class                     => ['sepadirectdebit'],
        Kbc::class                      => ['kbcpaymentbutton'],
        Paypal::class                   => ['paypal'],
        Eps::class                      => ['eps'],
        Sofort::class                   => ['sofort', 'sofortueberweisung'],
        Tinka::class                    => ['tinka'],
        Payconiq::class                 => ['payconiq'],
        Przelewy24::class               => ['przelewy24'],
        Pos::class                      => ['pospayment'],
        Giropay::class                  => ['giropay'],
        GiftCard::class                 => ['giftcard'],
        Trustly::class                  => ['trustly'],
        Transfer::class                 => ['transfer'],
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
