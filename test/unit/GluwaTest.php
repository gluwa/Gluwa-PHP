<?php

namespace Gluwa;

use Gluwa\Gluwa;
use \Gluwa\GluwaSDKException as GluwaException;

class GluwaTest extends TestCase
{

    private $Gluwa = null;

    private $Configuration_DEV = true;
    private $Configuration_APIKey = 'e7b81a2e-326d-4116-9f22-698a164fb4a9';
    private $Configuration_APISecret = 'pDIlEKQjzM7zaG4yTp0t0OZ40_Sxk_sASIByOMUZqHm22ZytHxOWP0DE2C12lpXC';
    private $Configuration_WebhookSecret = 'Aam3JTaJVvdSt4Ejukbychvp8ZMNBrpa-6aYSpu9Fw0wX-vBOn5A__HEi4299xL0';
    private $Configuration_MasterEthereumPrivateKey = '0xbc4d27937ef272288f89203dcad09319c0f369f0bbc796a7005635fb64088f5d';
    private $Configuration_MasterEthereumAddress = '0x74515A37703D7ba67D56Fe3CF0d0977966C87660';

    private $PostTransaction_Currency = 'USDG';
    private $PostTransaction_Amount = '100';
    private $PostTransaction_Target = '';
    private $PostTransaction_MerchantOrderID = '';
    private $PostTransaction_Note = '';
    private $PostTransaction_Expiry = 1800;

    private $GetPaymentQRCode_Currency = 'USDG';
    private $GetPaymentQRCode_Amount = '100';
    private $GetPaymentQRCode_Note = 'NoteContent';
    private $GetPaymentQRCode_MerchantOrderID = '250';
    private $GetPaymentQRCode_Expiry = 1800;

    private $GetListTransactionHistory_Currency = 'USDG';
    private $GetListTransactionHistory_Limit = '100';
    private $GetListTransactionHistory_Status = 'Confirmed';
    private $GetListTransactionHistory_Offset = '0';

    private $GetListTransactionDetail_Currency = 'USDG';
    private $GetListTransactionDetail_TxnHash = '';

    private $GetAddresses_Currency = 'USDG';
    
    private $ValidateWebhook_Payload = '';
    private $ValidateWebhook_Signature = '';


    /**
     * Init Gluwa
     */
    public function setUp() {
        parent::setUp();
        $this->Gluwa = new Gluwa([
            '__DEV__' => $this->Configuration_DEV, // If you want to run test on testnet, change this value to true. APIKey, APISecret and WebhookSecret must be filled with the values ​​obtained from Gluwa Dashboard's Sandbox Mode.
            'APIKey' => $this->Configuration_APIKey,
            'APISecret' => $this->Configuration_APISecret,
            'WebhookSecret' => $this->Configuration_WebhookSecret,
            'MasterEthereumPrivateKey' => $this->Configuration_MasterEthereumPrivateKey,
            'MasterEthereumAddress' => $this->Configuration_MasterEthereumAddress,
        ]);
    }

    /**
     * POST - Create a New Transaction (https://api.gluwa.com/v1/Transactions)
     */
    public function testPostTransaction() {
        try {
            $Response = $this->Gluwa->postTransaction([
                'Currency' => $this->PostTransaction_Currency,
                'Amount' => $this->PostTransaction_Amount,
                'Target' => $this->PostTransaction_Target, // Required - Target Address
                'MerchantOrderID' => $this->PostTransaction_MerchantOrderID, // optional
                'Note' => $this->PostTransaction_Note, // optional
                'Expiry' => $this->PostTransaction_Expiry, // optional
            ]);
            $this->ParseResponse($Response);
        } catch (GluwaException $e) {
            $this->assertNull($e->getMessage());
        }
    }

    /**
     * POST - Create a Payment QR Code (https://api.gluwa.com/v1/QRCode)
     */
    public function testGetCreatePaymentQRCode() {
        try {
            $Response = $this->Gluwa->getPaymentQRCode([
                'Currency' => $this->GetPaymentQRCode_Currency,
                'Amount' => $this->GetPaymentQRCode_Amount,
                'Note' => $this->GetPaymentQRCode_Note,
                'MerchantOrderID' => $this->GetPaymentQRCode_MerchantOrderID,
                'Expiry' => $this->GetPaymentQRCode_Expiry, // optional
            ]);
            $this->ParseResponse($Response);
        } catch (GluwaException $e) {
            $this->assertNull($e->getMessage());
        }
    }
    
    /**
     * GET - List Transaction History for an Address (https://api.gluwa.com/v1/:currency/Addresses/:address/Transactions)
     */
    public function testGetListTransactionHistory() {
        try {
            $Response = $this->Gluwa->getListTransactionHistory([
                'Currency' => $this->GetListTransactionHistory_Currency,
                'Limit' => $this->GetListTransactionHistory_Limit, // optional
                'Status' => $this->GetListTransactionHistory_Status, // optional
                'Offset' => $this->GetListTransactionHistory_Offset, // optional
            ]);
            $this->ParseResponse($Response);
        } catch (GluwaException $e) {
            $this->assertNull($e->getMessage());
        }
    }
    
    /**
     * GET - Retrieve Transaction Details by Hash (https://api.gluwa.com/v1/:currency/Transactions/:txnhash)
     */
    public function testGetListTransactionDetail() {
        try {
            $Response = $this->Gluwa->getListTransactionDetail([
                'Currency' => $this->GetListTransactionDetail_Currency,
                'TxnHash' => $this->GetListTransactionDetail_TxnHash,
            ]);
            $this->ParseResponse($Response);
        } catch (GluwaException $e) {
            $this->assertNull($e->getMessage());
        }
    }
    
    /**
     * GET - Retrieve a Balance for an Address (https://api.gluwa.com/v1/:currency/Addresses/:address)
     */
    public function testGetAddresses() {
        try {
            $Response = $this->Gluwa->getAddresses([
                'Currency' => $this->GetAddresses_Currency,
            ]);
            $this->ParseResponse($Response);
        } catch (GluwaException $e) {
            $this->assertNull($e->getMessage());
        }
    }

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
    public function testValidateWebhook() {
        $Response = $this->Gluwa->validateWebhook([
            'Payload' => $this->ValidateWebhook_Payload,
            'Signature' => $this->ValidateWebhook_Signature,
        ]);
        $this->assertTrue($Response);
    }
    
    
    private function ParseResponse(&$Response) {
        if (is_array($Response)) {
            if (array_key_exists('code', $Response)) {
                $this->assertEquals($Response['code'], 200);
                $Response = $Response['response'];
                echo PHP_EOL;
                var_dump($Response);
                echo PHP_EOL;
            } else {
                throw new \Exception($Response);
            }
        }
    }
}