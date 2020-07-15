<?php

namespace BeGateway;

/**
 * Class Card
 *
 * @package BeGateway
 */
class Card
{
    /**
     * @var string
     */
    protected $_card_number;
    /**
     * @var string
     */
    protected $_card_holder;
    /**
     * @var string
     */
    protected $_card_exp_month;
    /**
     * @var string
     */
    protected $_card_exp_year;
    /**
     * @var string
     */
    protected $_card_cvc;
    /**
     * @var string
     */
    protected $_first_one;
    /**
     * @var string
     */
    protected $_last_four;
    /**
     * @var string
     */
    protected $_brand;
    /**
     * @var null|string
     */
    protected $_card_token = null;
    /**
     * @var bool
     */
    protected $_skip_three_d_secure = false;
    /**
     * @var bool
     */
    protected $_encrypted = false;

    /**
     * @return bool
     */
    public function isEncrypted()
    {
        return $this->_encrypted;
    }

    /**
     * @param bool $encrypted
     */
    public function setEncrypted($encrypted)
    {
        $this->_encrypted = $encrypted;
    }

    /**
     * @param string $number
     */
    public function setCardNumber($number)
    {
        $this->_card_number = $number;
    }

    /**
     * @return string
     */
    public function getCardNumber()
    {
        return $this->_card_number;
    }

    /**
     * @param string $holder
     */
    public function setCardHolder($holder)
    {
        $this->_card_holder = $holder;
    }

    /**
     * @return string
     */
    public function getCardHolder()
    {
        return $this->_card_holder;
    }

    /**
     * @param string $exp_month
     */
    public function setCardExpMonth($exp_month)
    {
        if (preg_match('/^\d+/', $exp_month) == 1) {
            $this->_card_exp_month = sprintf('%02d', $exp_month);
        } else {
            $this->_card_exp_month = $exp_month;
        }
    }

    /**
     * @return string
     */
    public function getCardExpMonth()
    {
        return $this->_card_exp_month;
    }

    /**
     * @param string $exp_year
     */
    public function setCardExpYear($exp_year)
    {
        $this->_card_exp_year = $exp_year;
    }

    /**
     * @return string
     */
    public function getCardExpYear()
    {
        return $this->_card_exp_year;
    }

    /**
     * @param string $cvc
     */
    public function setCardCvc($cvc)
    {
        $this->_card_cvc = $cvc;
    }

    /**
     * @return string
     */
    public function getCardCvc()
    {
        return $this->_card_cvc;
    }

    /**
     * @param string $token
     */
    public function setCardToken($token)
    {
        $this->_card_token = $token;
    }

    /**
     * @return null|string
     */
    public function getCardToken()
    {
        return $this->_card_token;
    }

    /**
     * @param bool $skip
     */
    public function setSkipThreeDSecure($skip = false)
    {
        $this->_skip_three_d_secure = $skip;
    }

    /**
     * @return bool
     */
    public function getSkipThreeDSecure()
    {
        return $this->_skip_three_d_secure;
    }

    /**
     * @param string $brand
     */
    public function setBrand($brand)
    {
        $this->_brand = $brand;
    }

    /**
     * @return string
     */
    public function getBrand()
    {
        return $this->_brand;
    }

    /**
     * @param string $digit
     */
    public function setFirstOne($digit)
    {
        $this->_first_one = $digit;
    }

    /**
     * @return string
     */
    public function getFirstOne()
    {
        return $this->_first_one;
    }

    /**
     * @param string $digits
     */
    public function setLastFour($digits)
    {
        $this->_last_four = $digits;
    }

    /**
     * @return string
     */
    public function getLastFour()
    {
        return $this->_last_four;
    }
}
