<?php

/*
 * CardGate driver for Omnipay PHP payment processing library
 * https://www.cardgate.com/
 *
 * Latest driver release:
 * https://github.com/cardgate/
 *
 */
namespace Omnipay\Cardgate;

use Omnipay\Tests\GatewayTestCase;
use Omnipay\Omnipay;
define( 'CG_SITEID', '1593' );
define( 'CG_MERCHANTID', 'testaccount' );
define( 'CG_APIKEY', 'TuZFZq6K2bPBi7VTkQNYM3JFlD21wz8tQ0sNCqODTZxJWKScRlVm366GaRoIlIdQ' );
define( 'CG_NOTIFYURL', 'http://omnipay-cardgate.dev1.dbcorp.nl/notify.php' );
define( 'CG_RETURNURL', 'http://omnipay-cardgate.dev1.dbcorp.nl/return.php' );
define( 'CG_CANCELURL', 'http://omnipay-cardgate.dev1.dbcorp.nl/cancel.php' );

/**
 * PHPUnit Gateway unittest
 *
 * @author Martin Schipper martin@cardgate.com
 */
class GatewayTest extends GatewayTestCase
{

	/**
	 *
	 * @var Gateway
	 */
	protected $gateway;

	protected function setUp ()
	{
		parent::setUp();
		
		$this->gateway = Omnipay::create( 'Cardgate' );
		
		$this->gateway->initialize( 
				array( 
						'siteId' => CG_SITEID, 
						'merchantId' => CG_MERCHANTID, 
						'apiKey' => CG_APIKEY, 
						'notifyUrl' => CG_NOTIFYURL, 
						'returnUrl' => CG_RETURNURL, 
						'cancelUrl' => CG_CANCELURL, 
						'testMode' => true 
				) );
	}

	public function testFetchIssuers ()
	{
		/**
		 *
		 * @var \Omnipay\Cardgate\Message\FetchIssuersRequest $request
		 */
		$response = $this->gateway->fetchIssuers()->send();
		$this->assertInstanceOf( 'Omnipay\Cardgate\Message\FetchIssuersResponse', $response );
		$issuers = $response->getIssuers();
		$this->assertInstanceOf( 'Omnipay\Common\Issuer', next ( $issuers ) );
	}

	public function testFetchPaymentMethods ()
	{
		/**
		 *
		 * @var \Omnipay\Cardgate\Message\FetchIssuersRequest $request
		 */
		$response = $this->gateway->fetchPaymentMethods()->send();
		$this->assertInstanceOf( 'Omnipay\Cardgate\Message\FetchPaymentMethodsResponse', $response );
		$paymentmethods = $response->getPaymentMethods();
		$this->assertInstanceOf( 'Omnipay\Common\PaymentMethod', next ( $paymentmethods ) );
	}

	public function testPurchase ()
	{
		/**
		 *
		 * @var \Omnipay\Cardgate\Message\PurchaseRequest $request
		 */
		$request = $this->gateway->purchase( 
				array( 
						'issuer' => '121', 
						'amount' => '10.00', 
						'currency' => 'EUR', 
						'description' => 'Description field', 
						'language' => 'nl', 
						'returnUrl' => 'http://localhost/return', 
						'notifyUrl' => 'http://localhost/notify' 
				) );
		$this->assertInstanceOf( 'Omnipay\Cardgate\Message\PurchaseRequest', $request );
		$this->assertSame( '121', $request->getIssuer() );
		$this->assertSame( '10.00', $request->getAmount() );
		$this->assertSame( 'Description field', $request->getDescription() );
		$this->assertSame( 'http://localhost/return', $request->getReturnUrl() );
		$this->assertSame( 'http://localhost/notify', $request->getNotifyUrl() );
	}

	public function testCompletePurchase ()
	{
		/**
		 *
		 * @var \Omnipay\Cardgate\Message\CompletePurchaseRequest $request
		 */
		$request = $this->gateway->completePurchase( array( 
				'transactionId' => '123456' 
		) );
		$this->assertInstanceOf( 'Omnipay\Cardgate\Message\CompletePurchaseRequest', $request );
		$this->assertSame( '123456', $request->getTransactionId() );
	}
}
