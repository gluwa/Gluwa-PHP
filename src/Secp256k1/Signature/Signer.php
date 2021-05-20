<?php

namespace Gluwa\Secp256k1\Signature;

use GMP;
use Gluwa\Ecc\Crypto\Signature\Signer as EccSigner;

class Signer
{
    protected $adapter;

    protected $signer;

    protected $options;

    public function __construct($adapter, $options=[]) {
        $this->adapter = $adapter;
        $this->signer = new EccSigner($adapter);
        $this->options = $options;
    }

    public function sign($key, $truncatedHash, $randomK) {
        $signature = $this->signer->sign($key, $truncatedHash, $randomK);
        $options = $this->options;
        $math = $this->adapter;

        // get r and s
        $r = $signature->getR();
        $s = $signature->getS();

        // get recovery param
        $zero = gmp_init(0, 10);
        $one  = gmp_init(1, 10);

        $generator = $key->getPoint();
        $kp  = $generator->mul($randomK);
        $kpY = $kp->getY();
        $kpX = $kp->getX();
        $recoveryParam = (($math->equals($math->bitwiseAnd($kpY, $one), $zero)) ? 0 : 1) |
                         (($math->cmp($kpX, $r) !== 0)  ? 2 : 0);

        if (
            (isset($options['canonical']) && $options['canonical'] === true) &&
            (isset($options['n']) && $options['n'] instanceof GMP)) {
            $nh = $math->rightShift($options['n'], 1);

            if ($math->cmp($s, $nh) > 0) {
                $s = gmp_sub($options['n'], $s);
                $recoveryParam ^= 1;
            }
        }

        return new Signature($r, $s, $recoveryParam);
    }

    public function verify($key, $signature, $hash)
    {
        return $this->signer->verify($key, $signature, $hash);
    }
}
