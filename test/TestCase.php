<?php

namespace Gluwa;

use PHPUnit\Framework\TestCase as BaseTestCase;

use Gluwa\Secp256k1\Serializer\HexSignatureSerializer;

class TestCase extends BaseTestCase
{
    protected $sigSerializer;

    protected $signed = '';

    protected $testPrivateKey = getenv("GLUWA_ETHEREUM_PRIVATE_KEY");

    protected $testPublicKey = getenv("GLUWA_ETHEREUM_ADDRESS");

    protected function setUp(): void {
        $this->sigSerializer = new HexSignatureSerializer();
        parent::setUp();
    }

    protected function tearDown(): void {}
}
