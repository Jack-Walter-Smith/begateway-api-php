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
     * @var string
     */
    protected $_current_endpoint;

    /**
     * @return Response
     */
    public function submit() {
        foreach ($this->_endpoints() as $_endpoint) {
            $this->_current_endpoint = $_endpoint;

            try {
                $response = $this->_remoteRequest();
            } catch (\Exception $e) {
                $msg = $e->getMessage();
                $response = json_encode(
                    array(
                        'errors' => $msg,
                        'message' => $msg
                    )
                );
            }

            $response = new Response($response);
            if ($response->getUid()) {
                break;
            }
        }

        return $response;
    }

    /**
     * @return string
     */
    protected function _endpoint()
    {
        return $this->_current_endpoint;
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

    /**
     * @return string[]
     */
    protected function _endpoints() {
        return array(
            Settings::$apiBase . '/beyag/payments/' . $this->getUid(),
            Settings::$apiBase . '/beyag/transactions/' . $this->getUid(),
            Settings::$gatewayBase . '/transactions/' . $this->getUid()
        );
    }
}
