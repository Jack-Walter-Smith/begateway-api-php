<?php

namespace BeGateway;

/**
 * Class ResponseCardToken
 *
 * @package BeGateway
 */
class ResponseCardToken extends ResponseBase
{
    /**
     * @var \BeGateway\Card
     */
    public $card;

    /**
     * ResponseCardToken constructor.
     *
     * @param string $message
     */
    public function __construct($message)
    {
        $this->card = new Card();

        parent::__construct($message);

        if ($this->isSuccess()) {
            $this->card->setCardToken($this->getResponse()->token);
            $this->card->setCardHolder($this->getResponse()->holder);
            $this->card->setCardExpMonth($this->getResponse()->exp_month);
            $this->card->setCardExpYear($this->getResponse()->exp_year);
            $this->card->setBrand($this->getResponse()->brand);
            $this->card->setFirstOne($this->getResponse()->first_1);
            $this->card->setLastFour($this->getResponse()->last_4);
        }
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return is_object($this->getResponse()) &&
            isset($this->getResponse()->token) &&
            is_string($this->getResponse()->token) &&
            strlen($this->getResponse()->token) > 0;
    }
}
