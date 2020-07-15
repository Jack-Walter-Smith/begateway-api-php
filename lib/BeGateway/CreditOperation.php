<?php

namespace BeGateway;

/**
 * Class CreditOperation
 *
 * @package BeGateway
 */
class CreditOperation extends ApiAbstract
{
    /**
     * @var \BeGateway\Card
     */
    public $card;
    /**
     * @var \BeGateway\Money
     */
    public $money;
    /**
     * @var string
     */
    protected $_description;
    /**
     * @var string
     */
    protected $_tracking_id;

    /**
     * CreditOperation constructor.
     */
    public function __construct()
    {
        $this->money = new Money();
        $this->card = new Card();
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->_description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * @param string $tracking_id
     */
    public function setTrackingId($tracking_id)
    {
        $this->_tracking_id = $tracking_id;
    }

    /**
     * @return string
     */
    public function getTrackingId()
    {
        return $this->_tracking_id;
    }

    /**
     * @return array[]|mixed
     */
    protected function _buildRequestMessage()
    {
        $request = array(
            'request' => array(
                'amount' => $this->money->getCents(),
                'currency' => $this->money->getCurrency(),
                'description' => $this->getDescription(),
                'tracking_id' => $this->getTrackingId(),
                'credit_card' => array(
                    'token' => $this->card->getCardToken(),
                ),
            ),
        );

        Logger::getInstance()->write($request, Logger::DEBUG, get_class() . '::' . __FUNCTION__);

        return $request;
    }
}