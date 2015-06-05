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
		} elseif ( ! is_null( $this->code ) ) {
			return $this->data;
		}
		return null;
	}

    /**
     * {@inheritdoc}
     */
	public function getStatus ()
	{
		if ( isset( $this->data->transaction->status ) ) {
			return ( string ) $this->data->transaction->status;
		}
		return null;
	}

    /**
     * {@inheritdoc}
     */
	public function getTransactionId ()
	{
		if ( isset( $this->data->transaction->transaction_id ) ) {
			return ( string ) $this->data->transaction->transaction_id;
		}
		return false;
	}
}
