<?php
declare(strict_types=1);

namespace Buckaroo\Model;

class Payload
{

   public static function getDefaultPayload() : Array
   {
    return ['serviceVersion' => 2,
            'serviceAction' => 'Pay',
            'invoice' => \Buckaroo\Example\App::getOrderId(),
            'order' => \Buckaroo\Example\App::getOrderId(),
            'currency' => $_ENV['BPE_EXAMPLE_CURRENCY_CODE'],
            'returnURL' => $_ENV['BPE_EXAMPLE_RETURN_URL'],
            'returnURLCancel' => $_ENV['BPE_EXAMPLE_RETURN_URL'],
            'pushURL' => $_ENV['BPE_EXAMPLE_RETURN_URL'],
            ];
   }
}