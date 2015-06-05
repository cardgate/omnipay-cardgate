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

use Guzzle\Http\Exception\BadResponseException;

/**
 * FetchIssuersRequest class - it fetches Issuers.
 * 
 * @author Martin Schipper martin@cardgate.com
 */
class FetchIssuersRequest extends AbstractRequest {

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
    public function sendData( $data ) {
        
        // Test-API SSL cert issue
        $this->setSslVerification();
        
        $request = $this->httpClient->get( $this->getUrl() . $this->endpoint );
        $request->setAuth( $this->getMerchantId(), $this->getApiKey() );
        $request->setHeader( 'Content-type', 'application/json' );
        $request->addHeader( 'Accept', 'application/xml' );
        
        try {
            $httpResponse = $request->send();
        } catch (BadResponseException $e) {
            if ( $this->getTestMode() ) throw new BadResponseException( "CardGate RESTful API gave : " . $e->getResponse()->getBody( true ) );
            throw $e;
        }

        return $this->response = new FetchIssuersResponse ($this, $httpResponse->xml() );
        
    }

}
