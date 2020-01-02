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
		return [
			'id' => $this->getTransactionId() 
		];
	}

    /**
     * {@inheritdoc}
     */
	public function sendData ( $data )
	{		
		try {
			$response = $this->httpClient->request('GET', ($this->getUrl() . $this->endpoint . $this->getTransactionId()), [
                'Accept' => 'application/xml',
				'Content-Type' => 'application/json',
				'Authorization' => 'Basic '. base64_encode($this->getMerchantId() . ':' . $this->getApiKey()),
			], http_build_query($data));
			 
			$httpResponse = simplexml_load_string($response->getBody()->getContents());

			$httpResponse = $request->send();
		} catch (Exception $e) {

			if ( $this->getTestMode() ) {
				throw new InvalidResponseException(
					'CardGate RESTful API gave : ' . $e->getMessage(),
					$e->getCode()
				);
			} 
			else {
				throw $e;
			}
        }
		
		return new CompletePurchaseResponse( $this, $httpResponse->xml() );
	}
}
