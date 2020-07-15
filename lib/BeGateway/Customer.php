<?php

namespace BeGateway;

/**
 * Class Customer
 *
 * @package BeGateway
 */
class Customer
{
    /**
     * @var string
     */
    protected $_ip;
    /**
     * @var string
     */
    protected $_email;
    /**
     * @var string
     */
    protected $_first_name;
    /**
     * @var string
     */
    protected $_last_name;
    /**
     * @var string
     */
    protected $_address;
    /**
     * @var string
     */
    protected $_city;
    /**
     * @var string
     */
    protected $_country;
    /**
     * @var string
     */
    protected $_state;
    /**
     * @var string
     */
    protected $_zip;
    /**
     * @var string
     */
    protected $_phone;
    /**
     * @var null|string
     */
    protected $_birth_date = NULL;

    /**
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->_ip = $this->_setNullIfEmpty($ip);
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->_ip;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->_email = $this->_setNullIfEmpty($email);
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * @param string $first_name
     */
    public function setFirstName($first_name)
    {
        $this->_first_name = $this->_setNullIfEmpty($first_name);
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->_first_name;
    }

    /**
     * @param string $last_name
     */
    public function setLastName($last_name)
    {
        $this->_last_name = $this->_setNullIfEmpty($last_name);
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->_last_name;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->_address = $this->_setNullIfEmpty($address);
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->_address;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->_city = $this->_setNullIfEmpty($city);
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->_city;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->_country = $this->_setNullIfEmpty($country);
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->_country;
    }

    /**
     * @param string $state
     */
    public function setState($state)
    {
        $this->_state = $this->_setNullIfEmpty($state);
    }

    /**
     * @return string|null
     */
    public function getState()
    {
        return (in_array($this->_country, array('US', 'CA'))) ? $this->_state : null;
    }

    /**
     * @param string $zip
     */
    public function setZip($zip)
    {
        $this->_zip = $this->_setNullIfEmpty($zip);
    }

    /**
     * @return string
     */
    public function getZip()
    {
        return $this->_zip;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->_phone = $this->_setNullIfEmpty($phone);
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->_phone;
    }

    /**
     * @param string $birthdate
     */
    public function setBirthDate($birthdate)
    {
        $this->_birth_date = $this->_setNullIfEmpty($birthdate);
    }

    /**
     * @return string|null
     */
    public function getBirthDate()
    {
        return $this->_birth_date;
    }

    /**
     * @param $resource
     *
     * @return mixed
     */
    private function _setNullIfEmpty(&$resource)
    {
        return (strlen($resource) > 0) ? $resource : null;
    }
}

