<?php

namespace BeGateway;

use BeGateway\PaymentMethod\Base;
use Exception;

/**
 * Class GetPaymentToken
 *
 * @package BeGateway
 */
class GetPaymentToken extends ApiAbstract
{
    /**
     * @var \BeGateway\Customer
     */
    public $customer;
    /**
     * @var \BeGateway\Money
     */
    public $money;
    /**
     * @var \BeGateway\AdditionalData
     */
    public $additional_data;
    /**
     * @var string
     */
    protected $_description;
    /**
     * @var string
     */
    protected $_tracking_id;
    /**
     * @var string
     */
    protected $_success_url;
    /**
     * @var string
     */
    protected $_decline_url;
    /**
     * @var string
     */
    protected $_fail_url;
    /**
     * @var string
     */
    protected $_cancel_url;
    /**
     * @var string
     */
    protected $_notification_url;
    /**
     * @var string
     */
    protected $_transaction_type;
    /**
     * @var array
     */
    protected $_readonly;
    /**
     * @var array
     */
    protected $_visible;
    /**
     * @var array
     */
    protected $_payment_methods;
    /**
     * @var null|string
     */
    protected $_expired_at;
    /**
     * @var bool
     */
    protected $_test_mode;
    /**
     * @var null|int
     */
    protected $_attempts;
    /**
     * @var string[]
     */
    protected $_headers = array('X-Api-Version: 2');

    /**
     * GetPaymentToken constructor.
     */
    public function __construct()
    {
        $this->customer = new Customer();
        $this->money = new Money();
        $this->additional_data = new AdditionalData();
        $this->setPaymentTransactionType();
        $this->_language = Language::getDefaultLanguage();
        $this->_expired_at = NULL;
        $this->_readonly = array();
        $this->_visible = array();
        $this->_payment_methods = array();
        $this->_test_mode = false;
        $this->_attempts = NULL;
    }

    /**
     * @return void
     */
    public function setPaymentTransactionType()
    {
        $this->_transaction_type = 'payment';
    }

    /**
     * @return \BeGateway\Response|\BeGateway\ResponseCheckout
     * @throws \Exception
     */
    public function submit()
    {
        return new ResponseCheckout($this->_remoteRequest());
    }

    /**
     * @return void
     */
    public function setAuthorizationTransactionType()
    {
        $this->_transaction_type = 'authorization';
    }

    /**
     * @return void
     */
    public function setTokenizationTransactionType()
    {
        $this->_transaction_type = 'tokenization';
    }

    /**
     * @param string $language_code
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
     * @return void
     */
    public function setFirstNameReadonly()
    {
        $this->_readonly = self::_searchAndAdd($this->_readonly, 'first_name');
    }

    /**
     * @param array  $array
     * @param string $value
     *
     * @return mixed
     */
    private function _searchAndAdd($array, $value)
    {
        // search for $value in $array
        // if not found, adds $value to $array and returns $array
        // otherwise returns not altered $array
        $arr = $array;
        if (!in_array($value, $arr)) {
            array_push($arr, $value);
        }

        return $arr;
    }

    /**
     * @return void
     */
    public function unsetFirstNameReadonly()
    {
        $this->_readonly = array_diff($this->_readonly, array('first_name'));
    }

    /**
     * @return void
     */
    public function setLastNameReadonly()
    {
        $this->_readonly = self::_searchAndAdd($this->_readonly, 'last_name');
    }

    /**
     * @return void
     */
    public function unsetLastNameReadonly()
    {
        $this->_readonly = array_diff($this->_readonly, array('last_name'));
    }

    /**
     * @return void
     */
    public function setEmailReadonly()
    {
        $this->_readonly = self::_searchAndAdd($this->_readonly, 'email');
    }

    /**
     * @return void
     */
    public function unsetEmailReadonly()
    {
        $this->_readonly = array_diff($this->_readonly, array('email'));
    }

    /**
     * @return void
     */
    public function setAddressReadonly()
    {
        $this->_readonly = self::_searchAndAdd($this->_readonly, 'address');
    }

    /**
     * @return void
     */
    public function unsetAddressReadonly()
    {
        $this->_readonly = array_diff($this->_readonly, array('address'));
    }

    /**
     * @return void
     */
    public function setCityReadonly()
    {
        $this->_readonly = self::_searchAndAdd($this->_readonly, 'city');
    }

    /**
     * @return void
     */
    public function unsetCityReadonly()
    {
        $this->_readonly = array_diff($this->_readonly, array('city'));
    }

    /**
     * @return void
     */
    public function setStateReadonly()
    {
        $this->_readonly = self::_searchAndAdd($this->_readonly, 'state');
    }

    /**
     * @return void
     */
    public function unsetStateReadonly()
    {
        $this->_readonly = array_diff($this->_readonly, array('state'));
    }

    /**
     * @return void
     */
    public function setZipReadonly()
    {
        $this->_readonly = self::_searchAndAdd($this->_readonly, 'zip');
    }

    /**
     * @return void
     */
    public function unsetZipReadonly()
    {
        $this->_readonly = array_diff($this->_readonly, array('zip'));
    }

    /**
     * @return void
     */
    public function setPhoneReadonly()
    {
        $this->_readonly = self::_searchAndAdd($this->_readonly, 'phone');
    }

    /**
     * @return void
     */
    public function unsetPhoneReadonly()
    {
        $this->_readonly = array_diff($this->_readonly, array('phone'));
    }

    /**
     * @return void
     */
    public function setCountryReadonly()
    {
        $this->_readonly = self::_searchAndAdd($this->_readonly, 'country');
    }

    /**
     * @return void
     */
    public function unsetCountryReadonly()
    {
        $this->_readonly = array_diff($this->_readonly, array('country'));
    }

    # date when payment expires for payment
    # date is in ISO8601 format

    /**
     * @return void
     */
    public function setPhoneVisible()
    {
        $this->_visible = self::_searchAndAdd($this->_visible, 'phone');
    }

    /**
     * @return void
     */
    public function unsetPhoneVisible()
    {
        $this->_visible = array_diff($this->_visible, array('phone'));
    }

    /**
     * @return void
     */
    public function setAddressVisible()
    {
        $this->_visible = self::_searchAndAdd($this->_visible, 'address');
    }

    /**
     * @return void
     */
    public function unsetAddressVisible()
    {
        $this->_visible = array_diff($this->_visible, array('address'));
    }

    /**
     * @return void
     */
    public function setFirstNameVisible()
    {
        $this->_visible = self::_searchAndAdd($this->_visible, 'first_name');
    }

    /**
     * @return void
     */
    public function unsetFirstNameVisible()
    {
        $this->_visible = array_diff($this->_visible, array('first_name'));
    }

    /**
     * @return void
     */
    public function setLastNameVisible()
    {
        $this->_visible = self::_searchAndAdd($this->_visible, 'last_name');
    }

    /**
     * @return void
     */
    public function unsetLastNameVisible()
    {
        $this->_visible = array_diff($this->_visible, array('last_name'));
    }

    /**
     * @return void
     */
    public function setCityVisible()
    {
        $this->_visible = self::_searchAndAdd($this->_visible, 'city');
    }

    /**
     * @return void
     */
    public function unsetCityVisible()
    {
        $this->_visible = array_diff($this->_visible, array('city'));
    }

    /**
     * @return void
     */
    public function setStateVisible()
    {
        $this->_visible = self::_searchAndAdd($this->_visible, 'state');
    }

    /**
     * @return void
     */
    public function unsetStateVisible()
    {
        $this->_visible = array_diff($this->_visible, array('state'));
    }

    /**
     * @return void
     */
    public function setZipVisible()
    {
        $this->_visible = self::_searchAndAdd($this->_visible, 'zip');
    }

    /**
     * @return void
     */
    public function unsetZipVisible()
    {
        $this->_visible = array_diff($this->_visible, array('zip'));
    }

    /**
     * @return void
     */
    public function setCountryVisible()
    {
        $this->_visible = self::_searchAndAdd($this->_visible, 'country');
    }

    /**
     * @return void
     */
    public function unsetCountryVisible()
    {
        $this->_visible = array_diff($this->_visible, array('country'));
    }

    /**
     * @return void
     */
    public function setEmailVisible()
    {
        $this->_visible = self::_searchAndAdd($this->_visible, 'email');
    }

    /**
     * @return void
     */
    public function unsetEmailVisible()
    {
        $this->_visible = array_diff($this->_visible, array('email'));
    }

    /**
     * @return void
     */
    public function setBirthDateVisible()
    {
        $this->_visible = self::_searchAndAdd($this->_visible, 'birth_date');
    }

    /**
     * @return void
     */
    public function unsetBirthDateVisible()
    {
        $this->_visible = array_diff($this->_visible, array('birth_date'));
    }

    /**
     * @param array<Base> $methods
     */
    public function setPaymentMethods($methods)
    {
        foreach ($methods as $method) {
            if ($method instanceof Base) {
                $this->addPaymentMethod($method);
            }
        }
    }

    /**
     * @param PaymentMethod\Base $method
     */
    public function addPaymentMethod($method)
    {
        $this->_payment_methods[] = $method;
    }

    /**
     * @return string
     */
    protected function _endpoint()
    {
        return Settings::$checkoutBase . '/ctp/api/checkouts';
    }

    /**
     * @return array[]|mixed
     * @throws Exception
     */
    protected function _buildRequestMessage()
    {
        $request = array(
            'checkout' => array(
                'transaction_type' => $this->getTransactionType(),
                'attempts' => $this->getAttempts(),
                'test' => $this->getTestMode(),
                'order' => array(
                    'amount' => $this->money->getCents(),
                    'currency' => $this->money->getCurrency(),
                    'description' => $this->getDescription(),
                    'tracking_id' => $this->getTrackingId(),
                    'expired_at' => $this->getExpiredAt(),
                    'additional_data' => array(
                        'receipt_text' => $this->additional_data->getReceipt(),
                        'contract' => $this->additional_data->getContract(),
                        'meta' => $this->additional_data->getMeta(),
                    ),
                ),
                'settings' => array(
                    'notification_url' => $this->getNotificationUrl(),
                    'success_url' => $this->getSuccessUrl(),
                    'decline_url' => $this->getDeclineUrl(),
                    'fail_url' => $this->getFailUrl(),
                    'cancel_url' => $this->getCancelUrl(),
                    'language' => $this->getLanguage(),
                    'customer_fields' => array(
                        'read_only' => $this->getReadonly(),
                        'visible' => $this->getVisible(),
                    ),
                ),
                'customer' => array(
                    'email' => $this->customer->getEmail(),
                    'first_name' => $this->customer->getFirstName(),
                    'last_name' => $this->customer->getLastName(),
                    'country' => $this->customer->getCountry(),
                    'city' => $this->customer->getCity(),
                    'state' => $this->customer->getState(),
                    'zip' => $this->customer->getZip(),
                    'address' => $this->customer->getAddress(),
                    'phone' => $this->customer->getPhone(),
                    'birth_date' => $this->customer->getBirthDate(),
                ),
            ),
        );

        if (is_null($this->getAttempts())) {
            unset($request['checkout']['attempts']);
        }

        $payment_methods = $this->_getPaymentMethods();
        if ($payment_methods != NULL)
            $request['checkout']['payment_method'] = $payment_methods;

        Logger::getInstance()->write($request, Logger::DEBUG, get_class() . '::' . __FUNCTION__);

        return $request;
    }

    /**
     * @return string
     */
    public function getTransactionType()
    {
        return $this->_transaction_type;
    }

    /**
     * @param string $transactionType
     *
     * @throws \Exception
     */
    public function setTransactionType($transactionType)
    {
        $method = "set{$transactionType}TransactionType";

        if (method_exists($this, $method)) {
            $this->{$method}();
        } else {
            throw new Exception("Transaction type '$transactionType' cannot be set in " . __CLASS__);
        }
    }

    /**
     * @return int|null
     */
    public function getAttempts()
    {
        return $this->_attempts;
    }

    /**
     * @param int ate$attempts
     */
    public function setAttempts($attempts)
    {
        $this->_attempts = $attempts;
    }

    /**
     * @return bool
     */
    public function getTestMode()
    {
        return $this->_test_mode;
    }

    /**
     * @param bool $mode
     */
    public function setTestMode($mode = true)
    {
        $this->_test_mode = $mode;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->_description;
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
    public function getTrackingId()
    {
        return $this->_tracking_id;
    }

    /**
     * @param string $tracking_id
     */
    public function setTrackingId($tracking_id)
    {
        $this->_tracking_id = $tracking_id;
    }

    /**
     * @return string|null
     */
    public function getExpiredAt()
    {
        return $this->_expired_at;
    }

    /**
     * @param string $date ISO8601 format
     */
    public function setExpiredAt($date)
    {
        $iso8601 = NULL;

        if ($date != NULL)
            $iso8601 = date(DATE_ISO8601, strtotime($date));

        $this->_expired_at = $iso8601;
    }

    /**
     * @return string
     */
    public function getNotificationUrl()
    {
        return $this->_notification_url;
    }

    /**
     * @param string $notification_url
     */
    public function setNotificationUrl($notification_url)
    {
        $this->_notification_url = $notification_url;
    }

    /**
     * @return string
     */
    public function getSuccessUrl()
    {
        return $this->_success_url;
    }

    /**
     * @param string $success_url
     */
    public function setSuccessUrl($success_url)
    {
        $this->_success_url = $success_url;
    }

    /**
     * @return string
     */
    public function getDeclineUrl()
    {
        return $this->_decline_url;
    }

    /**
     * @param string $decline_url
     */
    public function setDeclineUrl($decline_url)
    {
        $this->_decline_url = $decline_url;
    }

    /**
     * @return string
     */
    public function getFailUrl()
    {
        return $this->_fail_url;
    }

    /**
     * @param string $fail_url
     */
    public function setFailUrl($fail_url)
    {
        $this->_fail_url = $fail_url;
    }

    /**
     * @return string
     */
    public function getCancelUrl()
    {
        return $this->_cancel_url;
    }

    /**
     * @param string $cancel_url
     */
    public function setCancelUrl($cancel_url)
    {
        $this->_cancel_url = $cancel_url;
    }

    /**
     * @return mixed|string
     */
    public function getLanguage()
    {
        return $this->_language;
    }

    /**
     * @return array
     */
    public function getReadonly()
    {
        return $this->_readonly;
    }

    /**
     * @param array $fields
     */
    public function setReadonly($fields)
    {
        // reset
        $this->_readonly = array();

        $this->_readonly = array_unique($fields);
    }

    /**
     * @return array
     */
    public function getVisible()
    {
        return $this->_visible;
    }

    /**
     * @param array $fields
     */
    public function setVisible($fields)
    {
        // reset
        $this->_visible = array();

        $this->_visible = array_unique($fields);
    }

    /**
     * @return array|null
     */
    private function _getPaymentMethods()
    {
        $arResult = array();

        if (!empty($this->_payment_methods)) {
            $arResult['types'] = array();
            foreach ($this->_payment_methods as $pm) {
                $arResult['types'][] = $pm->getName();
                $arResult[$pm->getName()] = $pm->getParamsArray();
            }
        } else {
            $arResult = NULL;
        }

        return $arResult;
    }
}

