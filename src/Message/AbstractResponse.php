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

use Omnipay\Common\Message\AbstractResponse as BaseAbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * AbstractResponse class
 *
 * @author Martin Schipper martin@cardgate.com
 */
abstract class AbstractResponse extends BaseAbstractResponse {

    /**
     * @var string
     */
    protected $code;

    /**
     * {@inheritdoc}
     */
    public function __construct( RequestInterface $request, $data ) 
    {
        parent::__construct( $request, $data );
        if ( isset( $this->data->error ) ) {
            $this->code = ( string ) $this->data->error->code;
            $this->data = ( string ) $this->data->error->message;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage() 
    {
        return $this->isSuccessful() ? null : $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        return $this->isSuccessful() ? null : $this->code;
    }

}
