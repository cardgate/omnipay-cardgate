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
 * CompletePurchaseRequest class - It requests information about the
 * transaction's status
 *
 * @author Martin Schipper martin@cardgate.com
 */
class CompletePurchaseRequest extends PurchaseRequest
{

	protected $endpoint = '/rest/v1/transactions/';

    /**
     * {@inheritdoc}
     */
	public function getData ()
	{
		$this->validate( 'transactionId' );
		return array( 
				'id' => $this->getTransactionId() 
		);
	}

    /**
     * {@inheritdoc}
     */
	public function sendData ( $data )
	{

		$headers = $this->getHeaders();
		try {
			$httpResponse = $this->httpClient->request(
				'GET',
				$this->getUrl() . $this->endpoint . $this->getTransactionId(),
				$headers);

			$this->response = new CompletePurchaseResponse(
				$this,
				simplexml_load_string( $httpResponse->getBody()->getContents() )
			);
		} catch (BadResponseException $e){
			$this->response = new BadResponseException( "CardGate RESTful API gave : " . $e->getResponse()->getBody( true ) );
		}

		return $this->response;
	}
}
