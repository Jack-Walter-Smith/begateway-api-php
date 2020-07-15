<?php

namespace BeGateway;

/**
 * Class ChildTransaction
 *
 * @package BeGateway
 */
abstract class ChildTransaction extends ApiAbstract
{
    /**
     * @var string
     */
    protected $_parent_uid;
    /**
     * @var \BeGateway\Money
     */
    public $money;

    /**
     * ChildTransaction constructor.
     */
    public function __construct()
    {
        $this->money = new Money();
    }

    /**
     * @param string $uid
     */
    public function setParentUid($uid)
    {
        $this->_parent_uid = $uid;
    }

    /**
     * @return string
     */
    public function getParentUid()
    {
        return $this->_parent_uid;
    }

    /**
     * @return array[]|mixed
     */
    protected function _buildRequestMessage()
    {
        $request = array(
            'request' => array(
                'parent_uid' => $this->getParentUid(),
                'amount' => $this->money->getCents(),
            ),
        );

        Logger::getInstance()->write($request, Logger::DEBUG, get_class() . '::' . __FUNCTION__);

        return $request;
    }
}
