<?php

namespace Gluwa\Ecc\Random;

class DebugDecorator implements RandomNumberGeneratorInterface
{
    /**
     * @var RandomNumberGeneratorInterface
     */
    private $generator;

    /**
     * @var string
     */
    private $generatorName;

    /**
     * @param RandomNumberGeneratorInterface $generator
     * @param string $name
     */
    public function __construct(RandomNumberGeneratorInterface $generator, $name)
    {
        $this->generator = $generator;
        $this->generatorName = $name;
    }

    /**
     * @param $max
     * @return 
     */
    public function generate($max)
    {
        echo $this->generatorName.'::rand() = ';

        $result = $this->generator->generate($max);

        echo gmp_strval($result, 10).PHP_EOL;

        return $result;
    }
}
