<?php

namespace Mero\Correios;

use Mero\Correios\Exception\InvalidZipCodeException;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class ClientTest extends PHPUnitTestCase
{
    /**
     * @inheritDoc
     */
    public function setUp()
    {
        if (!extension_loaded("soap")) {
            $this->markTestSkipped("This test requires soap to run");
        }
    }

    /**
     * Return the Soap Client mocked.
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createSoapClientMock()
    {
        $soapClientMocked = $this
            ->getMockBuilder("\SoapClient")
            ->disableOriginalConstructor()
            ->getMock();

        $soapClientMocked
            ->method("__soapCall")
            ->willReturn((object) [
                "return" => (object) [
                    "end" => "Avenida das Américas",
                    "bairro" => "Barra da Tijuca",
                    "cidade" => "Rio de Janeiro",
                    "uf" => "RJ",
                    "cep" => "22640102",
                ],
            ]);

        return $soapClientMocked;
    }

    /**
     * Return the Correios Client mocked for not accessing webservice.
     *
     * @param \PHPUnit_Framework_MockObject_MockObject $soapClientMocked
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createClientMock($soapClientMocked)
    {
        $client = $this
            ->getMockBuilder(Client::className())
            ->disableOriginalConstructor()
            ->setMethods(["createSoapClient"])
            ->getMock();

        $client
            ->method("createSoapClient")
            ->willReturn($soapClientMocked);

        $reflectedClass = new \ReflectionClass(Client::className());
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($client, []);

        return $client;
    }

    /**
     * @expectedException \Mero\Correios\Exception\InvalidZipCodeException
     */
    public function testInvalidZipCodeException()
    {
        $wsdlClient = $this->createSoapClientMock();
        $client = $this->createClientMock($wsdlClient);
        $client->findAddressByZipCode("200430900");
    }

    /**
     * @expectedException \Mero\Correios\Exception\AddressNotFoundException
     */
    public function testAddressNotFoundException()
    {
        $wsdlClient = $this
            ->getMockBuilder("\SoapClient")
            ->disableOriginalConstructor()
            ->getMock();

        $wsdlClient
            ->method("__soapCall")
            ->willThrowException(new \SoapFault(
                "soap:Server",
                "CEP NAO ENCONTRADO",
                "",
                (object) [
                    "SigepClienteException" => "CEP NAO ENCONTRADO"
                ]
            ));

        $client = $this->createClientMock($wsdlClient);
        $client->findAddressByZipCode("20000000");
    }

    public function testFindAddressByZipCode()
    {
        $wsdlClient = $this->createSoapClientMock();
        $client = $this->createClientMock($wsdlClient);
        $address = $client->findAddressByZipCode("22640102");
        $this->assertEquals("Avenida das Américas", $address->getAddress());
        $this->assertEquals("Barra da Tijuca", $address->getNeighborhood());
        $this->assertEquals("Rio de Janeiro", $address->getCity());
        $this->assertEquals("RJ", $address->getState());
        $this->assertEquals("22640102", $address->getZipCode());
    }
}
