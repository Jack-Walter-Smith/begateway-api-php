<?php

namespace BeGateway;

/**
 * Class CardToken
 *
 * @package BeGateway
 */
class CardToken extends ApiAbstract
{
    /**
     * @var \BeGateway\Card
     */
    public $card;

    /**
     * CardToken constructor.
     */
    public function __construct()
    {
        $this->card = new Card();
    }

    /**
     * @return \BeGateway\Response|\BeGateway\ResponseCardToken
     * @throws \Exception
     */
    public function submit()
    {
        return new ResponseCardToken($this->_remoteRequest());
    }

    /**
     * @return string
     */
    protected function _endpoint()
    {
        return Settings::$gatewayBase . '/credit_cards';
    }

    /**
     * @return array[]|mixed
     */
    protected function _buildRequestMessage()
    {
        $request = array(
            'request' => array(
                'holder' => $this->card->getCardHolder(),
                'number' => $this->card->getCardNumber(),
                'exp_month' => $this->card->getCardExpMonth(),
                'exp_year' => $this->card->getCardExpYear(),
                'token' => $this->card->getCardToken(),
            ),
        );

        Logger::getInstance()->write($request, Logger::DEBUG, get_class() . '::' . __FUNCTION__);

        return $request;
    }
}
