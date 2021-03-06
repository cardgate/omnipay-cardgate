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

use Omnipay\Omnipay;

use Omnipay\Tests\GatewayTestCase;

define( 'CG_SITEID', '' );
define( 'CG_MERCHANTID', '' );
define( 'CG_APIKEY', '' );
define( 'CG_NOTIFYURL', '' );
define( 'CG_RETURNURL', '' );
define( 'CG_CANCELURL', '' );

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
	}

	public function testFetchPaymentMethods ()
	{
		/**
		 *
		 * @var \Omnipay\Cardgate\Message\FetchIssuersRequest $request
		 */
		$response = $this->gateway->fetchPaymentMethods()->send();
		$this->assertInstanceOf( 'Omnipay\Cardgate\Message\FetchPaymentMethodsResponse', $response );
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
