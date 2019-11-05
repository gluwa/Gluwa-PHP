<?php

namespace Mdanter\Ecc\Math;

interface GmpMathInterface
{
    /**
     * Compares two numbers
     *
     * @param  $first
     * @param  $other
     * @return int        less than 0 if first is less than second, 0 if equal, greater than 0 if greater than.
     */
    public function cmp($first, $other);

    /**
     * @param $first
     * @param $other
     * @return bool
     */
    public function equals($first, $other);
    
    /**
     * Returns the remainder of a division
     *
     * @param  $number
     * @param  $modulus
     * @return 
     */
    public function mod($number, $modulus);

    /**
     * Adds two numbers
     *
     * @param  $augend
     * @param  $addend
     * @return 
     */
    public function add($augend, $addend);

    /**
     * Substract one number from another
     *
     * @param  $minuend
     * @param  $subtrahend
     * @return 
     */
    public function sub($minuend, $subtrahend);

    /**
     * Multiplies a number by another.
     *
     * @param  $multiplier
     * @param  $multiplicand
     * @return 
     */
    public function mul($multiplier, $multiplicand);

    /**
     * Divides a number by another.
     *
     * @param  $dividend
     * @param  $divisor
     * @return 
     */
    public function div($dividend, $divisor);

    /**
     * Raises a number to a power.
     *
     * @param  $base     The number to raise.
     * @param  int $exponent The power to raise the number to.
     * @return 
     */
    public function pow($base, $exponent);

    /**
     * Performs a logical AND between two values.
     *
     * @param  $first
     * @param  $other
     * @return 
     */
    public function bitwiseAnd($first, $other);

    /**
     * Performs a logical XOR between two values.
     *
     * @param  $first
     * @param  $other
     * @return 
     */
    public function bitwiseXor($first, $other);

    /**
     * Shifts bits to the right
     * @param        $number    Number to shift
     * @param int  $positions Number of positions to shift
     * @return 
     */
    public function rightShift($number, $positions);

    /**
     * Shifts bits to the left
     * @param       $number    Number to shift
     * @param int $positions Number of positions to shift
     * @return 
     */
    public function leftShift($number, $positions);

    /**
     * Returns the string representation of a returned value.
     *
     * @param $value
     * @return int|string
     */
    public function toString($value);

    /**
     * Converts an hexadecimal string to decimal.
     *
     * @param  string $hexString
     * @return int|string
     */
    public function hexDec($hexString);

    /**
     * Converts a decimal string to hexadecimal.
     *
     * @param  int|string $decString
     * @return int|string
     */
    public function decHex($decString);

    /**
     * Calculates the modular exponent of a number.
     *
     * @param $base
     * @param $exponent
     * @param $modulus
     */
    public function powmod($base, $exponent, $modulus);

    /**
     * Checks whether a number is a prime.
     *
     * @param  $n
     * @return boolean
     */
    public function isPrime($n);

    /**
     * Gets the next known prime that is greater than a given prime.
     *
     * @param  $currentPrime
     * @return 
     */
    public function nextPrime($currentPrime);

    /**
     * @param $a
     * @param $m
     * @return 
     */
    public function inverseMod($a, $m);

    /**
     * @param $a
     * @param $p
     * @return int
     */
    public function jacobi($a, $p);

    /**
     * @param  $x
     * @return string|null
     */
    public function intToString($x);

    /**
     *
     * @param  int|string $s
     * @return 
     */
    public function stringToInt($s);

    /**
     *
     * @param  $m
     * @return 
     */
    public function digestInteger($m);

    /**
     * @param  $a
     * @param  $m
     * @return 
     */
    public function gcd2($a, $m);

    /**
     * @param $value
     * @param $fromBase
     * @param $toBase
     * @return int|string
     */
    public function baseConvert($value, $fromBase, $toBase);

    /**
     * @return NumberTheory
     */
    public function getNumberTheory();

    /**
     * @param $modulus
     * @return ModularArithmetic
     */
    public function getModularArithmetic($modulus);
}
