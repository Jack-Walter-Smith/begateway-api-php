<?php

namespace BeGateway;

/**
 * Class Product
 *
 * @package BeGateway
 */
class Product extends ApiAbstract
{
    /**
     * @var \BeGateway\Money
     */
    public $money;
    /**
     * @var \BeGateway\AdditionalData
     */
    public $additional_data;
    /**
     * @var null|string
     */
    protected $_name = null;
    /**
     * @var null|string
     */
    protected $_description = null;
    /**
     * @var null|int
     */
    protected $_quantity = null;
    /**
     * @var bool
     */
    protected $_infinite = true;
    /**
     * @var string
     */
    protected $_language;
    /**
     * @var null|string
     */
    protected $_success_url = null;
    /**
     * @var null|string
     */
    protected $_fail_url = null;
    /**
     * @var null|string
     */
    protected $_return_url = null;
    /**
     * @var null|string
     */
    protected $_notification_url = null;
    /**
     * @var bool
     */
    protected $_immortal = true;
    /**
     * @var string
     */
    protected $_transaction_type = 'payment';
    /**
     * @var array
     */
    protected $_visible = array();
    /**
     * @var null|string
     */
    protected $_expired_at = null;
    /**
     * @var bool
     */
    protected $_test_mode = false;

    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->money = new Money();
        $this->additional_data = new AdditionalData();
        $this->_language = Language::getDefaultLanguage();
    }

    /**
     * @return string
     */
    protected function _endpoint()
    {
        return Settings::$apiBase . '/products';
    }

    /**
     * @return array|mixed
     * @throws \Exception
     */
    protected function _buildRequestMessage()
    {
        $request = array(
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'amount' => $this->money->getCents(),
            'currency' => $this->money->getCurrency(),
            'infinite' => $this->getInfinite(),
            'language' => $this->getLanguage(),
            'notification_url' => $this->getNotificationUrl(),
            'success_url' => $this->getSuccessUrl(),
            'fail_url' => $this->getFailUrl(),
            'return_url' => $this->getReturnUrl(),
            'immortal' => $this->getImmortal(),
            'visible' => $this->getVisibleFields(),
            'test' => $this->getTestMode(),
            'transaction_type' => $this->getTransactionType(),
            'additional_data' => array(
                'receipt_text' => $this->additional_data->getReceipt(),
                'contract' => $this->additional_data->getContract(),
                'meta' => $this->additional_data->getMeta(),
            ),
        );

        if ($this->_quantity > 0) {
            $request['quantity'] = $this->getQuantity();
            $request['infinite'] = false;
        }

        if (isset($this->_expired_at)) {
            $request['expired_at'] = $this->getExpiredAt();
            $request['immortal'] = false;
        }

        Logger::getInstance()->write($request, Logger::DEBUG, get_class() . '::' . __FUNCTION__);

        return $request;
    }

    /**
     * @return \BeGateway\Response|\BeGateway\ResponseApiProduct
     * @throws \Exception
     */
    public function submit()
    {
        return new ResponseApiProduct($this->_remoteRequest());
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param $description
     */
    public function setDescription($description)
    {
        $this->_description = $description;
    }

    /**
     * @return string|null
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * @param $quantity
     */
    public function setQuantity($quantity)
    {
        $this->_quantity = $quantity;
    }

    /**
     * @return int|null
     */
    public function getQuantity()
    {
        return $this->_quantity;
    }

    /**
     * @param bool $state
     */
    public function setInfinite($state = true)
    {
        $this->_infinite = $state;
    }

    /**
     * @return bool
     */
    public function getInfinite()
    {
        return $this->_infinite;
    }

    /**
     * @param bool $state
     */
    public function setImmortal($state = true)
    {
        $this->_immortal = $state;
    }

    /**
     * @return bool
     */
    public function getImmortal()
    {
        return $this->_immortal;
    }

    /**
     * @param $notification_url
     */
    public function setNotificationUrl($notification_url)
    {
        $this->_notification_url = $notification_url;
    }

    /**
     * @return string|null
     */
    public function getNotificationUrl()
    {
        return $this->_notification_url;
    }

    /**
     * @param $success_url
     */
    public function setSuccessUrl($success_url)
    {
        $this->_success_url = $success_url;
    }

    /**
     * @return string|null
     */
    public function getSuccessUrl()
    {
        return $this->_success_url;
    }

    /**
     * @param $fail_url
     */
    public function setFailUrl($fail_url)
    {
        $this->_fail_url = $fail_url;
    }

    /**
     * @return string|null
     */
    public function getFailUrl()
    {
        return $this->_fail_url;
    }

    /**
     * @param $return_url
     */
    public function setReturnUrl($return_url)
    {
        $this->_return_url = $return_url;
    }

    /**
     * @return string|null
     */
    public function getReturnUrl()
    {
        return $this->_return_url;
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
    public function setPaymentTransactionType()
    {
        $this->_transaction_type = 'payment';
    }

    /**
     * @param string $transactionType
     */
    public function setTransactionType($transactionType)
    {
        $this->_transaction_type = $transactionType;
    }

    /**
     * @return string
     */
    public function getTransactionType()
    {
        return $this->_transaction_type;
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
     * @return mixed|string
     */
    public function getLanguage()
    {
        return $this->_language;
    }

    # date when payment expires for payment
    # date is in ISO8601 format
    /**
     * @param $date
     */
    public function setExpiredAt($date)
    {
        $iso8601 = NULL;

        if ($date != NULL)
            $iso8601 = date(DATE_ISO8601, strtotime($date));

        $this->_expired_at = $iso8601;
    }

    /**
     * @return string|null
     */
    public function getExpiredAt()
    {
        return $this->_expired_at;
    }

    /**
     * @param array $fields
     *
     * @return array
     */
    public function setVisible($fields)
    {
        return array_unique(array_merge_recursive($this->_visible, $fields));
    }

    /**
     * @return array
     */
    public function getVisible()
    {
        return $this->_visible;
    }

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
     * @param $array
     * @param $value
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
}
