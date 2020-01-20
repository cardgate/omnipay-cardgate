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
 * FetchPaymentMethodsRequest class - it fetches Paymentmethods.
 * 
 * @author Martin Schipper martin@cardgate.com
 */
class FetchPaymentMethodsRequest extends AbstractRequest {

    protected $endpoint = '/rest/v1/billingoptions/';

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
        
	    $headers = $this->getHeaders();

	    try {
		    $httpResponse = $this->httpClient->request(
			    'GET',
			    $this->getUrl() . $this->endpoint . $this->getParameter( 'siteId' ),
			    $headers);

		    $this->response = new FetchPaymentMethodsResponse(
			    $this,
			    simplexml_load_string( $httpResponse->getBody()->getContents() )
		    );
	    } catch (BadResponseException $e){
	    	$this->response = new BadResponseException( "CardGate RESTful API gave : " . $e->getResponse()->getBody( true ) );
	    }

	    return $this->response;

    }

}
