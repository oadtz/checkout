# Checkout

A simple PHP package for capture credit card payment using Adyen and Braintree service

  

## Installation

  

Use composer to clone this package.

  

    $ composer require oadtz/checkout

  

Or download this package and extract to your project or add this package to your composer.json

  
  

    "require": {
    
    ...
    
    "oadtz/checkout": "dev-master",
    
    ...
    
    },

  

, then run:

  

    $ composer update

  
## Adyen & Braintree Configuration
Open src/Config/checkout.php file and edit with your own configuration.

    'adyen' => [
	    'username' => 'Your Adyen username',
	    'password' => 'Your Adyen password',
	    'environment' => 'test',
		'appname' => 'Optional'
    ],
    'braintree' => [
	    'environment' => 'sandbox',
	    'merchant_id' => 'Your Braintree merchant ID',
	    'public_key' => 'Your Braintree public API key',
	    'private_key' => 'Your Braintree private API key'
    ]

## Unit test

Use this command to run the unit test.

  

    $ vendor/bin/phpunit
