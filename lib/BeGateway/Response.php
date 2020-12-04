<?php

namespace BeGateway;

/**
 * Class Response
 *
 * @package BeGateway
 */
class Response extends ResponseBase
{
    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->getStatus() == 'successful';
    }

    /**
     * @return bool|string
     */
    public function getStatus()
    {
        if ($this->hasTransactionSection()) {
            return $this->getResponse()->transaction->status;
        } elseif ($this->isError()) {
            return 'error';
        }

        return false;
    }

    /**
     * @return bool
     */
    public function hasTransactionSection()
    {
        return (is_object($this->getResponse()) && isset($this->getResponse()->transaction));
    }

    /**
     * @return bool
     */
    public function isFailed()
    {
        return $this->getStatus() == 'failed';
    }

    /**
     * @return bool
     */
    public function isIncomplete()
    {
        return $this->getStatus() == 'incomplete';
    }

    /**
     * @return bool
     */
    public function isPending()
    {
        return $this->getStatus() == 'pending';
    }

    /**
     * @return bool
     */
    public function isTest()
    {
        if ($this->hasTransactionSection()) {
            return $this->getResponse()->transaction->test == true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function getUid()
    {
        if ($this->hasTransactionSection()) {
            return $this->getResponse()->transaction->uid;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function getTrackingId()
    {
        if ($this->hasTransactionSection()) {
            return $this->getResponse()->transaction->tracking_id;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function getPaymentMethod()
    {
        if ($this->hasTransactionSection()) {
            return $this->getResponse()->transaction->payment_method_type;
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        if (is_object($this->getResponse())) {
            if (isset($this->getResponse()->message)) {
                return $this->getResponse()->message;
            } elseif (isset($this->getResponse()->transaction)) {
                return $this->getResponse()->transaction->message;
            } elseif (isset($this->getResponse()->response)) {
                return $this->getResponse()->response->message;
            }
        }

        return '';
    }
}
