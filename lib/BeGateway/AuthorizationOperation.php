<?php

namespace BeGateway;

/**
 * Class AuthorizationOperation
 *
 * @package BeGateway
 */
class AuthorizationOperation extends ApiAbstract
{
    /**
     * @var \BeGateway\Customer
     */
    public $customer;
    /**
     * @var \BeGateway\Card
     */
    public $card;
    /**
     * @var \BeGateway\Money
     */
    public $money;
    /**
     * @var \BeGateway\AdditionalData
     */
    public $additional_data;
    /**
     * @var
     */
    protected $_description;
    /**
     * @var
     */
    protected $_tracking_id;
    /**
     * @var
     */
    protected $_notification_url;
    /**
     * @var
     */
    protected $_return_url;
    /**
     * @var bool
     */
    protected $_test_mode;

    /**
     * AuthorizationOperation constructor.
     */
    public function __construct()
    {
        $this->customer = new Customer();
        $this->money = new Money();
        $this->card = new Card();
        $this->additional_data = new AdditionalData();
        $this->_language = Language::getDefaultLanguage();
        $this->_test_mode = false;
    }

    /**
     * @param $description
     */
    public function setDescription($description)
    {
        $this->_description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * @param $tracking_id
     */
    public function setTrackingId($tracking_id)
    {
        $this->_tracking_id = $tracking_id;
    }

    /**
     * @return mixed
     */
    public function getTrackingId()
    {
        return $this->_tracking_id;
    }

    /**
     * @param $notification_url
     */
    public function setNotificationUrl($notification_url)
    {
        $this->_notification_url = $notification_url;
    }

    /**
     * @return mixed
     */
    public function getNotificationUrl()
    {
        return $this->_notification_url;
    }

    /**
     * @param $return_url
     */
    public function setReturnUrl($return_url)
    {
        $this->_return_url = $return_url;
    }

    /**
     * @return mixed
     */
    public function getReturnUrl()
    {
        return $this->_return_url;
    }

    /**
     * @param bool $mode
     */
    public function setTestMode($mode = true)
    {
        $this->_test_mode = $mode;
    }

    /**
     * @return bool
     */
    public function getTestMode()
    {
        return $this->_test_mode;
    }

    /**
     * @return array
     */
    protected function _buildCard()
    {
        $encrypted_card = array();
        $card = array(
            'number' => $this->card->getCardNumber(),
            'verification_value' => $this->card->getCardCvc(),
            'holder' => $this->card->getCardHolder(),
            'exp_month' => $this->card->getCardExpMonth(),
            'exp_year' => $this->card->getCardExpYear(),
            'token' => $this->card->getCardToken(),
            'skip_three_d_secure_verification' => $this->card->getSkipThreeDSecure(),
        );

        $card = array_filter($card);

        foreach ($card as $k => $v) {
            if (strpos($v, '$begatewaycse') !== false) {
                $encrypted_card[$k] = $v;
                unset($card[$k]);
            }
        }

        $response = array();

        if (count($card) > 0) {
            $response['credit_card'] = $card;
        }
        if (count($encrypted_card) > 0) {
            $response['encrypted_credit_card'] = $encrypted_card;
        }

        return $response;
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
                'notification_url' => $this->getNotificationUrl(),
                'return_url' => $this->getReturnUrl(),
                'language' => $this->getLanguage(),
                'test' => $this->getTestMode(),
                'customer' => array(
                    'ip' => $this->customer->getIp(),
                    'email' => $this->customer->getEmail(),
                    'birth_date' => $this->customer->getBirthDate(),
                ),
                'billing_address' => array(
                    'first_name' => $this->customer->getFirstName(),
                    'last_name' => $this->customer->getLastName(),
                    'country' => $this->customer->getCountry(),
                    'city' => $this->customer->getCity(),
                    'state' => $this->customer->getState(),
                    'zip' => $this->customer->getZip(),
                    'address' => $this->customer->getAddress(),
                    'phone' => $this->customer->getPhone(),
                ),
                'additional_data' => array(
                    'receipt_text' => $this->additional_data->getReceipt(),
                    'contract' => $this->additional_data->getContract(),
                    'meta' => $this->additional_data->getMeta(),
                ),
            ),
        );

        $request['request'] = array_merge($request['request'], $this->_buildCard());

        Logger::getInstance()->write($request, Logger::DEBUG, get_class() . '::' . __FUNCTION__);

        return $request;
    }
}
