<?php

namespace BeGateway;

/**
 * Class Webhook
 *
 * @package BeGateway
 */
class Webhook extends Response
{
    /**
     * @var string
     */
    protected $_json_source = 'php://input';
    /**
     * @var null
     */
    protected $_id = null;
    /**
     * @var null
     */
    protected $_key = null;

    /**
     * Webhook constructor.
     */
    public function __construct()
    {
        parent::__construct(file_get_contents($this->_json_source));
    }

    /**
     * @return bool
     */
    public function isAuthorized()
    {
        $this->processAuthData();

        return $this->_id == Settings::$shopId
            && $this->_key == Settings::$shopKey;
    }

    /**
     * @return void
     */
    private function processAuthData()
    {
        $token = null;

        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            $this->_id = $_SERVER['PHP_AUTH_USER'];
            $this->_key = $_SERVER['PHP_AUTH_PW'];
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION']) && !is_null($_SERVER['HTTP_AUTHORIZATION'])) {
            $token = $_SERVER['HTTP_AUTHORIZATION'];
        } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']) && !is_null($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $token = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        }

        if ($token != null) {
            if (strpos(strtolower($token), 'basic') === 0) {
                list($this->_id, $this->_key) = explode(':', base64_decode(substr($token, 6)));
            }
        }
    }
}
