# Gluwa SDK for PHP

**Dear users, Sandbox environment has been deprecated, please update your code to avoid any further errors**


If your service is developed in PHP, the features we provide are available through the SDK. The Gluwa SDK for PHP is a library with powerful features that enable PHP developers to easily make requests to the Gluwa APIs.

## Update

v1.1.0 - Add support sNGNG \(20/05/2021\)
v1.0.9 - Add support sUSDCG \(01/06/2021\)

## Getting started

Download the PHP Package below and upload it to your server. The SDK requires PHP 5.6 or greater.

```bash
$ composer require gluwa/gluwa-php
```

Create and initialize a `Gluwa` object. Then, enter the `APIKey`, `APISecret` and `WebookSecret` generated from the [Gluwa Dashboard](https://dashboard.gluwa.com), and an Ethereum wallet to manage your funds. You can use credentials from sandbox dashboard and a Goerli wallet if you want to test in the sandbox environment.

```php
<?php
// Specify your vendor path.
require_once('vendor/autoload.php');

// If you want to run test on the sandbox mode, change below to values ​​obtained from Gluwa Dashboard's sandbox mode.
$Configuration_DEV = false; // "true" if you want to use the sandbox mode

$Configuration_APIKey = '{Your API Key}';
$Configuration_APISecret = '{Your API Secret}';
$Configuration_WebhookSecret = '{Your Webhook Secret}';
$Configuration_MasterEthereumPrivateKey = '{Your Ethereum Private Key}';
$Configuration_MasterEthereumAddress = '{Your Ethereum Address}';

$Gluwa = new Gluwa\Gluwa([
    '__DEV__' => $Configuration_DEV,
    'APIKey' => $Configuration_APIKey,
    'APISecret' => $Configuration_APISecret,
    'WebhookSecret' => $Configuration_WebhookSecret,
    'MasterEthereumPrivateKey' => $Configuration_MasterEthereumPrivateKey,
    'MasterEthereumAddress' => $Configuration_MasterEthereumAddress,
]);
```

If you are using PHP 5.6, you need to enable [**php-bcmath**](https://www.php.net/manual/en/book.bc.php)
because it is not enabled by default prior PHP 7. If it is not installed yet, just install it. Please visit [here](https://www.php.net/manual/en/book.bc.php) for more information.

If you receive the following message, you should install [**gmp extention** ](https://www.php.net/manual/en/book.gmp.php)on your server. Please visit [this site](https://www.php.net/manual/en/book.gmp.php) to find a solution.

> Function gmp\_init is unavailable. Please make sure php\_gmp extension is available

Now you are ready to use the Gluwa API.

## Method Examples

#### [Create a New Transaction](https://docs.gluwa.com/api/api.md#create-a-new-transaction)

```php
$PostTransaction_Currency = '{USDG or sUSDCG or KRWG or sNGNG}';
$PostTransaction_Amount = '{Sending Amount}';
$PostTransaction_Target = '{Receiver\'s Address}';
$PostTransaction_MerchantOrderID = '{Merchant\'s Order ID. Optional}';
$PostTransaction_Note = '{Custom Note. Optional}';
$PostTransaction_Expiry = {Expiry of the Transfer Request. Optional};

$Response = $Gluwa->postTransaction([
    'Currency' => $PostTransaction_Currency,
    'Amount' => $PostTransaction_Amount,
    'Target' => $PostTransaction_Target,
    'MerchantOrderID' => $PostTransaction_MerchantOrderID, // optional
    'Note' => $PostTransaction_Note, // optional
    'Expiry' => $PostTransaction_Expiry, // optional
]);
```

#### [Create a Payment QR Code](https://docs.gluwa.com/api/api.md#create-a-payment-qr-code)

```php
$Response = $Gluwa->getPaymentQRCode([
    'Currency' => 'USDG', // USDG or sUSDCG or KRWG or sNGNG
    'Amount' => '1',
    'Note' => '', // optional
    'MerchantOrderID' => '', // optional
    'Expiry' => 1800, // optional
]);
```

`getPaymentQRCode` API returns a QR code png image as a Base64 string. You can display the image on your website as below:

```markup
<img src="data:image/png;base64,{BASE64_STRING_YOU_RECEIVED}" alt="Gluwa Payment QR Code">
```

#### [List Transaction History for an Address](https://docs.gluwa.com/api/api.md#list-transaction-history-for-an-address)

```php
$Response = $Gluwa->getListTransactionHistory([
    'Currency' => 'USDG', // USDG or sUSDCG or KRWG or sNGNG
    'Limit' => '100', // optional
    'Status' => 'Confirmed', // optional
    'Offset' => '0', // optional
]);
```

#### [Retrieve Transaction Details by Hash](https://docs.gluwa.com/api/api.md#retrieve-transaction-details-by-hash)

```php
$Response = $Gluwa->getListTransactionDetail([
    'Currency' => 'USDG', // USDG or sUSDCG or KRWG or sNGNG
    'TxnHash' => '',
]);
```

#### [Retrieve a Balance for an Address](https://docs.gluwa.com/api/api.md#retrieve-a-balance-for-an-address)

```php
$Response = $Gluwa->getAddresses([
    'Currency' => 'USDG', // USDG or sUSDCG or KRWG or sNGNG
]);
```

#### [Webhook Validation](https://docs.gluwa.com/development/webhooks#step-3-verify-your-wallet-address)

When user completes transfer via the QR code, the Gluwa API sends a webhook to your webhook endpoint. Verify that the values ​​actually sent by the Gluwa server are correct.

Payload and Signature of webhook can be obtained as follows:

```php
$Headers = getallheaders();
$Signature = $Headers['X-REQUEST-SIGNATURE'];
$Payload = file_get_contents("php://input");
```

Verify the requested Signature and Payload as follows:

```php
$Response2 = $Gluwa->validateWebhook([
    'Payload' => $Payload,
    'Signature' => $Signature,
]);
```

| Type | Description |
| :--- | :--- |
| boolean | This will return `true` if it is a valid webhook. If it returns `false`, you either set an incorrect secret or the webhook did not originate from Gluwa. |
