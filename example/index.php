<?php
require_once (__DIR__ . "/init.php");

use Buckaroo\SDK\Transaction;

?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <title>SDK</title>
  </head>
  <body style="padding:10px;">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <div class="row d-flex">
        <a href="<?php echo $baseUrl; ?>"><h1>SDK test</h1></a>
        <div class="d-flex">
            <form action="" method="post">
                <div class="form-group">
                    <label for="orderId">order Id</label>
                    <input type="text" class="form-control" name="orderId" id="orderId" value="<?php echo $orderId; ?>" placeholder="orderId">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
  </body>
</html>

<?php

if(isset($_GET['status'])){
    switch ($_GET['status']) {
        case 'push':
            $response = Transaction::push([
                "post" => $_POST,
                "secretKey" => $secretKey,
            ]);
            echo '<br><br>push'; die();
            break;
        case 'return':
            echo '<br><br>return'; var_dump($_POST);die();
            break;
        case 'returnCancel':
            echo '<br><br>returnCancel'; die();
            break;
    }
}

if(isset($_POST['orderId'])){
    $orderId = $_POST['orderId'];

    $response = Transaction::create(
        $client,
        [
            "serviceName" => 'ideal',
            "serviceVersion" => 2,
            "amountDebit" => '10.10',
            "invoice" => $orderId,
            "order" => $orderId,
            "currency" => $currencyCode,
            "issuer" => 'ABNANL2A',
            "returnURL" => $returnURL,
            "returnURLCancel" => $returnURLCancel,
            "pushURL" => $pushURL,
        ]
    );

    if ($response->hasRedirect()) {
        if($response->isAwaitingConsumer() || $response->isPendingProcessing() || $response->isWaitingOnUserInput()){
            echo 'Status: pendingPaymentStatus';
        }
        // header("Location: " . $response->getRedirectUrl(), true, 303);
        echo '<br><br><a href="'.$response->getRedirectUrl().'" target="_blank">Proceed with redirect</a>'; die();
    } elseif ($response->isSuccess() || $response->isAwaitingConsumer() || $response->isPendingProcessing() || $response->isWaitingOnUserInput()) {
        if(!$response->isSuccess()){
            echo '<br><br>Status: pendingPaymentStatus';
        }
        echo '<br><br>checkout.finish.page'; die();
    } elseif ($response->isCanceled()) {
        die('CustomerCanceledAsyncPaymentException');
    }
}

