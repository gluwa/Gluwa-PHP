<?php
header('Content-Type: text/html; charset=UTF-8');

// You only need to require it once.
require_once('../___library/GluwaPro/autoload.php');

$GluwaPro = new \Gluwa\GluwaPro([
    '__DEV__' => false, // If you want to run test on testnet, change this value to true. APIKey, APISecret and WebhookSecret must be filled with the values ​​obtained from Gluwa Dashboard's Sandbox Mode.
    'APIKey' => '',
    'APISecret' => '',
    'WebhookSecret' => '',
    'MasterEthereumPrivateKey' => '',
    'MasterEthereumAddress' => '',
]);


/**
 * POST - Create a New Transaction (https://api.gluwa.com/v1/Transactions)
 */
$Response = $GluwaPro->postTransaction([
    'Currency' => 'USDG',
    'Amount' => '1.78',
    'Target' => '', // Required - Target Address
    'MerchantOrderID' => '', // optional
    'Note' => '', // optional
    'Expiry' => 1800, // optional
]);


/**
 * POST - Create a Payment QR Code (https://api.gluwa.com/v1/QRCode)
 */
$Response = $GluwaPro->getPaymentQRCode([
    'Currency' => 'USDG',
    'Amount' => '1',
    'Note' => 'NoteContent',
    'MerchantOrderID' => '250',
    'Expiry' => 1800, // optional
]);


/**
 * GET - List Transaction History for an Address (https://api.gluwa.com/v1/:currency/Addresses/:address/Transactions)
 */
$Response = $GluwaPro->getListTransactionHistory([
    'Currency' => 'USDG',
    'Limit' => '100', // optional
    'Status' => 'Confirmed', // optional
    'Offset' => '0', // optional
]);


/**
 * GET - Retrieve Transaction Details by Hash (https://api.gluwa.com/v1/:currency/Transactions/:txnhash)
 */
$Response = $GluwaPro->getListTransactionDetail([
    'Currency' => 'USDG',
    'TxnHash' => '0x42caf24fd83f81b70cdf0a039de084dcd4bd60b65bf2494465e6fa27ab1de77b',
]);


/**
 * GET - Retrieve a Balance for an Address (https://api.gluwa.com/v1/:currency/Addresses/:address)
 */
$Response = $GluwaPro->getAddresses([
    'Currency' => 'USDG',
]);


/**
 * WEBHOOK Validation (https://app.gitbook.com/@gluwa/s/gluwa-documentation/development/webhooks)
 * When user completes transfer via the QR code, the Gluwa API sends a webhook to your webhook endpoint. Verify that the values ​​actually sent by the Gluwa server are correct.
 * 
 * Payload and Signature of webhook can be obtained as follows:
 * 
 * $Headers = getallheaders();
 * $Signature = $Headers['X-REQUEST-SIGNATURE'];
 * $Payload = file_get_contents("php://input");
 */

$Response2 = $GluwaPro->validateWebhook([
    'Payload' => '',
    'Signature' => '',
]);
