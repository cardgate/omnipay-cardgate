# Omnipay: Cardgate

**Cardgate gateway for the Omnipay PHP payment processing library**

[Omnipay](https://github.com/omnipay/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements Cardgate support for Omnipay.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "cardgate/omnipay-cardgate": "~2.0"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

The following gateways are provided by this package:

* Cardgate

For general usage instructions, please see the main [Omnipay](https://github.com/omnipay/omnipay)
repository. See also the [Cardgate RESTFul Documentation](https://www.cardgate.com/api-docs/cg-api-rest.html)

## Example

```php

	$gateway = Omnipay::create( 'Cardgate' );
	$gateway->initialize( 
		array(
				'siteId' => '<siteid>',
				'merchantId' => '<merchantid>',
				'apiKey' => '<apikey>',
				'notifyUrl' => '<notifyurl>',
				'returnUrl' => '<returnurl>',
				'cancelUrl' => '<cancelurl>',
				'testMode' => <bool:enabled>
		) );

	// Start the purchase
    
	$response = $gateway->purchase( 
 		array(
 				'paymentMethod' => '<paymentmethodid>',
 				'issuer' => <nummeric-issuerid>,
 				'description' => "Test description.",
 				'transactionReference' => 'TEST_TransactionReference_000123_mustBeUnique',
 				'amount' => '10.00',
 				'currency' => 'EUR',
 				'ipaddress' => '10.10.10.10'
 		) )->send();
    
    if ( $response->isSuccessful() ) {
        // payment was successful: update database
        print_r( $response );
    } elseif ( $response->isRedirect() ) {
        // redirect to offsite payment oGateway
        $response->redirect();
    } else {
        // payment failed: display message to customer
        echo $response->getMessage();
    }

```

**Use the fetchIssuers response to see the available issuers**

```php
$response = $oGateway->fetchIssuers()->send();
if($response->isSuccessful()){
    $oIssuers = $response->getIssuers();
}
```    
    
The billing/shipping data are set with the `card` parameter, with an array or [CreditCard object](https://github.com/omnipay/omnipay#credit-card--payment-form-input).

        
## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/Cardgate/omnipay-cardgate/issues).
