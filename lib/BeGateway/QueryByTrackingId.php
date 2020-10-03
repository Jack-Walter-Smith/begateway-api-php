<?php

namespace BeGateway;

/**
 * Class QueryByTrackingId
 *
 * @package BeGateway
 */
class QueryByTrackingId extends ApiAbstract
{
    /**
     * @var string
     */
    protected $_tracking_id;

    /**
     * @return string
     */
    protected function _endpoint()
    {
        return Settings::$gatewayBase . '/v2/transactions/tracking_id/' . $this->getTrackingId();
    }

    /**
     * @return mixed
     */
    public function getTrackingId()
    {
        return $this->_tracking_id;
    }

    /**
     * @param string $trackingId
     */
    public function setTrackingId($trackingId)
    {
        $this->_tracking_id = $trackingId;
    }

    /**
     * @return mixed|string
     */
    protected function _buildRequestMessage()
    {
        return '';
    }
}
