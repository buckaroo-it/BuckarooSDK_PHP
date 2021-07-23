<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <title>Buckaroo PHP SDK</title>
  </head>
  <body style="padding:10px;">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <div class="row d-flex">
        <h1><a href="<?php echo $baseUrl; ?>">Buckaroo PHP SDK examples</a></h1>
        <div class="d-flex">
            <ul>
                <li>
                    Transactions
                    <ul>
                        <li>
                            <a href="<?php echo $baseUrl; ?>transactions/ideal.php">ideal</a>
                        </li>
                        <li>
                            <a href="<?php echo $baseUrl; ?>transactions/ideal_refund.php">ideal (refund)</a>
                        </li>
                        <li>
                            <a href="<?php echo $baseUrl; ?>transactions/afterpay.php">afterpay (pay)</a>
                        </li>
                        <li>
                            <a href="<?php echo $baseUrl; ?>transactions/afterpay_auth_capture.php">afterpay (auth & capture)</a>
                        </li>
                        <li>
                            <a href="<?php echo $baseUrl; ?>transactions/oop_ideal.php">ideal (OOP style)</a>
                        </li>
                        <li>
                            <a href="<?php echo $baseUrl; ?>transactions/oop_afterpay.php">afterpay (OOP style)</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <h1>Logs</h1>
        <div class="d-flex">
            <ul>
                <li>
                    <a href="<?php echo $baseUrl; ?>logs/push.txt">pushes handler</a>
                </li>
                <li>
                    to see debug log : open your browser console
                </li>
            </ul>
        </div>
    </div>
    <div style="position:absolute;bottom:0;">
        tip
    </div>
    <em>
        <strong>
