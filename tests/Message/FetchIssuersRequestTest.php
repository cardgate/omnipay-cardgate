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
 * PHPUnit FetchIssuerRequest unittest
 *
 * @author Martin Schipper martin@cardgate.com
 */
class FetchIssuersRequestTest extends TestCase
{

    /**
     *
     * @var FetchIssuersRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = new FetchIssuersRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('FetchIssuersSuccess.txt');
        $response = $this->request->send();
        $this->assertTrue($response->isSuccessful());
        $issuers = $response->getIssuers();
        $this->assertEquals(3, count($issuers));
        $issuer = $issuers[0];
        $this->assertInstanceOf('\Omnipay\Common\Issuer', $issuer);
        $this->assertEquals('121', $issuer->getId());
        $this->assertEquals('Test Issuer', $issuer->getName());
    }
}
