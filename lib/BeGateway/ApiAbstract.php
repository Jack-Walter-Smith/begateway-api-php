<?php

namespace BeGateway;

/**
 * Class ApiAbstract
 *
 * @package BeGateway
 */
abstract class ApiAbstract
{
    /**
     * @return mixed
     */
    protected abstract function _buildRequestMessage();

    /**
     * @var
     */
    protected $_language;
    /**
     * @var int
     */
    protected $_timeout_connect = 10;
    /**
     * @var int
     */
    protected $_timeout_read = 30;

    /**
     * @return \BeGateway\Response
     */
    public function submit()
    {
        try {
            $response = $this->_remoteRequest();
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $response = json_encode(array(
                'errors' => $msg,
                'message' => $msg,
            ));
        }

        return new Response($response);
    }

    /**
     * @return bool|string
     * @throws \Exception
     */
    protected function _remoteRequest()
    {
        return GatewayTransport::submit(
            $this->_endpoint(), $this->_buildRequestMessage(),
            $this->_timeout_read, $this->_timeout_connect);
    }

    /**
     * @return string
     */
    protected function _endpoint()
    {
        return Settings::$gatewayBase . '/transactions/' . $this->_getTransactionType();
    }

    /**
     * @return string
     */
    protected function _getTransactionType()
    {
        list($module, $klass) = explode('\\', get_class($this));
        $klass = str_replace('Operation', '', $klass);
        $klass = strtolower($klass) . 's';

        return $klass;
    }

    /**
     * @param $language_code
     */
    public function setLanguage($language_code)
    {
        if (in_array($language_code, Language::getSupportedLanguages())) {
            $this->_language = $language_code;
        } else {
            $this->_language = Language::getDefaultLanguage();
        }
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->_language;
    }

    /**
     * @param $timeout
     */
    public function setTimeoutConnect($timeout)
    {
        $this->_timeout_connect = $timeout;
    }

    /**
     * @param $timeout
     */
    public function setTimeoutRead($timeout)
    {
        $this->_timeout_read = $timeout;
    }
}


