<?php

namespace BeGateway;

/**
 * Class Logger
 *
 * @package BeGateway
 */
class Logger
{
    /**
     *
     */
    const INFO = 0;
    /**
     *
     */
    const WARNING = 1;
    /**
     *
     */
    const ERROR = 2;
    /**
     *
     */
    const DEBUG = 4;
    /**
     * @var int
     */
    private $_level;
    /**
     * @var
     */
    private static $instance;
    /**
     * @var string
     */
    private $_output = 'php://stderr';
    /**
     * @var bool
     */
    private $_message_callback = false;
    /**
     * @var bool
     */
    private $_mask = true;

    /**
     * Logger constructor.
     */
    private function __construct()
    {
        $this->_level = self::INFO;
    }

    /**
     * @param        $msg
     * @param int    $level
     * @param string $place
     *
     * @throws \Exception
     */
    public function write($msg, $level = self::INFO, $place = '')
    {
        $p = '';
        if (!empty($place)) {
            $p = "( $place )";
        }

        if ($this->_level >= $level) {
            $message = "[" . self::getLevelName($level) . " $p] => ";
            $message .= print_r($this->filter(var_export($msg, true)), true);
            $message .= PHP_EOL;
            if ($this->_output) {
                $this->sendToFile($message);
            }
            if ($this->_message_callback != false) {
                call_user_func($this->_message_callback, $message);
            }
        }
    }

    /**
     * @param $level
     */
    public function setLogLevel($level)
    {
        $this->_level = $level;
    }

    /**
     * @param $option
     */
    public function setPANfitering($option)
    {
        $this->_mask = $option;
    }

    /**
     * @param $callback
     */
    public function setOutputCallback($callback)
    {
        $this->_message_callback = $callback;
    }

    /**
     * @param $path
     */
    public function setOutputFile($path)
    {
        $this->_output = $path;
    }

    /**
     * @param $level
     *
     * @return string
     * @throws \Exception
     */
    public static function getLevelName($level)
    {
        switch ($level) {
            case self::INFO :
                return 'INFO';
                break;
            case self::WARNING :
                return 'WARNING';
                break;
            case self::DEBUG :
                return 'DEBUG';
                break;
            default:
                throw new \Exception('Unknown log level ' . $level);
        }
    }

    /**
     * @return \BeGateway\Logger
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param $message
     */
    private function sendToFile($message)
    {
        $fh = fopen($this->_output, 'w+');
        fwrite($fh, $message);
        fclose($fh);
    }

    /**
     * @param $message
     *
     * @return string|string[]|null
     */
    private function filter($message)
    {
        $card_filter = '/("number":")(\d{1})\d{8,13}(\d{4})(")/';
        $cvc_filter = '/("verification_value":")(\d{3,4})(")/';
        $modified = $message;
        if ($this->_mask) {
            $modified = preg_replace($card_filter, '$1$2 xxxx $3$4', $modified);
            $modified = preg_replace($cvc_filter, '$1xxx$3', $modified);
        }

        return $modified;
    }
}

?>
