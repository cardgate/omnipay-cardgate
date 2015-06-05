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

use Mockery as m;
use Omnipay\Tests\TestCase;

/**
 * PHPUnit CompletePurchase unittest
 *
 * @author Martin Schipper martin@cardgate.com
 */
class CompletePurchaseRequestTest extends TestCase
{

	protected function setUp ()
	{
		$request = $this->getHttpRequest();
		$arguments = array( 
				$this->getHttpClient(), 
				$request 
		);
		$this->request = m::mock( 'Omnipay\Cardgate\Message\CompletePurchaseRequest[getEndpoint]', $arguments );
		$this->request->setMerchantId( CG_MERCHANTID );
		$this->request->setApiKey( CG_APIKEY );
		$this->request->setSiteId( CG_SITEID );
		$this->request->setTransactionId( '3768165' );
	}

	public function testData ()
	{
		$data = $this->request->getData();
		$this->assertSame( '3768165', $data['id'] );
	}

	public function testSendSuccess ()
	{
		$this->setMockHttpResponse( 'CompletePurchaseSuccess.txt' );
		$response = $this->request->send();
		$this->assertTrue( $response->isSuccessful() );
	}

	public function testSendFailure ()
	{
		$this->setMockHttpResponse( 'CompletePurchaseFailure.txt' );
		$response = $this->request->send();
		$this->assertFalse( $response->isSuccessful() );
		$this->assertEquals( '300', $response->getMessage() );
		$this->assertEquals( null, $response->getCode() );
	}

	public function testSendCancel ()
	{
		$this->setMockHttpResponse( 'CompletePurchaseCancel.txt' );
		$response = $this->request->send();
		$this->assertFalse( $response->isSuccessful() );
		$this->assertEquals( '309', $response->getMessage() );
		$this->assertEquals( null, $response->getCode());
    }
}