<?php
require_once (__DIR__ . "/includes/init.php");
require_once (__DIR__ . '/html/header.php');
$app->handleReturn($_POST, $secretKey);
require_once (__DIR__ . '/html/footer.php');
?>
