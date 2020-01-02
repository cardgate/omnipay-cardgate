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
use Omnipay\Common\CreditCard;

/**
 * PurchaseRequest class - it registers a transaction
 *
 * @author Martin Schipper martin@cardgate.com
 */
class PurchaseRequest extends AbstractRequest
{
	protected $endpoint = '/rest/v1/';

	/**
	 * (non-PHPdoc)
	 *
	 * @see \Omnipay\Common\Message\MessageInterface::getData()
	 */
	public function getData ()
	{
		// PaymentMethod independant checks
		$this->validate( 
			'transactionReference', 
			'currency', 
			'siteId', 
			'amount', 
			'description', 
			'returnUrl', 
			'notifyUrl', 
			'ipaddress' );
		
		// PaymentMethod specific checks
		$this->validatePaymentMethodSpecific();
		
		// Transaction data
		$data = [
			'ref' => $this->getTransactionReference(), 
			'currency' => $this->getCurrency(), 
			'site_id' => $this->getSiteId(), 
			'amount' => $this->getAmountInteger(), 
			'description' => $this->getDescription(), 
			'control_url' => $this->getNotifyUrl(), 
			'return_url' => $this->getReturnUrl(), 
			'return_url_failed' => $this->getCancelUrl(), 
			'ip_address' => $this->getIpAddress(), 
			'language' => $this->getLanguage() 
		];

		if ( $this->getPaymentMethod() == 'ideal' ) {
			$data['issuer_id'] = $this->getIssuer();
		}
		
		// Customer data
		$customerData = new CreditCard( $_POST );

		if ( $customerData && strlen( $customerData->getFirstName() ) && strlen( $customerData->getLastName() ) ) {

			$data['customer'] = [
				'first_name' => $customerData->getFirstName(), 
				'last_name' => $customerData->getLastName(), 
				'company_name' => $customerData->getCompany(), 
				'address' => $customerData->getAddress1(), 
				'city' => $customerData->getCity(), 
				'state' => $customerData->getState(), 
				'postal_code' => $customerData->getPostcode(), 
				'country_code' => $customerData->getCountry(), 
				'phone_number' => $customerData->getPhone(), 
				'email' => $customerData->getEmail() 				
			];

		}//if
		
		return $data;
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @see \Omnipay\Common\Message\RequestInterface::sendData()
	 */
	public function sendData ( $data )
	{
		// Test-API SSL cert issue

		$jsonData = json_encode([
			'payment' => $data 
		]);
		
		try {
			
			$response = $this->httpClient->request('POST', ($this->getUrl() . $this->endpoint . $this->getPaymentMethod() . '/payment/'), [
                'Accept' => 'application/xml',
				'Content-Type' => 'application/json',
				'Authorization' => 'Basic '. base64_encode($this->getMerchantId() . ':' . $this->getApiKey()),
			], http_build_query(json_decode($jsonData, true)));
			 
			$httpResponse = simplexml_load_string($response->getBody()->getContents());
			
		} catch (Exception $e) {
			// $e->getResponse()->getBody(1); // ugly; but else ->xml() will fail
			// return new PurchaseResponse( $this, $e->getResponse()->xml() );
			if ( $this->getTestMode() ) {
				throw new InvalidResponseException('CardGate RESTful API gave : ' . $e->getMessage(),$e->getCode());
			} 
			else {
				throw $e;
			}
        }
		
		return new PurchaseResponse( $this, $httpResponse );
	}

	/**
	 * Some PaymentMethod specific validation checks.
	 *
	 * @throws InvalidRequestException
	 */
	private function validatePaymentMethodSpecific ()
	{
		$this->validate( 'paymentMethod' );
		switch ( $this->getPaymentMethod() ) {
			case 'ideal':
				$this->validate( 'issuer' );
				if ( $this->getCurrency() != 'EUR' )
					throw new InvalidRequestException( "Cardgate : iDeal only accepts EUR" );
				break;
			case 'mistercash':
				if ( $this->getCurrency() != 'EUR' )
					throw new InvalidRequestException( "Cardgate : Bancontact/Mistercash only accepts EUR" );
				break;
		}
	}
}
