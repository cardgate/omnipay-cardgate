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

use \Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;

/**
 * AbstractRequest class
 *
 * @author Martin Schipper martin@cardgate.com
 */
abstract class AbstractRequest extends BaseAbstractRequest
{

    /**
     * Get live- or testURL.
     */
    public function getUrl()
    {
        return $this->getTestMode() ? 'https://secure-staging.curopayments.net' :' https://secure.curopayments.net';
    }

    // ------------ Request specific Getter'n'Setters ------------ //

    // ------------ Getter'n'Setters ------------ //

    /**
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    /**
     *
     * @param string $value
     * @return \Omnipay\Cardgate\Gateway
     */
    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }

    /**
     *
     * @return string
     */
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    /**
     *
     * @param string $value
     * @return \Omnipay\Cardgate\Gateway
     */
    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    /**
     *
     * @return string
     */
    public function getSiteId()
    {
        return $this->getParameter('siteId');
    }

    /**
     *
     * @param string $value
     * @return \Omnipay\Cardgate\Gateway
     */
    public function setSiteId($value)
    {
        return $this->setParameter('siteId', $value);
    }

    /**
     *
     * @return string
     */
    public function getIpAddress()
    {
        return $this->getParameter('ipaddress');
    }

    /**
     *
     * @param string $value
     * @return \Omnipay\Cardgate\Gateway
     */
    public function setIpAddress($value)
    {
        return $this->setParameter('ipaddress', $value);
    }

    /**
     *
     * @return string
     */
    public function getNotifyUrl()
    {
        return $this->getParameter('notifyUrl');
    }

    /**
     *
     * @param string $value
     * @return \Omnipay\Cardgate\Gateway
     */
    public function setNotifyUrl($value)
    {
        return $this->setParameter('notifyUrl', $value);
    }

    /**
     *
     * @return string
     */
    public function getReturnUrl()
    {
        return $this->getParameter('returnUrl');
    }

    /**
     *
     * @param string $value
     * @return \Omnipay\Cardgate\Gateway
     */
    public function setReturnUrl($value)
    {
        return $this->setParameter('returnUrl', $value);
    }

    /**
     *
     * @return string
     */
    public function getCancelUrl()
    {
        return $this->getParameter('cancelUrl');
    }

    /**
     *
     * @param string $value
     * @return \Omnipay\Cardgate\Gateway
     */
    public function setCancelUrl($value)
    {
        return $this->setParameter('cancelUrl', $value);
    }

    /**
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    /**
     *
     * @param string $value
     * @return \Omnipay\Cardgate\Gateway
     */
    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }
}
