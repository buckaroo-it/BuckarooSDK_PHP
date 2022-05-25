<?php
//WIP





//
//declare(strict_types=1);
//
//namespace Buckaroo\PaymentMethods;
//
//use Buckaroo\Model\PaymentPayload;
//use Buckaroo\Model\RefundPayload;
//use Buckaroo\Model\ServiceList;
//
//class CreditCard extends PaymentMethod
//{
//    public const SERVICE_VERSION = 2;
//
//    public static function getCards(): array
//    {
//        return [
//            'vpay', 'bancontactmrcash', 'cartebancaire', 'mastercard', 'visa', 'maestro', 'visaelectron',
//            'cartebleuevisa', 'dankort', 'nexi', 'postepay', 'amex'
//        ];
//    }
//
//    public function setPayServiceList(array $serviceParameters = []): self
//    {
//        new ServiceList(
//            'CreditCard',
//            self::SERVICE_VERSION,
//            'Pay'
//        );
//
//        return $this;
//    }
//
//    public function setRefundServiceList(): self
//    {
//        new ServiceList(
//            'CreditCard',
//            self::SERVICE_VERSION,
//            'Refund'
//        );
//
//        return $this;
//    }
//}
