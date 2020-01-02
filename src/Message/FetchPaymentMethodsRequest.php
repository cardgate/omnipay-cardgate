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

use Exception;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\ResponseInterface;

/**
 * FetchPaymentMethodsRequest class - it fetches Paymentmethods.
 * 
 * @author Martin Schipper martin@cardgate.com
 */
class FetchPaymentMethodsRequest extends AbstractRequest 
{

    public function __construct()
	{
		dump('here');
		dd($this->httpClient);
	}
    protected $endpoint = '/rest/v1/billingoptions/';

    /**
     * {@inheritdoc}
     */
    public function getData() 
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function sendData( $data ) 
    {    
        try {
			$response = $this->httpClient->request('GET', ($this->getUrl() . $this->endpoint . $this->getSiteId() . '/'), [
                'Accept' => 'application/xml',
				'Content-Type' => 'application/json',
				'Authorization' => 'Basic '. base64_encode($this->getMerchantId() . ':' . $this->getApiKey()),
			], http_build_query($data));
			 
			$httpResponse = simplexml_load_string($response->getBody()->getContents());

        }catch (Exception $e) {

			if ( $this->getTestMode() ) {
				throw new InvalidResponseException('CardGate RESTful API gave : ' . $e->getMessage(),$e->getCode());
			} 
			else {
				throw $e;
			}
        }
        
        return $this->response = new FetchPaymentMethodsResponse( $this, $httpResponse->xml() );
        
    }

}
