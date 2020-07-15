<?php

namespace BeGateway;

/**
 * Class AdditionalData
 *
 * @package BeGateway
 */
class AdditionalData
{
    /**
     * @var array
     */
    protected $_receipt_text = array();
    /**
     * @var array
     */
    protected $_contract = array();
    /**
     * @var array
     */
    protected $_meta = array();

    /**
     * @param array $receipt
     */
    public function setReceipt($receipt)
    {
        $this->_receipt_text = $receipt;
    }

    /**
     * @return array
     */
    public function getReceipt()
    {
        return $this->_receipt_text;
    }

    /**
     * @param array $contract
     */
    public function setContract($contract)
    {
        $this->_contract = $contract;
    }

    /**
     * @return array
     */
    public function getContract()
    {
        return $this->_contract;
    }

    /**
     * @param array $meta
     */
    public function setMeta($meta)
    {
        $this->_meta = $meta;
    }

    /**
     * @return array
     */
    public function getMeta()
    {
        return $this->_meta;
    }
}
