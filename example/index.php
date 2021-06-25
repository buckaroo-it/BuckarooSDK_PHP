<?php
require_once (__DIR__ . "/init.php");

use \Buckaroo\SDK\Transaction;

$orderId = 's_' . date("Ymd H:i:s");

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
  <body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <div class="row d-flex justify-content-center">
        <a href="/"><h1>SDK test</h1></a>
        <div class="d-flex justify-content-center">
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
            echo 'push'; die();
            break;
        case 'return':
            echo 'return'; die();
            break;
        case 'returnCancel':
            echo 'returnCancel'; die();
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
            echo 'pendingPaymentStatus';
        }
        // header("Location: " . $response->getRedirectUrl(), true, 303);
        echo '<a href="'.$response->getRedirectUrl().'" target="_blank">Redirect</a>'; die();
    } elseif ($response->isSuccess() || $response->isAwaitingConsumer() || $response->isPendingProcessing() || $response->isWaitingOnUserInput()) {
        if(!$response->isSuccess()){
            echo 'pendingPaymentStatus';
        }
        echo 'checkout.finish.page'; die();
    } elseif ($response->isCanceled()) {
        die('CustomerCanceledAsyncPaymentException');
    }
}

/*$buckarooClient = new \Buckaroo\SDK\BuckarooClient();
$buckarooClient->setWebsiteKey($websiteKey);
$buckarooClient->setSecretKey($secretKey);

$request = new \Buckaroo\SDK\Buckaroo\Payload\TransactionRequest();
$request->setServiceName('ideal');
$request->setServiceVersion('2');
$request->setAmountCredit(0);
$request->setAmountDebit('10.10');
$request->setInvoice($orderId);
$request->setOrder($orderId);
$request->setCurrency($currencyCode);
$request->setServiceParameter('issuer', 'ABNANL2A');

$url = $buckarooClient->getTransactionUrl('test');
try {
    $response = $buckarooClient->post($url, $request, 'Buckaroo\SDK\Buckaroo\Payload\TransactionResponse');

    if ($response->hasRedirect()) {
        if($response->isAwaitingConsumer() || $response->isPendingProcessing() || $response->isWaitingOnUserInput()){
            echo 'pendingPaymentStatus';
        }
        echo '<a href="'.$response->getRedirectUrl().'" target="_blank">Redirect</a>'; die();
    } elseif ($response->isSuccess() || $response->isAwaitingConsumer() || $response->isPendingProcessing() || $response->isWaitingOnUserInput()) {
        if(!$response->isSuccess()){
            echo 'pendingPaymentStatus';
        }
        echo 'checkout.finish.page'; die();
    } elseif ($response->isCanceled()) {
        die('CustomerCanceledAsyncPaymentException');
    }
    echo 'other end';

} catch (Exception $e) {
    echo "<pre style='color:#ff0000'>";
    print_r($e->getMessage());
    echo "</pre>";die;
}

echo 'end';
*/