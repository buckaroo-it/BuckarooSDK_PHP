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

namespace Tests\Buckaroo\Payments;

use Tests\Buckaroo\BuckarooTestCase;
use Buckaroo\Handlers\Reply\ReplyHandler;

class PushTest extends BuckarooTestCase
{
    /**
     * @test
     */
    public function it_test_the_push_response()
    {
        $post_data = '{"Transaction":{"Key":"308E1CE5A7914DCC9212040A4DB17528","Invoice":"RqovbgXe4o4Ejvqt","ServiceCode":"afterpay","Status":{"Code":{"Code":190,"Description":"Success"},"SubCode":{"Code":"S990","Description":"The request was successful."},"DateTime":"2024-11-25T13:51:10"},"IsTest":true,"Order":"RqovbgXe4o4Ejvqt","Currency":"EUR","AmountDebit":79.95,"TransactionType":"C039","Services":null,"CustomParameters":null,"AdditionalParameters":null,"MutationType":1,"RelatedTransactions":null,"IsCancelable":false,"IssuingCountry":"NL","StartRecurrent":false,"Recurring":false,"CustomerName":"Test Vries","PayerHash":null,"PaymentKey":"D215D7F62CEF41308B2230EAE3BC85A1","Description":"buckaroo 2130"}}';
        $uri = 'https://buckaroo.dev/push';
        $timestamp = '1732545581';
        $nonce = 'ac9bcf061ac14a02b7e52b2328119de5';

        $md5 = md5($post_data, true);
        $base64Data = base64_encode($md5);
        $hmac = $_ENV['BPE_WEBSITE_KEY'] . 'POST' . 'buckaroo.dev%2fpush' . $timestamp . $nonce . $base64Data;
        $hash = base64_encode(hash_hmac('sha256', $hmac, $_ENV['BPE_SECRET_KEY'], true));

        $auth_header = "{$_ENV['BPE_WEBSITE_KEY']}:{$hash}:{$nonce}:{$timestamp}";

        $reply_handler = new ReplyHandler($this->buckaroo->client()->config(), $post_data, $auth_header, $uri);
        $reply_handler->validate();

        $this->assertTrue($reply_handler->isValid());
    }
}
