<?php
require_once (__DIR__ . "/includes/init.php");
require_once (__DIR__ . '/html/header.php');
?>
<h3>RETURN HANDLER</h3>
<?php
$app->handleReturn($_POST, $secretKey);
require_once (__DIR__ . '/html/footer.php');
?>
