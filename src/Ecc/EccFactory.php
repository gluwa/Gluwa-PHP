<?php

namespace Gluwa\Ecc;

use Gluwa\Ecc\Crypto\Signature\Signer;
use Gluwa\Ecc\Curves\NistCurve;
use Gluwa\Ecc\Curves\SecgCurve;
use Gluwa\Ecc\Math\GmpMathInterface;
use Gluwa\Ecc\Math\MathAdapterFactory;
use Gluwa\Ecc\Primitives\CurveFp;
use Gluwa\Ecc\Primitives\CurveParameters;

/**
 * Static factory class providing factory methods to work with NIST and SECG recommended curves.
 */
class EccFactory
{
    /**
     * Selects and creates the most appropriate adapter for the running environment.
     *
     * @param $debug [optional] Set to true to get a trace of all mathematical operations
     *
     * @throws \RuntimeException
     * @return GmpMathInterface
     */
    public static function getAdapter($debug = false)
    {
        return MathAdapterFactory::getAdapter($debug);
    }

    /**
     * Returns a factory to create NIST Recommended curves and generators.
     *
     * @param  GmpMathInterface $adapter [optional] Defaults to the return value of EccFactory::getAdapter().
     * @return NistCurve
     */
    public static function getNistCurves(GmpMathInterface $adapter = null)
    {
        return new NistCurve($adapter ?: self::getAdapter());
    }

    /**
     * Returns a factory to return SECG Recommended curves and generators.
     *
     * @param  GmpMathInterface $adapter [optional] Defaults to the return value of EccFactory::getAdapter().
     * @return SecgCurve
     */
    public static function getSecgCurves(GmpMathInterface $adapter = null)
    {
        return new SecgCurve($adapter ?: self::getAdapter());
    }

    /**
     * Creates a new curve from arbitrary parameters.
     *
     * @param              $prime
     * @param              $a
     * @param              $b
     * @param  GmpMathInterface $adapter [optional] Defaults to the return value of EccFactory::getAdapter().
     * @return \Gluwa\Ecc\Primitives\CurveFpInterface
     */
    public static function createCurve($bitSize, $prime, $a, $b, GmpMathInterface $adapter = null)
    {
        return new CurveFp(new CurveParameters($bitSize, $prime, $a, $b), $adapter ?: self::getAdapter());
    }

    /**
     *
     * @param  GmpMathInterface $adapter [optional] Defaults to the return value of EccFactory::getAdapteR()
     * @return \Gluwa\Ecc\Crypto\Signature\Signer
     */
    public static function getSigner(GmpMathInterface $adapter = null)
    {
        return new Signer($adapter ?: self::getAdapter());
    }
}
