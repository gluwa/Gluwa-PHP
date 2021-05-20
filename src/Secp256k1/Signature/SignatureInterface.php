<?php

namespace Gluwa\Secp256k1\Signature;

use Gluwa\Ecc\Crypto\Signature\SignatureInterface as EccSignatureInterface;

interface SignatureInterface extends EccSignatureInterface {
    public function getRecoveryParam();
}
