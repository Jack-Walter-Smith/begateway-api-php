<?php

namespace BeGateway;

/**
 * Class ResponseBase
 *
 * @package BeGateway
 */
abstract class ResponseBase
{
    /**
     * @var mixed
     */
    protected $_response;
    /**
     * @var mixed
     */
    protected $_response_array;

    /**
     * ResponseBase constructor.
     *
     * @param string $message
     */
    public function __construct($message)
    {
        $this->_response = json_decode($message);
        $this->_response_array = json_decode($message, true);
    }

    /**
     * @return bool
     */
    public abstract function isSuccess();

    /**
     * @return bool
     */
    public function isError()
    {
        if (!is_object($this->getResponse()))
            return true;

        if (isset($this->getResponse()->errors))
            return true;

        if (isset($this->getResponse()->response))
            return true;

        return false;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return !($this->_response === false || $this->_response == null);
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->_response;
    }

    /**
     * @return mixed
     */
    public function getResponseArray()
    {
        return $this->_response_array;
    }
}

