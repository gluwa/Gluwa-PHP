<?php

/*
* based on https://github.com/kornrunner/php-secp256k1
* support for php 5.6.x
*/

namespace Gluwa\Secp256k1;

use InvalidArgumentException;

use Gluwa\Secp256k1\Serializer\HexPrivateKeySerializer;
use Gluwa\Secp256k1\Signature\Signer;

use Gluwa\Ecc\Curves\CurveFactory;
use Gluwa\Ecc\Curves\SecgCurve;
use Gluwa\Ecc\EccFactory;
use Gluwa\Ecc\Random\RandomGeneratorFactory;

class Secp256k1
{
    protected $adapter;

    protected $generator;

    protected $curve;

    protected $deserializer;

    protected $algorithm;

    public function __construct($hashAlgorithm='sha256') {
        $this->adapter = EccFactory::getAdapter();
        $this->generator = CurveFactory::getGeneratorByName(SecgCurve::NAME_SECP_256K1);
        $this->curve = $this->generator->getCurve();
        $this->deserializer = new HexPrivateKeySerializer($this->generator);
        $this->algorithm = $hashAlgorithm;
    }

    public function sign($hash, $privateKey, $options=[]) {
        $key = $this->deserializer->parse($privateKey);
        $hex_hash = gmp_init($hash, 16);

        if (!isset($options['n'])) {
            $options['n'] = $this->generator->getOrder();
        }
        if (!isset($options['canonical'])) {
            $options['canonical'] = true;
        }
        $signer = new Signer($this->adapter, $options);

        $random = RandomGeneratorFactory::getHmacRandomGenerator($key, $hex_hash, $this->algorithm);
        $randomK = $random->generate($options['n']);
        return $signer->sign($key, $hex_hash, $randomK);
    }

    public function verify($hash, $signature, $publicKey)
    {
        $gmpKey = $this->decodePoint($publicKey);
        $key = $this->generator->getPublickeyFrom($gmpKey->getX(), $gmpKey->getY());
        $hex_hash = gmp_init($hash, 16);
        $signer = new Signer($this->adapter);

        return $signer->verify($key, $signature, $hex_hash);
    }

    protected function decodePoint($publicKey)
    {
        $order = $this->generator->getOrder();
        $orderString = gmp_strval($order, 16);
        $length = mb_strlen($orderString);
        $keyLength = mb_strlen($publicKey);
        $num = hexdec(mb_substr($publicKey, 0, 2));

        if (
            ($num === 4 || $num === 6 || $num === 7) &&
            ($length * 2 + 2) === $keyLength
            ) {
            $x = gmp_init(mb_substr($publicKey, 2, $length), 16);
            $y = gmp_init(mb_substr($publicKey, ($length + 2), $length), 16);

            if ($this->generator->isValid($x, $y) !== true) {
                throw new InvalidArgumentException('Invalid public key point x and y.');
            }

            return $this->curve->getPoint($x, $y, $order);
        } elseif (
            ($num === 2 || $num === 3) &&
            ($length + 2) === $keyLength
        ) {
            $x = gmp_init(mb_substr($publicKey, 2, $length), 16);
            $y = $this->curve->recoverYfromX($num === 3, $x);

            return $this->curve->getPoint($x, $y, $order);
        }
        throw new InvalidArgumentException('Invalid public key point format.');
    }
}
