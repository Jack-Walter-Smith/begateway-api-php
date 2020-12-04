<?php

namespace BeGateway;

/**
 * Class Money
 *
 * @package BeGateway
 */
class Money
{
    /**
     * @var float
     */
    protected $_amount;
    /**
     * @var string
     */
    protected $_currency;
    /**
     * @var int
     */
    protected $_cents;

    /**
     * Money constructor.
     *
     * @param int    $amount
     * @param string $currency
     */
    public function __construct($amount = 0, $currency = 'USD')
    {
        $this->_currency = $currency;
        $this->setAmount($amount);
    }

    /**
     * @return int
     */
    public function getCents()
    {
        $amountInCents = intval(strval($this->_amount * $this->_currency_multiplyer()));

        return ($this->_cents && $this->_cents === $amountInCents) ? $this->_cents : $amountInCents;
    }

    /**
     * @param int $cents
     */
    public function setCents($cents)
    {
        $this->_cents = intval($cents);
        $this->_amount = NULL;
    }

    /**
     * @return float|int
     */
    private function _currency_multiplyer()
    {
        return pow(10, $this->_currency_power());
    }

    /**
     * @return int
     */
    private function _currency_power()
    {
        //array currency code => mutiplyer
        $exceptions = array(
            'BIF' => 0, 'BYR' => 0, 'CLF' => 0, 'CLP' => 0,
            'DJF' => 0, 'GNF' => 0, 'IDR' => 0, 'IQD' => 0,
            'ISK' => 0, 'JPY' => 0, 'KMF' => 0, 'KPW' => 0,
            'LAK' => 0, 'LBP' => 0, 'MMK' => 0, 'PYG' => 0,
            'SLL' => 0, 'STD' => 0, 'UYI' => 0, 'VND' => 0,
            'XAF' => 0, 'XOF' => 0, 'XPF' => 0, 'MOP' => 1,
            'JOD' => 3, 'KWD' => 3, 'LYD' => 3, 'OMR' => 3,
            'BYN' => 2, 'CVE' => 0, 'IRR' => 0, 'KRW' => 0,
            'RWF' => 0, 'VUV' => 0, 'BHD' => 3, 'TND' => 3,
        );

        $power = 2; //default value
        foreach ($exceptions as $key => $value) {
            if (($this->_currency == $key)) {
                $power = $value;
                break;
            }
        }

        return $power;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        if ($this->_amount) {
            $amount = $this->_amount;
        } else {
            $amount = $this->_cents / $this->_currency_multiplyer();
        }

        return floatval(strval($amount));
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->_amount = $amount;
        $this->_cents = NULL;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->_currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->_currency = $currency;
    }
}
