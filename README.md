Mero Correios
=============

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/9fadab25-eff8-4439-b3e5-51315e18e413/mini.png)](https://insight.sensiolabs.com/projects/9fadab25-eff8-4439-b3e5-51315e18e413)
[![Build Status](https://travis-ci.org/merorafael/php-correios.svg?branch=master)](https://travis-ci.org/merorafael/php-correios)
[![Coverage Status](https://coveralls.io/repos/github/merorafael/php-correios/badge.svg?branch=master)](https://coveralls.io/github/merorafael/php-correios?branch=master)
[![Latest Stable Version](https://poser.pugx.org/mero/correios/v/stable.svg)](https://packagist.org/packages/mero/correios)
[![Total Downloads](https://poser.pugx.org/mero/correios/downloads.svg)](https://packagist.org/packages/mero/correios)
[![License](https://poser.pugx.org/mero/correios/license.svg)](https://packagist.org/packages/mero/correios)

Integration with [Correios](http://www.correios.com.br/) Webservices.

Requirements
------------

- PHP 5.4 or above
- SOAP extension

Instalation with composer
-------------------------

1. Open your project directory;
2. Run `composer require mero/correios` to add `Mero Correios`
 in your project vendor.
 
Client methods
--------------

| Method               | Description                                   | Parameters | Return                                                                                                                    | Exceptions                                                                                                                                                                                                                                                                                |
| -------------------- | --------------------------------------------- | ---------- | ------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| findAddressByZipCode | Find address informations using the zip code. | $zipCode   | [Mero\Correios\Model\Address](https://github.com/merorafael/php-correios/blob/master/src/Mero/Correios/Model/Address.php) | [AddressNotFoundException](https://github.com/merorafael/php-correios/blob/master/src/Mero/Correios/Exception/AddressNotFoundException.php) and [InvalidZipCodeException](https://github.com/merorafael/php-correios/blob/master/src/Mero/Correios/Exception/InvalidZipCodeException.php) |

Usage
-----

Declare an instance of object `Mero\Correios\Client` and use the methods
available in the client.

**Example:**

```php
<?php

$correios = new \Mero\Correios\Client();
$address = $correios->findAddressByZipCode('22640102'); // Return Address object related to '22640-102' zip-code.

echo $address->getAddress(); // Return the address 'Avenida das Américas'
echo $address->getNeighborhood(); // Return the neighborhood 'Barra da Tijuca'
echo $address->getCity(); // Return the city 'Rio de Janeiro'
echo $address->getState(); // Return the state 'RJ'
echo $address->getZipCode(); // Return the address '22640102'
```
