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

use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * PurchaseResponse class - it contains information about a newly registered transaction
 *
 * @author Martin Schipper martin@cardgate.com
 */
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface {

    /**
     * {@inheritdoc}
     */
    public function isSuccessful() {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isRedirect() {
        return $this->data->payment->issuer_auth_url != '';
    }

    public function isPending() {
        return $this->data->payment->action == 'pending';
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectUrl() {
        return  ( string ) $this->data->payment->issuer_auth_url;
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectMethod() {
        return 'GET';
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectData() {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getTransactionId() {
        if ( isset( $this->data->payment ) && isset( $this->data->payment->transaction_id ) ) {
            return ( string ) $this->data->payment->transaction_id;
        }
        return false;
    }
}
