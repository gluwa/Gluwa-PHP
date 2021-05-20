<?php

namespace Gluwa\Ecc\Serializer\PrivateKey;

use Gluwa\Ecc\Crypto\Key\PrivateKeyInterface;

interface PrivateKeySerializerInterface
{
    /**
     *
     * @param  PrivateKeyInterface $key
     * @return string
     */
    public function serialize(PrivateKeyInterface $key);

    /**
     *
     * @param  string $formattedKey
     * @return PrivateKeyInterface
     */
    public function parse($formattedKey);
}
