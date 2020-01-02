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
 * FetchIssuersRequest class - it fetches Issuers.
 * 
 * @author Martin Schipper martin@cardgate.com
 */
class FetchIssuersRequest extends AbstractRequest 
{    
    protected $endpoint = '/rest/v1/ideal/issuers/';
    
    /**
     * {@inheritdoc}
     */
    public function getData() {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function sendData( $data ) 
    {
       
        try {
			$response = $this->httpClient->request('GET', ($this->getUrl() . $this->endpoint), [
                'Accept' => 'application/xml',
				'Content-Type' => 'application/json',
				'Authorization' => 'Basic '. base64_encode($this->getMerchantId() . ':' . $this->getApiKey()),
			], http_build_query($data));
			 
			$httpResponse = simplexml_load_string($response->getBody()->getContents());
        }
        catch (Exception $e) {
			if ( $this->getTestMode() ) {
				throw new InvalidResponseException('CardGate RESTful API gave : ' . $e->getMessage(),$e->getCode());
			} 
			else {
				throw $e;
			}
        }
        return $this->response = new FetchIssuersResponse ($this, $httpResponse->xml() );
        
    }

}
