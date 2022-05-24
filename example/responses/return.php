<?php
require_once (__DIR__ . "/includes/init.php");
require_once (__DIR__ . '/html/header.php');
$app->handleReturn($_POST, $_ENV['BPE_SECRET_KEY']);
require_once (__DIR__ . '/html/footer.php');
?>
