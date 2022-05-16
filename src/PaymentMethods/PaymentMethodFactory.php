<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Client;
use Buckaroo\Exceptions\SdkException;
use Buckaroo\Model\Config;
use Buckaroo\Model\RequestValidator;
use Buckaroo\Model\ServiceParam;

class PaymentMethodFactory
{
    private static array $classes = [
        PaymentMethod::AFTERPAY => 'Afterpay',
        PaymentMethod::KLARNAKP => 'Klarna',
        PaymentMethod::KLARNA => 'KlarnaPay',
        PaymentMethod::SEPA => 'Sepa',
        PaymentMethod::KBC => 'Kbc',
        PaymentMethod::PAYPAL => 'Paypal',
        PaymentMethod::EPS => 'Eps',
        PaymentMethod::SOFORT => 'Sofort',
        PaymentMethod::PAYCONIQ => 'Payconiq',
        PaymentMethod::P24 => 'P24',
        PaymentMethod::IDEAL => 'Ideal',
        PaymentMethod::CAPAYABLE => 'Capayable',
        PaymentMethod::GIROPAY => 'Giropay',
        PaymentMethod::GIFTCARD => 'GiftCard',
        PaymentMethod::TRANSFER => 'Transfer',
        PaymentMethod::RTP => 'RequestToPay',
        PaymentMethod::APPLEPAY => 'ApplePay',
        PaymentMethod::ALIPAY => 'Alipay',
        PaymentMethod::WECHATPAY => 'WeChatPay',
        PaymentMethod::BILLINK => 'Billink',
        PaymentMethod::BELFIUS => 'Belfius',
    ];

    private Client $client;
    private string $paymentMethod;

    public function __construct(
        Client $client,
        string $paymentMethod
    ) {
        $this->client = $client;
        $this->paymentMethodRequest = $paymentMethod;
    }

    public function getPaymentMethod() : PaymentMethod
    {
        $class = $this->determinePaymentClass();

        $config = new Config();
        $serviceParam = new ServiceParam($config);
        $requestValidator = new RequestValidator();
        $paymentMethodObject = new $class($this->client, $config, $serviceParam, $requestValidator);

        return $paymentMethodObject;
    }

    private function determinePaymentClass()
    {
        $bankCards = CreditCard::getCards();

        $paymentMethod = strtolower($this->paymentMethodRequest);

        $matches = self::$classes;

        if(isset($matches[$paymentMethod]))
        {
            return '\Buckaroo\PaymentMethods\\' . $matches[$paymentMethod];
        }

        if (in_array($paymentMethod, $bankCards)) {
            return CreditCard::class;
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

    public static function getMethods(): array
    {
        return array_keys(self::$classes);
    }
}
