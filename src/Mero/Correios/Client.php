<?php

namespace Mero\Correios;

use Mero\Correios\Exception\AddressNotFoundException;
use Mero\Correios\Exception\InvalidZipCodeException;
use Mero\Correios\Model\Address;

class Client
{
    /**
     * Correios WSDL URI
     */
    const CORREIOS_WSDL_URI = "https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl";

    /**
     * @var \SoapClient Correios WSDL Client
     */
    protected $wsdl;

    public function __construct()
    {
        $this->wsdl = new \SoapClient(self::CORREIOS_WSDL_URI);
    }

    /**
     * Find address informations using the zip code.
     *
     * @param string $zipCode Zip code
     *
     * @return Address Address informations
     *
     * @throws AddressNotFoundException When address not found
     * @throws InvalidZipCodeException When the zip code is invalid
     */
    public function findAddressByZipCode($zipCode)
    {
        if (strlen($zipCode) != 9) {
            throw new InvalidZipCodeException('The brazilian zip code should have exacly 9 characters.');
        }
        try {
            $address = $this->wsdl->__soapCall('consultaCEP', [
                'consultaCEP' => [
                    'cep' => $zipCode
                ]
            ]);

            return new Address(
                $address->return->end,
                $address->return->bairro,
                $address->return->cidade,
                $address->return->uf,
                $address->return->cep
            );
        } catch (\SoapFault $e) {
            throw new AddressNotFoundException($e->getMessage());
        }
    }
}
