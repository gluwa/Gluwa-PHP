<?php

namespace Mdanter\Ecc\Math;

class ModularArithmetic
{
    /**
     * @var GmpMathInterface
     */
    private $adapter;

    /**
     * @var \GMP
     */
    private $modulus;

    /**
     * @param GmpMathInterface $adapter
     * @param $modulus
     */
    public function __construct(GmpMathInterface $adapter, $modulus)
    {
        $this->adapter = $adapter;
        $this->modulus = $modulus;
    }

    /**
     * @param $augend
     * @param $addend
     * @return \GMP
     */
    public function add($augend, $addend)
    {
        return $this->adapter->mod($this->adapter->add($augend, $addend), $this->modulus);
    }

    /**
     * @param $minuend
     * @param $subtrahend
     * @return \GMP
     */
    public function sub($minuend, $subtrahend)
    {
        return $this->adapter->mod($this->adapter->sub($minuend, $subtrahend), $this->modulus);
    }

    /**
     * @param $multiplier
     * @param $muliplicand
     * @return \GMP
     */
    public function mul($multiplier, $muliplicand)
    {
        return $this->adapter->mod($this->adapter->mul($multiplier, $muliplicand), $this->modulus);
    }

    /**
     * @param $dividend
     * @param $divisor
     * @return \GMP
     */
    public function div($dividend, $divisor)
    {
        return $this->mul($dividend, $this->adapter->inverseMod($divisor, $this->modulus));
    }

    /**
     * @param $base
     * @param $exponent
     * @return \GMP
     */
    public function pow($base, $exponent)
    {
        return $this->adapter->powmod($base, $exponent, $this->modulus);
    }
}
