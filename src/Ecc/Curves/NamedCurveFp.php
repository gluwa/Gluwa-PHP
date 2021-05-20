<?php

namespace Gluwa\Ecc\Curves;

use Gluwa\Ecc\Math\GmpMathInterface;
use Gluwa\Ecc\Primitives\CurveFp;
use Gluwa\Ecc\Primitives\CurveParameters;

class NamedCurveFp extends CurveFp
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param string           $name
     * @param CurveParameters  $parameters
     * @param GmpMathInterface $adapter
     */
    public function __construct($name, CurveParameters $parameters, GmpMathInterface $adapter)
    {
        $this->name = $name;

        parent::__construct($parameters, $adapter);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
