<?php

namespace Gluwa;

use Gluwa\Ethereum;

/**
 * Class Gluwa
 *
 * @package Gluwa
 */
class Gluwa {

    /**
     * @const string API Host.
     */
    const host = '';

    /**
     * @const boolean Is DEV mode.
     */
    const __DEV__ = false;

    /**
     * @const string APIKey.
     */
    const API_KEY = '';

    /**
     * @protected string The APIKey.
     */
    protected $APIKey;

    /**
     * @const string APISecret.
     */
    const API_SECRET = '';

    /**
     * @protected string The APISecret.
     */
    protected $APISecret;

    /**
     * @const string WebhookSecret.
     */
    const WEBHOOK_SECRET = '';

    /**
     * @protected string The WebhookSecret.
     */
    protected $WebhookSecret;

    /**
     * @const string MasterEthereumPrivateKey.
     */
    const MASTER_ETHEREUM_PRIVATE_KEY = '';

    /**
     * @protected string The MasterEthereumPrivateKey.
     */
    protected $MasterEthereumPrivateKey;

    /**
     * @const string MasterEthereumAddress.
     */
    const MASTER_ETHEREUM_ADDRESS = '';

    /**
     * @protected string The MasterEthereumAddress.
     */
    protected $MasterEthereumAddress;

    /**
     * @private array RequiredFunctions.
     */
    private $RequiredFunctions = array('gmp_init', 'bcmul', 'bcdiv');

    /**
     * @private object FunctionExists.
     */
    private $FunctionExists = null;

    /**
     * Instantiates a new Gluwa super-class object.
     *
     * @param array $config
     *
     * @throws GluwaSDKException
     */
    
    public function __construct(array $config = []) {
        $APIKey = isset($config['APIKey']) ? $config['APIKey'] : getenv(static::API_KEY);
        if (!$APIKey) {
            throw new GluwaSDKException('Required "APIKey" key not supplied in config.');
        }
        $this->APIKey = $APIKey;

        $APISecret = isset($config['APISecret']) ? $config['APISecret'] : getenv(static::API_SECRET);
        if (!$APISecret) {
            throw new GluwaSDKException('Required "APISecret" key not supplied in config.');
        }
        $this->APISecret = $APISecret;

        $WebhookSecret = isset($config['WebhookSecret']) ? $config['WebhookSecret'] : getenv(static::WEBHOOK_SECRET);
        if (!$WebhookSecret) {
            throw new GluwaSDKException('Required "WebhookSecret" key not supplied in config.');
        }
        $this->WebhookSecret = $WebhookSecret;

        $MasterEthereumPrivateKey = isset($config['MasterEthereumPrivateKey']) ? $config['MasterEthereumPrivateKey'] : getenv(static::MASTER_ETHEREUM_PRIVATE_KEY);
        if (!$MasterEthereumPrivateKey) {
            throw new GluwaSDKException('Required "MasterEthereumPrivateKey" key not supplied in config.');
        }
        $this->MasterEthereumPrivateKey = $MasterEthereumPrivateKey;

        $MasterEthereumAddress = isset($config['MasterEthereumAddress']) ? $config['MasterEthereumAddress'] : getenv(static::MASTER_ETHEREUM_ADDRESS);
        if (!$MasterEthereumAddress) {
            throw new GluwaSDKException('Required "MasterEthereumAddress" key not supplied in config.');
        }
        $this->MasterEthereumAddress = $MasterEthereumAddress;

        $this->__DEV__ = isset($config['__DEV__']) ? $config['__DEV__'] : false;
        $this->setAPIHost();
        $this->FunctionExists = $this->FunctionExists();
    }

    private function setAPIHost() {
        $this->host = $this->__DEV__ ? 'https://sandbox.api.gluwa.com' : 'https://api.gluwa.com';
    }

    private function getAuthorization() {
        return base64_encode($this->APIKey . ':' . $this->APISecret);
    }

    private function getContractAddress($Currency, $__DEV__) {
        if ($Currency === 'USDG') {
            if ($__DEV__) {
                return '0x8e9611f8ebc9323EdDA39eA2d8F31bbb2436adEE';
            } else {
                return '0xfb0aaa0432112779d9ac483d9d5e3961ece18eec';
            }
        } else if ($Currency === 'sUSDCG') {
            if ($__DEV__) {
                return '0x5f71cbAebb9c1e8F1664a8eF2e1cFF2ED8044eE0';
            } else {
                return '0x39589FD5A1D4C7633142A178F2F2b30314FB2BaF';
            }
        } else if ($Currency === 'KRWG') {
            if ($__DEV__) {
                return '0x408b7959b3e15b8b1e8495fa9cb123c0180d44db';
            } else {
                return '0x4cc8486f2f3dce2d3b5e27057cf565e16906d12d';
            }
        } else if ($Currency === 'sNGNG') {
            if ($__DEV__) {
                return '0x5cb4744Bb6bcC360A63f54499d92c7617F0a8f8c';
            } else {
                return '0xc33496C93AaFf765e4925A4E3b873d5efc635405';
            }
        }
    }

    private function getTimestampSignature() {
        $Timestamp = strval(time());
        $FlatSignature = $Timestamp.'.'.Ethereum::sign($Timestamp, $this->MasterEthereumPrivateKey);
        $Signature = base64_encode($FlatSignature);
        return $Signature;
    }

    private function curl(array $htArg = []) {
        $ch = curl_init();
        $sUrl = $htArg['sUrl']; // String
        $sMethod = $htArg['sMethod']; // String: 'POST' / 'GET'
        $aParam = $htArg['aParam']; // Array
        $sParamType = $htArg['sParamType']; // String: 'JSON' / 'Array'
        $aHeader = $htArg['aHeader']; // Array

        if ($sParamType == 'JSON') {
            $sParam = json_encode($aParam);
        } else {
            $sParam = http_build_query($aParam, '', '&');
        }

        if ($sMethod == 'GET') {
            $sUrl = $sUrl . '?' . $sParam;
        } else {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $sParam);
        }

        if ($sMethod == 'PUT') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        } else if ($sMethod == 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }

        curl_setopt($ch, CURLOPT_URL, $sUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if ($aHeader) { curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader); }
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ($ch);
        try {
            $decoded = json_decode($response, true);
            if ($decoded !== null)
                $response = $decoded;
        } catch (\Exception $e) {
            throw new GluwaSDKException($e->getMessage());
        }
        return array('code' => $httpcode, 'response' => $response);
    }

    public function getPaymentQRCode(array $htArg = []) {
        if ($this->FunctionExists['ok'] === false) {
            return $this->FunctionExists['error'];
        }

        $Signature = $this->getTimestampSignature();

        $Args = [
            'aHeader' => [
                'Content-Type: application/json',
                'Authorization: Basic '.$this->getAuthorization(),
            ],
            'sUrl' => $this->host . '/v1/QRCode',
            'sMethod' => 'POST',
            'sParamType' => 'JSON',
            'aParam' => [
                'Signature' => $Signature,
                'Currency' => $htArg['Currency'],
                'Target' => $this->MasterEthereumAddress,
                'Amount' => $htArg['Amount'],
                'MerchantOrderID' => $htArg['MerchantOrderID'],
                'Note' => $htArg['Note'],
                'Expiry' => $htArg['Expiry'],
            ]
        ];

        $Result = $this->curl($Args);
        
        if ($Result['code'] >= 200 && $Result['code'] <= 300) {
            return $Result;
        } else {
            throw new GluwaSDKException($Result['response'], $Result['code']);
        }
    }

    public function getAddresses(array $htArg = []) {
        if ($this->FunctionExists['ok'] === false) {
            return $this->FunctionExists['error'];
        }

        $Args = [
            'aHeader' => [
                'Accept: application/json',
                'Content-Type: application/json;charset=UTF-8',
            ],
            'sUrl' => $this->host . '/v1/' . $htArg['Currency'] . '/Addresses/' . $this->MasterEthereumAddress,
            'sMethod' => 'GET',
            'sParamType' => 'JSON',
            'aParam' => []
        ];

        $Result = $this->curl($Args);

        if ($Result['code'] >= 200 && $Result['code'] <= 300) {
            return $Result;
        } else {
            throw new GluwaSDKException($Result['response'], $Result['code']);
        }
    }

    private function getCryptoRandom($length = 1) {
        $returnStr = '';
        $range = 10;
        $bits = ceil(log(($range), 2));
        $bytes = ceil($bits / 8.0);
        $bits_max = 1 << $bits;

        for ($i = 0; $i < $length; $i++) {
            do {
                $num = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes, $cstrong))) % $bits_max;
                if ($num >= $range || ($i === 0 && $num === 0)) {
                    continue;
                }
                break;
            } while (true);

            $returnStr = $returnStr . $num;
        }

        return $returnStr;
    }

    public function postTransaction(array $htArg = []) {
        if ($this->FunctionExists['ok'] === false) {
            return $this->FunctionExists['error'];
        }

        $Amount = strval($htArg['Amount']);

        $Args = [
            'aHeader' => [
                'Accept: application/json',
                'Content-Type: application/json;charset=UTF-8',
            ],
            'sUrl' => $this->host . '/v1/' . $htArg['Currency'] . '/Fee',
            'sMethod' => 'GET',
            'sParamType' => 'Array',
            'aParam' => [
                'amount' => $htArg['Amount'],
            ]
        ];

        $Result = $this->curl($Args);
        if ($Result['code'] >= 200 && $Result['code'] <= 300) {
            $Fee = $Result['response']['MinimumFee'];

            $Nonce = $this->getCryptoRandom(75);

            $ConvertedAmount = strval(Ethereum::toWei($Amount, "ether"));
            $ConvertedFee = strval(Ethereum::toWei($Fee, "ether"));

            if ($htArg['Currency'] === 'sUSDCG') {
                $ConvertedAmount = substr($ConvertedAmount, 0, strlen($ConvertedAmount) - 12);
                $ConvertedFee = substr($ConvertedFee, 0, strlen($ConvertedFee) - 12);
            }
    
            $Hash = Ethereum::hash([
                    ['t' => "address", 'v' => $this->getContractAddress($htArg['Currency'], $this->__DEV__)],
                    ['t' => "address", 'v' => $this->MasterEthereumAddress],
                    ['t' => "address", 'v' => $htArg['Target']],
                    ['t' => "uint256", 'v' => $ConvertedAmount],
                    ['t' => "uint256", 'v' => $ConvertedFee],
                    ['t' => "uint256", 'v' => $Nonce],
                ]
            );
    
            $Signature = Ethereum::sign($Hash, $this->MasterEthereumPrivateKey);
    
            $Args = [
                'aHeader' => [
                    'Accept: application/json',
                    'Content-Type: application/json;charset=UTF-8',
                ],
                'sUrl' => $this->host . '/v1/Transactions',
                'sMethod' => 'POST',
                'sParamType' => 'JSON',
                'aParam' => [
                    'Signature' => $Signature,
                    'Source' => $this->MasterEthereumAddress,
                    'Currency' => $htArg['Currency'],
                    'Target' => $htArg['Target'],
                    'Amount' => $Amount,
                    'Fee' => $Fee,
                    'Nonce' => $Nonce,
                ]
            ];
            $Result = $this->curl($Args);
            
            if ($Result['code'] >= 200 && $Result['code'] <= 300) {
                return $Result;
            } else {
                throw new GluwaSDKException($Result['response'], $Result['code']);
            }
        } else {
            throw new GluwaSDKException($Result['response'], $Result['code']);
        }
    }

    public function getListTransactionHistory(array $htArg = []) {
        if ($this->FunctionExists['ok'] === false) {
            return $this->FunctionExists['error'];
        }

        $Signature = $this->getTimestampSignature();
        
        $Args = [
            'aHeader' => [
                'Content-Type: application/json',
                'X-REQUEST-SIGNATURE: '.$Signature,
            ],
            'sUrl' => $this->host . '/v1/' . $htArg['Currency'] . '/Addresses/' . $this->MasterEthereumAddress . '/Transactions',
            'sMethod' => 'GET',
            'sParamType' => 'Array',
            'aParam' => [
                'Limit' => $htArg['Limit'] ? $htArg['Limit'] : 100,
                'Status' => $htArg['Status'] ? $htArg['Status'] : 'Confirmed',
                'Offset' => $htArg['Offset'] ? $htArg['Offset'] : 0,
            ]
        ];

        $Result = $this->curl($Args);

        if ($Result['code'] >= 200 && $Result['code'] <= 300) {
            return $Result;
        } else {
            throw new GluwaSDKException($Result['response'], $Result['code']);
        }
    }

    public function getListTransactionDetail(array $htArg = []) {
        if ($this->FunctionExists['ok'] === false) {
            return $this->FunctionExists['error'];
        }

        $Signature = $this->getTimestampSignature();
        
        $Args = [
            'aHeader' => [
                'Content-Type: application/json',
                'X-REQUEST-SIGNATURE: '.$Signature,
            ],
            'sUrl' => $this->host . '/v1/' . $htArg['Currency'] . '/Transactions/' . $htArg['TxnHash'],
            'sMethod' => 'GET',
            'sParamType' => 'JSON',
            'aParam' => []
        ];

        $Result = $this->curl($Args);

        if ($Result['code'] >= 200 && $Result['code'] <= 300) {
            return $Result;
        } else {
            throw new GluwaSDKException($Result['response'], $Result['code']);
        }
    }

    public function validateWebhook(array $htArg = []) {
        if ($this->FunctionExists['ok'] === false) {
            return $this->FunctionExists['error'];
        }
        $Payload = $htArg['Payload'];
        $Signature = $htArg['Signature'];

        $PayloadHash = hash_hmac('sha256', $Payload, $this->WebhookSecret, true);
        $PayloadHashBase64Encode = base64_encode($PayloadHash);

        return hash_equals($PayloadHashBase64Encode, $Signature);
    }

    private function FunctionExists() {
        foreach ($this->RequiredFunctions as $function)
        {
            if (!function_exists($function))
            {
                return array('ok' => false, 'error' => array("code" => 408, "error" => 'Function '.$function.' is unavailable. Please make sure php_gmp, php_bcmath extensions is available'));
            }
        }
        return array('ok' => true);
    }
}