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
 * PHPUnit FetchPaymentMethods unittest
 *
 * @author Martin Schipper martin@cardgate.com
 */
class FetchPaymentMethodsRequestTest extends TestCase
{

    /**
     *
     * @var FetchIssuersRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = new FetchPaymentMethodsRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->setMerchantId( CG_MERCHANTID );
        $this->request->setApiKey( CG_APIKEY );
        $this->request->setSiteId( CG_SITEID );
        
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('FetchPaymentMethodsSuccess.txt');
        $response = $this->request->send();
        $this->assertTrue($response->isSuccessful());
        $methods = $response->getPaymentMethods();
        $this->assertEquals(9, count($methods));
        $method = $methods[0];
        $this->assertInstanceOf('\Omnipay\Common\PaymentMethod', $method);
        $this->assertEquals('banktransfer', $method->getId());
        $this->assertEquals('Bank transfer', $method->getName());
    }
}
