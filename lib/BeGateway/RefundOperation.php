<?php

namespace BeGateway;

/**
 * Class RefundOperation
 *
 * @package BeGateway
 */
class RefundOperation extends ChildTransaction
{
    /**
     * @var string
     */
    protected $_reason;

    /**
     * @return array[]|mixed
     */
    protected function _buildRequestMessage()
    {
        $request = parent::_buildRequestMessage();

        $request['request']['reason'] = $this->getReason();

        return $request;
    }

    /**
     * @return string
     */
    public function getReason()
    {
        return $this->_reason;
    }

    /**
     * @param string $reason
     */
    public function setReason($reason)
    {
        $this->_reason = $reason;
    }
}

