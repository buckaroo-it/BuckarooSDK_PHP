<?php
require(__DIR__ . '/../../../autoload.php');

error_reporting(E_ALL);
ini_set("display_errors", 1);

$websiteKey = "PUT_YOUR_WEBSITE_KEY_HERE";
$secretKey = "PUT_YOUR_SECRET_KEY_HERE";
$currencyCode = 'EUR';

$baseUrl = 'https://example.com/';
$returnURL = $baseUrl . 'index.php?status=return';
$returnURLCancel = $baseUrl . 'index.php?status=returnCancel';
$pushURL =  $baseUrl . 'index.php?status=push';

