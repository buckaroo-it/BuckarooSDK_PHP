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
        PaymentMethod::AFTERPAY => 'AfterpayNew',
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

    public static function getPaymentMethod(
        Client $client,
        string $paymentMethod
    ): PaymentMethod {
        $bankCards = CreditCard::getCards();

        $paymentMethod = strtolower($paymentMethod);

        $matches = self::$classes;
        if (isset($matches[$paymentMethod])) {
            $className = $matches[$paymentMethod];
        } elseif (in_array($paymentMethod, $bankCards)) {
            $className = 'CreditCard';
        } else {
            throw new SdkException($client->getLogger(), __METHOD__, "Wrong payment method code has been given");
        }

        $className = '\Buckaroo\PaymentMethods\\' . $className;
        $config = new Config();
        $serviceParam = new ServiceParam($config);
        $requestValidator = new RequestValidator();
        $paymentMethodObject = new $className($client, $config, $serviceParam, $requestValidator);
        return $paymentMethodObject;
    }

    public static function getMethods(): array
    {
        return array_keys(self::$classes);
    }
}
