<?php

namespace Gluwa\Ecc\Serializer\PublicKey\Der;

use FG\ASN1\Universal\Sequence;
use FG\ASN1\Universal\ObjectIdentifier;
use FG\ASN1\Universal\BitString;
use Gluwa\Ecc\Math\GmpMathInterface;
use Gluwa\Ecc\Primitives\PointInterface;
use Gluwa\Ecc\Crypto\Key\PublicKeyInterface;
use Gluwa\Ecc\Curves\NamedCurveFp;
use Gluwa\Ecc\Serializer\Util\CurveOidMapper;
use Gluwa\Ecc\Serializer\PublicKey\DerPublicKeySerializer;
use Gluwa\Ecc\Serializer\Point\PointSerializerInterface;
use Gluwa\Ecc\Serializer\Point\UncompressedPointSerializer;

class Formatter
{

    /**
     * @var GmpMathInterface
     */
    private $adapter;

    /**
     * @var UncompressedPointSerializer
     */
    private $pointSerializer;

    /**
     * Formatter constructor.
     * @param GmpMathInterface $adapter
     * @param PointSerializerInterface|null $pointSerializer
     */
    public function __construct(GmpMathInterface $adapter, PointSerializerInterface $pointSerializer = null)
    {
        $this->adapter = $adapter;
        $this->pointSerializer = $pointSerializer ?: new UncompressedPointSerializer($adapter);
    }

    /**
     * @param PublicKeyInterface $key
     * @return string
     */
    public function format(PublicKeyInterface $key)
    {
        if (! ($key->getCurve() instanceof NamedCurveFp)) {
            throw new \RuntimeException('Not implemented for unnamed curves');
        }

        $sequence = new Sequence(
            new Sequence(
                new ObjectIdentifier(DerPublicKeySerializer::X509_ECDSA_OID),
                CurveOidMapper::getCurveOid($key->getCurve())
            ),
            new BitString($this->encodePoint($key->getPoint()))
        );

        return $sequence->getBinary();
    }

    /**
     * @param PointInterface $point
     * @return string
     */
    public function encodePoint(PointInterface $point)
    {
        return $this->pointSerializer->serialize($point);
    }
}
