<?php

namespace Gluwa\Ecc\Random;

use Gluwa\Ecc\Math\GmpMathInterface;
use Gluwa\Ecc\Util\NumberSize;

class RandomNumberGenerator implements RandomNumberGeneratorInterface
{
    /**
     * @var GmpMathInterface
     */
    private $adapter;

    /**
     * RandomNumberGenerator constructor.
     * @param GmpMathInterface $adapter
     */
    public function __construct(GmpMathInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param $max
     * @return 
     */
    public function generate($max)
    {
        $numBits = NumberSize::bnNumBits($this->adapter, $max);
        $numBytes = ceil($numBits / 8);

        // Generate an integer of size >= $numBits
        $bytes = random_bytes($numBytes);
        $value = $this->adapter->stringToInt($bytes);

        $mask = gmp_sub(gmp_pow(2, $numBits), 1);
        $integer = gmp_and($value, $mask);

        return $integer;
    }
}
