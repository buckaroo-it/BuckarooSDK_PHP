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

namespace Tests\Buckaroo;

use Buckaroo\BuckarooClient;
use Buckaroo\Config\DefaultConfig;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class BuckarooTestCase extends TestCase
{
    protected BuckarooClient $buckaroo;
    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(getcwd());
        $dotenv->load();

//        $this->buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
//
//        ?string $mode = null,
//        ?string $currency = null,
//        ?string $returnURL = null,
//        ?string $returnURLCancel = null,
//        ?string $pushURL = null,
//        ?string $platformName = null,
//        ?string $moduleSupplier = null,
//        ?string $moduleName = null,
//        ?string $moduleVersion = null,
        
        $this->buckaroo = new BuckarooClient(new DefaultConfig(
            $_ENV['BPE_WEBSITE_KEY'],
            $_ENV['BPE_SECRET_KEY'],
            $_ENV['BPE_MODE'] ?? null,
            $_ENV['BPE_CURRENCY_CODE'] ?? null,
            $_ENV['BPE_RETURN_URL'] ?? null,
            $_ENV['BPE_RETURN_URL_CANCEL'] ?? null,
            $_ENV['BPE_PUSH_URL'] ?? null,
            'TestingPlatform',
            '3.0.0',
            'TestingModule',
            'Testing',
            '2.4.0',
            'nl-NL'
        ));

        parent::__construct();
    }
}
