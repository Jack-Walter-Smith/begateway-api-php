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
     * @var \BeGateway\Money
     */
    public $money;
    /**
     * @var string
     */
    protected $_parent_uid;

    /**
     * ChildTransaction constructor.
     */
    public function __construct()
    {
        $this->money = new Money();
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

    /**
     * @return string
     */
    public function getParentUid()
    {
        return $this->_parent_uid;
    }

    /**
     * @param string $uid
     */
    public function setParentUid($uid)
    {
        $this->_parent_uid = $uid;
    }
}
