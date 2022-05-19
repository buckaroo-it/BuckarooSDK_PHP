<?php 
require_once (__DIR__ . '/../includes/init.php');
require_once (__DIR__ . '/../html/header.php');

use Buckaroo\Buckaroo;

$buckaroo = new Buckaroo($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

$response = $buckaroo->authorize([
    'method'            => 'afterpay',
    'amountDebit'       => 50.30,
    'order'             => uniqid(),
    'invoice'           => uniqid(),
    'serviceParameters' => [
        'articles'      => [
            [
                'identifier' => 'Articlenumber1',
                'description' => 'Blue Toy Car',
                'vatPercentage' => '21',
                'quantity' => '2',
                'grossUnitPrice' => '20.10'
            ],
            [
                'identifier' => 'Articlenumber2',
                'description' => 'Red Toy Car',
                'vatPercentage' => '21',
                'quantity' => '1',
                'grossUnitPrice' => '10.10'
            ],
        ],
        'customer'      => [
            'useBillingInfoForShipping' => false,
            'billing'                   => [
                'firstName' => 'Test',
                'lastName' => 'Acceptatie',
                'email' => 'billingcustomer@buckaroo.nl',
                'phone' => '0109876543',
                'street' => 'Hoofdstraat',
                'streetNumber' => '80',
                'streetNumberAdditional' => 'A',
                'postalCode' => '8441EE',
                'city' => 'Heerenveen',
                'country' => 'NL',
                'salutation' => 'Mr',
                'birthDate' => '01-01-1990'
            ],
            'shipping'                  => [
                'firstName' => 'Test',
                'lastName' => 'Aflever',
                'email' => 'billingcustomer@buckaroo.nl',
                'phone' => '0109876543',
                'street' => 'Hoofdstraat',
                'streetNumber' => '80',
                'streetNumberAdditional' => 'A',
                'postalCode' => '8441EE',
                'city' => 'Heerenveen',
                'country' => 'NL',
                'salutation' => 'Mr',
                'birthDate' => '01-01-1990'
            ]
        ]
    ]
]);

//
////Products setup
//$serviceParams['products'][] = ['Identifier' => 'Articlenumber1',
//                                'Description' => 'Blue Toy Car',
//                                'VatPercentage' => '21',
//                                'Quantity' => '2',
//                                'GrossUnitPrice' => '20.10'
//                                ];
//$serviceParams['products'][] = ['Identifier' => 'Articlenumber2',
//                                'Description' => 'Red Toy Car',
//                                'VatPercentage' => '21',
//                                'Quantity' => '1',
//                                'GrossUnitPrice' => '10.10'
//                                ];
////Customer setup
//$serviceParams['customer']['billing'] = ['FirstName' => 'Test',
//                                         'LastName' => 'Acceptatie',
//                                         'Email' => 'billingcustomer@buckaroo.nl',
//                                         'Phone' => '0109876543',
//                                         'Street' => 'Hoofdstraat',
//                                         'StreetNumber' => '80',
//                                         'StreetNumberAdditional' => 'A',
//                                         'PostalCode' => '8441EE',
//                                         'City' => 'Heerenveen',
//                                         'Country' => 'NL',
//                                         'Salutation' => 'Mr',
//                                         'BirthDate' => '01-01-1990'
//
//                                        ];
//
////Set to 1 to use billing address for shipping
//$serviceParams['customer']['use_billing_info_for_shipping'] = 1;
//
////Ship order to other address
//$serviceParams['customer']['shipping'] = ['FirstName' => 'Test',
//                                         'LastName' => 'Aflever',
//                                         'Email' => 'billingcustomer@buckaroo.nl',
//                                         'Phone' => '0109876543',
//                                         'Street' => 'Hoofdstraat',
//                                         'StreetNumber' => '80',
//                                         'StreetNumberAdditional' => 'A',
//                                         'PostalCode' => '8441EE',
//                                         'City' => 'Heerenveen',
//                                         'Country' => 'NL',
//                                         'Salutation' => 'Mr',
//                                         'BirthDate' => '01-01-1990'
//                                        ];
//
////TransactionRequest payload
//$payload = ['method' => 'afterpay',
//            'amountDebit' => 50.30,
//            'clientIP' => $_ENV['BPE_EXAMPLE_IP'],
//            'serviceParameters' => $serviceParams
//           ];
//
//$payload = json_encode($payload); //Payload can be also json
//
//try {
//    $buckaroo = new Buckaroo($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
//    $response = $buckaroo->authorize($payload);
//    $app->handleResponse($response);
//} catch (\Exception $e) {
//    $app->handleException($e);
//}
//
//require_once (__DIR__ . '/../html/footer.php');