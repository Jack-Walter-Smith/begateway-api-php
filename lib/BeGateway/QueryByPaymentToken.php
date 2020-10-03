<?php

namespace BeGateway;

/**
 * Class QueryByPaymentToken
 *
 * @package BeGateway
 */
class QueryByPaymentToken extends ApiAbstract
{
    /**
     * @var string
     */
    protected $_token;

    /**
     * @return \BeGateway\Response|\BeGateway\ResponseCheckout
     * @throws \Exception
     */
    public function submit()
    {
        return new ResponseCheckout($this->_remoteRequest());
    }

    /**
     * @return string
     */
    protected function _endpoint()
    {
        return Settings::$checkoutBase . '/ctp/api/checkouts/' . $this->getToken();
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->_token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->_token = $token;
    }

    /**
     * @return mixed|string
     */
    protected function _buildRequestMessage()
    {
        return '';
    }
}
