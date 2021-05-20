<?php

namespace Gluwa\Secp256k1\Serializer;

use Gluwa\Ecc\Serializer\PrivateKey\PrivateKeySerializerInterface;
use Gluwa\Ecc\Crypto\Key\PrivateKeyInterface;

class HexPrivateKeySerializer implements PrivateKeySerializerInterface
{
    protected $generator;

    public function __construct($generator) {
        $this->generator = $generator;
    }

    public function serialize(PrivateKeyInterface $key) {
        return gmp_strval($key->getSecret(), 16);
    }

    public function parse($formattedKey) {
        $key = gmp_init($formattedKey, 16);

        return $this->generator->getPrivateKeyFrom($key);
    }
}
