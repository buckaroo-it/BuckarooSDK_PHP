<?php

namespace Buckaroo\Tests;

use Buckaroo\Buckaroo;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class BuckarooTestCase extends TestCase
{
    public function __construct()
    {
        $dotenv = Dotenv::createImmutable('../');
        $dotenv->load();

        $this->buckaroo = new Buckaroo($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

        parent::__construct();
    }
}