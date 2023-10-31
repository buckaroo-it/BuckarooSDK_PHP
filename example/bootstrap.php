<?php

namespace Example\Bootstrap;

use Dotenv\Dotenv;

require_once __DIR__ . "/../vendor/autoload.php";

// Load env
$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();
