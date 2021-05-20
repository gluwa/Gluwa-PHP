<?php

namespace Gluwa\Ecc\Serializer\Point;

use Gluwa\Ecc\Math\GmpMathInterface;
use Gluwa\Ecc\Primitives\PointInterface;
use Gluwa\Ecc\Primitives\CurveFpInterface;
use Gluwa\Ecc\Serializer\Util\CurveOidMapper;
use Gluwa\Ecc\Util\BinaryString;

class UncompressedPointSerializer implements PointSerializerInterface
{
    /**
     * @var GmpMathInterface
     */
    private $adapter;

    /**
     * @param GmpMathInterface     $adapter
     */
    public function __construct(GmpMathInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param PointInterface $point
     * @return string
     */
    public function serialize(PointInterface $point)
    {
        $length = CurveOidMapper::getByteSize($point->getCurve()) * 2;

        $hexString = '04';
        $hexString .= str_pad(gmp_strval($point->getX(), 16), $length, '0', STR_PAD_LEFT);
        $hexString .= str_pad(gmp_strval($point->getY(), 16), $length, '0', STR_PAD_LEFT);

        return $hexString;
    }

    /**
     * @param CurveFpInterface $curve
     * @param string           $data
     * @return PointInterface
     */
    public function unserialize(CurveFpInterface $curve, $data)
    {
        if (BinaryString::substring($data, 0, 2) != '04') {
            throw new \InvalidArgumentException('Invalid data: only uncompressed keys are supported.');
        }

        $data = BinaryString::substring($data, 2);
        $dataLength = BinaryString::length($data);

        $x = gmp_init(BinaryString::substring($data, 0, $dataLength / 2), 16);
        $y = gmp_init(BinaryString::substring($data, $dataLength / 2), 16);

        return $curve->getPoint($x, $y);
    }
}
