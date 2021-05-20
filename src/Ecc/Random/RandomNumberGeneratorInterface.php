<?php

namespace Gluwa\Ecc\Random;

interface RandomNumberGeneratorInterface
{
    /**
     * Generate a random number between 0 and the specified upper boundary.
     *
     * @param $max Upper boundary, inclusive
     */
    public function generate($max);
}
