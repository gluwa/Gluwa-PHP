<?php

namespace Mdanter\Ecc\Primitives;

class CurveParameters
{
    /**
     * Elliptic curve over the field of integers modulo a prime.
     *
     * @var 
     */
    protected $a;

    /**
     *
     * @var 
     */
    protected $b;

    /**
     *
     * @var 
     */
    protected $prime;

    /**
     * Binary length of keys associated with these curve parameters
     *
     * @var int
     */
    protected $size;

    /**
     * @param int $size
     * @param $prime
     * @param $a
     * @param $b
     */
    public function __construct($size, $prime, $a, $b)
    {
        $this->size = $size;
        $this->prime = $prime;
        $this->a = $a;
        $this->b = $b;
    }

    /**
     * @return 
     */
    public function getA()
    {
        return $this->a;
    }

    /**
     * @return 
     */
    public function getB()
    {
        return $this->b;
    }

    /**
     * @return 
     */
    public function getPrime()
    {
        return $this->prime;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }
}
