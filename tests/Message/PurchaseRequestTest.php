<?php

/*
 * CardGate driver for Omnipay PHP payment processing library
 * https://www.cardgate.com/
 *
 * Latest driver release:
 * https://github.com/cardgate/
 *
 */
namespace Omnipay\Cardgate\Message;

use Omnipay\Tests\TestCase;

/**
 * PHPUnit PurchaseRequest unittest
 *
 * @author Martin Schipper martin@cardgate.com
 */
class PurchaseRequestTest extends TestCase
{

	/**
	 *
	 * @var PurchaseRequest
	 *
	 */
	private $request;

	protected function setUp ()
	{
		$this->request = new PurchaseRequest( $this->getHttpClient(), $this->getHttpRequest() );
		$this->request->setMerchantId( CG_MERCHANTID );
		$this->request->setApiKey( CG_APIKEY );
        $this->request->setSiteId( CG_SITEID );
		$this->request->setCurrency( 'EUR' );
		$this->request->setAmount( '10.00' );
		$this->request->setReturnUrl( CG_RETURNURL );
		$this->request->setNotifyUrl( CG_NOTIFYURL );
		$this->request->setIpAddress( '10.10.10.10' );
		$this->request->setDescription( "Test description." );
		$this->request->setTransactionReference( 'TEST_TransactionReference_000123' );
		$this->request->setPaymentMethod( 'ideal' );
		$this->request->setIssuer( '121' );
	}

	public function testSendSuccess ()
	{
		$this->setMockHttpResponse( 'PurchaseSuccess.txt' );
		$response = $this->request->send();
		$this->assertFalse( $response->isSuccessful() );
		$this->assertTrue( $response->isRedirect() );
		$this->assertEquals( 
				'https://gateway.cardgateplus.com/simulator/?return_url=http://omnipay-cardgate.dev1.dbcorp.nl/return.php&ec=3768165', 
				$response->getRedirectUrl() );
		$this->assertEquals( '3768165', $response->getTransactionId() );
	}

	public function testSendError ()
	{
		$this->setMockHttpResponse( 'PurchaseError.txt' );
		$response = $this->request->send();
		$this->assertFalse( $response->isSuccessful() );
		$this->assertEquals( 'Request requires merchant authentication.', $response->getMessage() );
		$this->assertEquals( 'Unauthorized', $response->getCode() );
	}
}