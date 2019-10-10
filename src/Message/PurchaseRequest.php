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

use Omnipay\Common\CreditCard;
use Omnipay\Common\Exception\InvalidRequestException;
use Guzzle\Http\Exception\BadResponseException;

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
		$this->validate( 'transactionReference', 'currency', 'siteId', 'amount', 'description', 'returnUrl', 
				'notifyUrl', 'ipaddress' );
		
		// PaymentMethod specific checks
		$this->validatePaymentMethodSpecific();
		
		// Transaction data
		$data = array( 
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
		);
		if ( $this->getPaymentMethod() == 'ideal' ) {
			$data['issuer_id'] = $this->getIssuer();
		}
		
		// Customer data
		$customerData = new CreditCard( $_POST );
		if ( $customerData && strlen( $customerData->getFirstName() ) && strlen( $customerData->getLastName() ) ) {
			$data['customer'] = array( 
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
			);
		}
		
		return $data;
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @see \Omnipay\Common\Message\RequestInterface::sendData()
	 */
	public function sendData ( $data )
	{
		
		$jsonData = json_encode( array( 
				'payment' => $data 
		) );
		$headers = $this->getHeaders();
		try {
			$httpResponse = $this->httpClient->request(
				'POST',
				$this->getUrl() . $this->endpoint . $this->getPaymentMethod() . '/payment/',
				$headers,
				$jsonData );

			$this->response = new PurchaseResponse(
				$this,
				simplexml_load_string( $httpResponse->getBody()->getContents() )
			);
		} catch (BadResponseException $e){
			$this->response = new BadResponseException( "CardGate RESTful API gave : " . $e->getResponse()->getBody( true ) );
		}

		return $this->response;
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
