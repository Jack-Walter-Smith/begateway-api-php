<?php

namespace BeGateway;

/**
 * Class ResponseApi
 *
 * @package BeGateway
 */
class ResponseApi extends ResponseBase
{
    /**
     * @return bool
     */
    public function isSuccess()
    {
        return isset($this->getResponse()->id);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        if ($this->isSuccess()) {
            return $this->getResponse()->id;
        }
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        if (isset($this->getResponse()->message)) {
            return $this->getResponse()->message;
        } elseif ($this->isError()) {
            return $this->_compileErrors();
        } else {
            return '';
        }
    }

    /**
     * @return string
     */
    private function _compileErrors()
    {
        $message = 'there are errors in request parameters.';
        if (isset($this->getResponse()->errors)) {
            foreach ($this->getResponse()->errors as $name => $desc) {
                $message .= ' ' . print_r($name, true);
                foreach ($desc as $value) {
                    $message .= ' ' . $value . '.';
                }
            }
        }

        return $message;
    }
}
