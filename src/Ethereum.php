<?php

namespace Gluwa;

use Gluwa\Keccak\Keccak;
use Gluwa\Secp256k1\Secp256k1;

use Bezhanov\Ethereum\Converter;

class Ethereum
{

    public static function toWei($amount, $unit)
    {
        $converter = new Converter();
        return $converter->toWei($amount, $unit);
    }

    public static function hash($arr)
    {
        $message = '';

        foreach ($arr as $data) {
            $type = $data['t'];
            $value = $data['v'];

            if ($type == 'address') {
                $value = substr($value, 2);
                $value = str_pad($value, 40, 0, STR_PAD_LEFT);
                $value = hex2bin($value);

            } elseif ($type == 'uint256') {
                $value = self::dec2hex($value);
                $value = str_pad($value, 256 / 8 * 2, 0, STR_PAD_LEFT);
                $value = hex2bin($value);
            }

            $message .= $value;

        }

        return '0x' . Keccak::hash($message, 256);
    }

    /**
     * Internal PHP 5.6 function doesn't support large numbers
     * @param $number
     * @return string
     */
    protected static function dec2hex($number)
    {
        $hexvalues = array('0', '1', '2', '3', '4', '5', '6', '7',
            '8', '9', 'A', 'B', 'C', 'D', 'E', 'F');
        $hexval = '';
        while ($number != '0') {
            $hexval = $hexvalues[bcmod($number, '16')] . $hexval;
            $number = bcdiv($number, '16', 0);
        }
        return $hexval;
    }

    public static function sign($data, $privateKey)
    {
        // If it's a hex value
        if (strpos($data, '0x') === 0 && ctype_xdigit(substr($data, 2))) {
            $data = substr($data, 2);
            $size = count(self::hex2ByteArray($data));
            $data = hex2bin($data);
        } else {
            $size = strlen($data);
        }

        $data = "\x19Ethereum Signed Message:\n" . $size . $data;
        $data = Keccak::hash($data, 256);

        $secp256k1 = new Secp256k1();
        $signature = $secp256k1->sign($data, $privateKey);

        return '0x' . $signature->toHex() . dechex(27 + $signature->getRecoveryParam());
    }

    protected static function hex2ByteArray($hexString)
    {
        $string = hex2bin($hexString);
        return unpack('C*', $string);
    }
}