<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Client;
use Buckaroo\Exceptions\SdkException;

class PaymentMethodFactory
{
    private static array $payments = [
        ApplePay::class                 => ['applepay'],
//        Alipay::class                   => ['alipay'],
        Afterpay::class                 => ['afterpay'],
        AfterpayDigiAccept::class       => ['afterpaydigiaccept'],
        Bancontact::class               => ['bancontactmrcash'],
        Billink::class                  => ['billink'],
        Belfius::class                  => ['belfius'],
        CreditCard::class               => ['creditcard', 'mastercard', 'visa', 'amex', 'vpay', 'maestro', 'visaelectron', 'cartebleuevisa', 'cartebancaire', 'dankort', 'nexi', 'postepay'],
        Ideal::class                    => ['ideal', 'idealprocessing'],
        In3::class                      => ['in3'],
        KlarnaPay::class                => ['klarna'],
        KlarnaKP::class                 => ['klarnakp'],
        Sepa::class                     => ['sepadirectdebit'],
        Kbc::class                      => ['kbcpaymentbutton'],
        Paypal::class                   => ['paypal'],
        Eps::class                      => ['eps'],
        Sofort::class                   => ['sofort'],
        Payconiq::class                 => ['payconiq'],
        //P24::class                      => ['przelewy24'],
        Giropay::class                  => ['giropay'],
        GiftCard::class                 => ['giftcard'],
//        Transfer::class                 => ['transfer'],
//        RTP::class                      => ['requesttopay'],
//        WechatPay::class                => ['wechatpay'],
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

        throw new SdkException($this->client->getLogger(), __METHOD__, "Wrong payment method code has been given");
    }

    public static function get(
        Client $client,
        string $paymentMethod
    ): PaymentMethod {
        $factory = new self($client, $paymentMethod);
        return $factory->getPaymentMethod();
    }
}
