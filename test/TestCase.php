<?php

namespace Gluwa;

use PHPUnit\Framework\TestCase as BaseTestCase;

use Gluwa\Secp256k1\Serializer\HexSignatureSerializer;

class TestCase extends BaseTestCase
{
    protected $sigSerializer;
    protected $signed = '';
    protected $testPrivateKey;
    protected $testPublicKey;

    protected function setUp(): void {
        $this->sigSerializer = new HexSignatureSerializer();
        $this->testPrivateKey = getenv("GLUWA_ETHEREUM_PRIVATE_KEY");
        $this->testPublicKey = getenv("GLUWA_ETHEREUM_ADDRESS");
        parent::setUp();
    }

    protected function tearDown(): void {}
}
