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

/**
 * CompletePurchaseResponse class - It contains information about the
 * transaction's status
 *
 * @author Martin Schipper martin@cardgate.com
 */
class CompletePurchaseResponse extends PurchaseResponse
{
    /**
     * {@inheritdoc}
     */
	public function isSuccessful ()
	{
		return ( $this->getStatus() == '200' );
	}

    /**
     * {@inheritdoc}
     */
	public function getMessage ()
	{
		$status = $this->getStatus();
		if ( ! is_null( $status ) ) {
			return $status;
		}
		return ( !is_null( $this->code ) ) ? $this->data : null;

	}

    /**
     * {@inheritdoc}
     */
	public function getStatus ()
	{
		return ( isset( $this->data->transaction->status ) ) ? ( string ) $this->data->transaction->status : null;
	}

    /**
     * {@inheritdoc}
     */
	public function getTransactionId ()
	{
		return ( isset( $this->data->transaction->transaction_id ) ) ? ( string ) $this->data->transaction->transaction_id : false;
	}
}
