<?php

namespace Gluwa\Secp256k1\Signature;

use Gluwa\Secp256k1\Serializer\HexSignatureSerializer;
use Gluwa\Secp256k1\Signature\SignatureInterface;

use Gluwa\Ecc\Crypto\Signature\Signature as EccSignature;

class Signature extends EccSignature implements SignatureInterface
{
    protected $serializer;

    protected $recoveryParam;

    public function __construct($r, $s, $recoveryParam) {
        parent::__construct($r, $s);

        $this->serializer = new HexSignatureSerializer;
        $this->recoveryParam = $recoveryParam;
    }

    public function toHex() {
        return $this->serializer->serialize($this);
    }

    public function getRecoveryParam() {
        return $this->recoveryParam;
    }
}