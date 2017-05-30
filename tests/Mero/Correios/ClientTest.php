<?php

namespace Mero\Correios;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class ClientTest extends PHPUnitTestCase
{
    /**
     * @var Client Correios Client
     */
    protected $client;

    /**
     * @inheritDoc
     */
    public function setUp()
    {
        if (!extension_loaded('soap')) {
            $this->markTestSkipped('This test requires soap to run');
        }
        $this->client = new Client();
    }

    /**
     * @expectedException \Mero\Correios\Exception\InvalidZipCodeException
     */
    public function testInvalidZipCodeException()
    {
        $this->client->findAddressByZipCode('200430-900');
    }

    /**
     * @expectedException \Mero\Correios\Exception\AddressNotFoundException
     */
    public function testAddressNotFoundException()
    {
        $this->client->findAddressByZipCode('20000-000');
    }

    public function testFindAddressByZipCode()
    {
        $address = $this->client->findAddressByZipCode('20081-262');
        $this->assertEquals('Rua Sacadura Cabral', $address->getAddress());
        $this->assertEquals('Saúde', $address->getNeighborhood());
        $this->assertEquals('Rio de Janeiro', $address->getCity());
        $this->assertEquals('RJ', $address->getState());
        $this->assertEquals('20081262', $address->getZipCode());
    }
}