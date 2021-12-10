<?php

namespace Gluwa;

use PHPUnit\Framework\TestCase as BaseTestCase;

use Gluwa\Secp256k1\Serializer\HexSignatureSerializer;

class TestCase extends BaseTestCase
{
    protected $sigSerializer;

    protected $signed = '';

    protected $testPrivateKey = '';

    protected $testPublicKey = '';

    protected function setUp(): void {
        $this->sigSerializer = new HexSignatureSerializer();
        parent::setUp();
    }

    protected function tearDown(): void {}
}
