<?php

namespace BeGateway;

/**
 * Class QueryByUid
 *
 * @package BeGateway
 */
class QueryByUid extends ApiAbstract
{
    /**
     * @var string
     */
    protected $_uid;

    /**
     * @return string
     */
    protected function _endpoint()
    {
        return Settings::$gatewayBase . '/transactions/' . $this->getUid();
    }

    /**
     * @return string
     */
    public function getUid()
    {
        return $this->_uid;
    }

    /**
     * @param string $uid
     */
    public function setUid($uid)
    {
        $this->_uid = $uid;
    }

    /**
     * @return mixed|string
     */
    protected function _buildRequestMessage()
    {
        return '';
    }
}
