<?php

namespace Mero\Correios;

use Mero\Correios\Exception\AddressNotFoundException;
use Mero\Correios\Exception\InvalidZipCodeException;
use Mero\Correios\Model\Address;

class Client
{
    /**
     * Return the Correios SOAP client.
     *
     * @return \SoapClient SOAP client
     */
    protected function createWsdlConnection()
    {
        return new \SoapClient(
            "https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl"
        );
    }

    /**
     * Find address informations using the zip code.
     *
     * @param string $zipCode Zip code without special characters
     *
     * @return Address Address informations
     *
     * @throws AddressNotFoundException When address not found
     * @throws InvalidZipCodeException When the zip code is invalid
     */
    public function findAddressByZipCode($zipCode)
    {
        $zipCode = preg_replace('/[^0-9]/', '', $zipCode);
        if (strlen($zipCode) != 8) {
            throw new InvalidZipCodeException('The brazilian zip code should have exacly 8 characters.');
        }

        try {
            $wsdlConnection = $this->createWsdlConnection();
            $address = $wsdlConnection->__soapCall('consultaCEP', [
                'consultaCEP' => [
                    'cep' => $zipCode
                ]
            ]);

            $address = new Address(
                $address->return->end,
                $address->return->bairro,
                $address->return->cidade,
                $address->return->uf,
                $address->return->cep
            );

            return $address;
        } catch (\SoapFault $e) {
            throw new AddressNotFoundException($e->getMessage());
        }
    }

    /**
     * Return the class name. Please, not use this method if you are using PHP 5.5 or above.
     *
     * @return string Class name
     *
     * @deprecated In PHP 5.5 or above you don't need to use this method. The native constant "::class" was implemented.
     */
    public static function className()
    {
        return get_called_class();
    }
}
