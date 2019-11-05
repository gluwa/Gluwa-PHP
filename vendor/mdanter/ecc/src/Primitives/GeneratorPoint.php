<?php

namespace Mdanter\Ecc\Primitives;

use Mdanter\Ecc\Math\GmpMathInterface;
use Mdanter\Ecc\Crypto\Key\PrivateKey;
use Mdanter\Ecc\Crypto\Key\PublicKey;
use Mdanter\Ecc\Random\RandomGeneratorFactory;
use Mdanter\Ecc\Random\RandomNumberGeneratorInterface;

/**
 * Curve point from which public and private keys can be derived.
 */
class GeneratorPoint extends Point
{
    /**
     * @var \Mdanter\Ecc\Random\DebugDecorator|RandomNumberGeneratorInterface|null
     */
    private $generator;

    /**
     * @param GmpMathInterface               $adapter
     * @param CurveFpInterface               $curve
     * @param                           $x
     * @param                           $y
     * @param                           $order
     * @param RandomNumberGeneratorInterface $generator
     */
    public function __construct(
        GmpMathInterface $adapter,
        CurveFpInterface $curve,
        $x,
        $y,
        $order,
        RandomNumberGeneratorInterface $generator = null
    ) {
        $this->generator = $generator ?: RandomGeneratorFactory::getRandomGenerator();
        parent::__construct($adapter, $curve, $x, $y, $order);
    }

    /**
     * Verifies validity of given coordinates against the current point and its point.
     *
     * @todo   Check if really necessary here (only used for testing in lib)
     * @param  $x
     * @param  $y
     * @return boolean
     */
    public function isValid($x, $y)
    {
       
        $math = $this->getAdapter();

        $n = $this->getOrder();
        $zero = gmp_init(0, 10);
        $curve = $this->getCurve();

        if ($math->cmp($x, $zero) < 0 || $math->cmp($n, $x) <= 0 || $math->cmp($y, $zero) < 0 || $math->cmp($n, $y) <= 0) {
            return false;
        }

        if (! $curve->contains($x, $y)) {
            return false;
        }

        $point = $curve->getPoint($x, $y)->mul($n);

        if (! $point->isInfinity()) {
            return false;
        }

        return true;
    }

    /**
     * @return PrivateKey
     */
    public function createPrivateKey()
    {
        $secret = $this->generator->generate($this->getOrder());

        return new PrivateKey($this->getAdapter(), $this, $secret);
    }

    /**
     * @param $x
     * @param $y
     * @param $order
     * @return PublicKey
     */
    public function getPublicKeyFrom($x, $y, $order = null)
    {
        $pubPoint = $this->getCurve()->getPoint($x, $y, $order);
        return new PublicKey($this->getAdapter(), $this, $pubPoint);
    }

    /**
     * @param $secretMultiplier
     * @return PrivateKey
     */
    public function getPrivateKeyFrom($secretMultiplier)
    {
        return new PrivateKey($this->getAdapter(), $this, $secretMultiplier);
    }
}
