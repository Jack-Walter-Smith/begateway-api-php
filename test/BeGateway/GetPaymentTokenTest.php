<?php
namespace BeGateway;

class GetPaymentTokenTest extends TestCase {

  public function test_setDescription() {
    $auth = $this->getTestObjectInstance();
    $description = 'Test description';
    $auth->setDescription($description);
    $this->assertEqual($auth->getDescription(), $description);
  }

  public function test_setTrackingId() {
    $auth = $this->getTestObjectInstance();
    $tracking_id = 'Test tracking_id';
    $auth->setTrackingId($tracking_id);
    $this->assertEqual($auth->getTrackingId(), $tracking_id);
  }

  public function test_setExpiryDate() {
    $auth = $this->getTestObjectInstance();
    $date = '2020-12-30 23:21:46';
    $date_iso8601 = date(DATE_ISO8601, strtotime($date));
    $auth->setExpiredDate($date);
    $this->assertEqual($auth->getExpiredDate(), $date_iso8601);

    $date = NULL;
    $auth->setExpiredDate($date);
    $this->assertEqual($auth->getExpiredDate(), NULL);
  }

  public function test_setUrls() {

    $auth = $this->getTestObjectInstance();

    $url = 'http://www.example.com';

    $auth->setNotificationUrl($url . '/n' );
    $auth->setCancelUrl($url . '/c' );
    $auth->setSuccessUrl($url . '/s' );
    $auth->setDeclineUrl($url . '/d' );
    $auth->setFailUrl($url . '/f' );

    $this->assertEqual($auth->getNotificationUrl(), $url . '/n' );
    $this->assertEqual($auth->getCancelUrl(), $url . '/c' );
    $this->assertEqual($auth->getSuccessUrl(), $url . '/s' );
    $this->assertEqual($auth->getDeclineUrl(), $url . '/d' );
    $this->assertEqual($auth->getFailUrl(), $url . '/f' );

  }

  public function test_readonly() {
    $auth = $this->getTestObjectInstance();

    $auth->setFirstNameReadonly();
    $auth->setLastNameReadonly();
    $auth->setEmailReadonly();
    $auth->setCityReadonly();

    $this->assertEqual(array_diff($auth->getReadonly(), array( 'first_name', 'last_name', 'email', 'city' )), array() );

    $auth->unsetFirstNameReadonly();

    $this->assertEqual(array_diff($auth->getReadonly(), array( 'last_name', 'email', 'city' )), array() );

  }

  public function test_visible() {
    $auth = $this->getTestObjectInstance();
    $auth->setPhoneVisible();
    $auth->setAddressVisible();

    $this->assertEqual(array_diff($auth->getVisible(), array( 'phone', 'address' )), array() );

    $auth->unsetAddressVisible();

    $this->assertEqual(array_diff($auth->getVisible(), array( 'phone' )), array() );
  }

  public function test_transaction_type() {
    $auth = $this->getTestObjectInstance();
    $auth->setAuthorizationTransactionType();

    $this->assertEqual($auth->getTransactionType(), 'authorization');
  }

  public function test_setTestMode() {
    $auth = $this->getTestObjectInstance();
    $this->assertFalse($auth->getTestMode());
    $auth->setTestMode(true);
    $this->assertTrue($auth->getTestMode());
    $auth->setTestMode(false);
    $this->assertFalse($auth->getTestMode());
  }

  public function test_setAttempts() {
    $auth = $this->getTestObjectInstance();
    $auth->setAttempts(10);
    $this->assertEqual($auth->getAttempts(), 10);
  }

  public function test_buildRequestMessage() {
    $auth = $this->getTestObject();
    $arr = array(
      'checkout' => array(
        'version' => "2.1",
        'transaction_type' => 'payment',
        'attempts' => 5,
        'test' => true,
        'order' => array(
          'amount' => 1233,
          'currency' => 'EUR',
          'description' => 'test',
          'tracking_id' => 'my_custom_variable',
          'expired_at' => '2030-12-30T21:21:46+0000',
          'additional_data' => array(
              'receipt_text' => array(),
              'contract' => array(),
              'meta' => array()
          )
        ),
        'settings' => array(
          'success_url' => 'http://www.example.com/s',
          'cancel_url' => 'http://www.example.com/c',
          'decline_url' => 'http://www.example.com/d',
          'fail_url' => 'http://www.example.com/f',
          'notification_url' => 'http://www.example.com/n',
          'language' => 'zh',
          'customer_fields' => array(
            'visible' => array(),
            'read_only' => array(),
          ),
        ),
        'customer' => array(
          'email' => 'john@example.com',
          'first_name' => 'John',
          'last_name' => 'Doe',
          'country' => 'LV',
          'city' => 'Riga',
          'state' => '',
          'zip' => 'LV-1082',
          'address' => 'Demo str 12',
          'phone' => '',
          'birth_date' => ''
        )
      )
    );

    $reflection = new \ReflectionClass( 'BeGateway\GetPaymentToken');
    $method = $reflection->getMethod('_buildRequestMessage');
    $method->setAccessible(true);

    $request = $method->invoke($auth, '_buildRequestMessage');

    $this->assertEqual($arr, $request);

    $arr['checkout']['test'] = false;
    $auth->setTestMode(false);
    $request = $method->invoke($auth, '_buildRequestMessage');

    $this->assertEqual($arr, $request);
  }

  public function test_buildRequestMessageWithErip() {
    $auth = $this->getTestObject();
    $auth->money->setAmount(100);
    $auth->money->setCurrency('BYN');
    $erip = new PaymentMethod\Erip(array(
      'account_number' => '1234',
      'service_no' => '99999999',
      'order_id' => 100001,
      'service_info' => array('Test payment')
    ));
    $cc = new PaymentMethod\CreditCard();

    $auth->addPaymentMethod($erip);
    $auth->addPaymentMethod($cc);

    $arr = array(
      'checkout' => array(
        'version' => "2.1",
        'transaction_type' => 'payment',
        'attempts' => 5,
        'test' => true,
        'order' => array(
          'amount' => 10000,
          'currency' => 'BYN',
          'description' => 'test',
          'tracking_id' => 'my_custom_variable',
          'expired_at' => '2030-12-30T21:21:46+0000',
          'additional_data' => array(
              'receipt_text' => array(),
              'contract' => array(),
              'meta' => array()
          )
        ),
        'settings' => array(
          'success_url' => 'http://www.example.com/s',
          'cancel_url' => 'http://www.example.com/c',
          'decline_url' => 'http://www.example.com/d',
          'fail_url' => 'http://www.example.com/f',
          'notification_url' => 'http://www.example.com/n',
          'language' => 'zh',
          'customer_fields' => array(
            'visible' => array(),
            'read_only' => array(),
          ),
        ),
        'customer' => array(
          'email' => 'john@example.com',
          'first_name' => 'John',
          'last_name' => 'Doe',
          'country' => 'LV',
          'city' => 'Riga',
          'state' => '',
          'zip' => 'LV-1082',
          'address' => 'Demo str 12',
          'phone' => '',
          'birth_date' => null
        ),
        'payment_method' => array(
          'types' => array('erip', 'credit_card'),
          'erip' => array(
            'account_number' => '1234',
            'service_no' => '99999999',
            'order_id' => 100001,
            'service_info' => array('Test payment')
          ),
          'credit_card' => array()
        )
      )
    );

    $reflection = new \ReflectionClass( 'BeGateway\GetPaymentToken');
    $method = $reflection->getMethod('_buildRequestMessage');
    $method->setAccessible(true);

    $request = $method->invoke($auth, '_buildRequestMessage');

    $this->assertEqual($arr, $request);
  }

  public function test_buildRequestMessageWithEmexvoucher() {
    $auth = $this->getTestObject();
    $auth->money->setAmount(100);
    $auth->money->setCurrency('USD');
    $erip = new PaymentMethod\Erip(['account_number'=>12345]);
    $cc = new PaymentMethod\CreditCard();

    $auth->addPaymentMethod($erip);
    $auth->addPaymentMethod($cc);

    $arr = array(
      'checkout' => array(
        'version' => "2.1",
        'transaction_type' => 'payment',
        'test' => true,
        'attempts' => 5,
        'order' => array(
          'amount' => 10000,
          'currency' => 'USD',
          'description' => 'test',
          'tracking_id' => 'my_custom_variable',
          'expired_at' => '2030-12-30T21:21:46+0000',
          'additional_data' => array(
              'receipt_text' => array(),
              'contract' => array(),
              'meta' => array()
          )
        ),
        'settings' => array(
          'success_url' => 'http://www.example.com/s',
          'cancel_url' => 'http://www.example.com/c',
          'decline_url' => 'http://www.example.com/d',
          'fail_url' => 'http://www.example.com/f',
          'notification_url' => 'http://www.example.com/n',
          'language' => 'zh',
          'customer_fields' => array(
            'visible' => array(),
            'read_only' => array(),
          ),
        ),
        'customer' => array(
          'email' => 'john@example.com',
          'first_name' => 'John',
          'last_name' => 'Doe',
          'country' => 'LV',
          'city' => 'Riga',
          'state' => '',
          'zip' => 'LV-1082',
          'address' => 'Demo str 12',
          'phone' => '',
          'birth_date' => null
        ),
        'payment_method' => array(
          'types' => array('erip', 'credit_card'),
          'credit_card' => array(),
          'erip' => array(
            'order_id' => NULL,
            'account_number' => 12345,
            'service_no' => NULL
          )
        )
      )
    );

    $reflection = new \ReflectionClass( 'BeGateway\GetPaymentToken');
    $method = $reflection->getMethod('_buildRequestMessage');
    $method->setAccessible(true);

    $request = $method->invoke($auth, '_buildRequestMessage');

    $this->assertEqual($arr, $request);
  }

  public function test_endpoint() {

    $auth = $this->getTestObjectInstance();

    $reflection = new \ReflectionClass('BeGateway\GetPaymentToken');
    $method = $reflection->getMethod('_endpoint');
    $method->setAccessible(true);
    $url = $method->invoke($auth, '_endpoint');

    $this->assertEqual($url, Settings::$checkoutBase . '/ctp/api/checkouts');

  }

  public function test_successTokenRequest() {
    $auth = $this->getTestObject();

    $amount = rand(0,10000) / 100;

    $auth->money->setAmount($amount);

    $response = $auth->submit();

    $this->assertTrue($response->isValid());
    $this->assertTrue($response->isSuccess());
    $this->assertNotNull($response->getToken());

  }

  public function test_redirectUrl() {
    $auth = $this->getTestObject();

    $amount = rand(0,10000) / 100;

    $auth->money->setAmount($amount);

    $response = $auth->submit();

    $this->assertTrue($response->isValid());
    $this->assertTrue($response->isSuccess());
    $this->assertNotNull($response->getToken());
    $this->assertNotNull($response->getRedirectUrl());
    $this->assertEqual(\BeGateway\Settings::$checkoutBase . '/v2/checkout?token=' . $response->getToken(),
                       $response->getRedirectUrl());
    $this->assertEqual(\BeGateway\Settings::$checkoutBase . '/v2/checkout',
                       $response->getRedirectUrlScriptName());
  }

  public function test_errorTokenRequest() {

    $auth = $this->getTestObject();

    $amount = rand(0,10000) / 100;

    $auth->money->setAmount(0);
    $auth->setDescription('');

    $response = $auth->submit();

    $this->assertTrue($response->isValid());
    $this->assertTrue($response->isError());

  }

  protected function getTestObject() {

    $transaction = $this->getTestObjectInstance();

    $url = 'http://www.example.com';

    $transaction->money->setAmount(12.33);
    $transaction->money->setCurrency('EUR');
    $transaction->setPaymentTransactionType();
    $transaction->setAttempts(5);
    $transaction->setDescription('test');
    $transaction->setTrackingId('my_custom_variable');
    $transaction->setNotificationUrl($url . '/n' );
    $transaction->setCancelUrl($url . '/c' );
    $transaction->setSuccessUrl($url . '/s' );
    $transaction->setDeclineUrl($url . '/d' );
    $transaction->setFailUrl($url . '/f' );
    $transaction->setLanguage('zh');
    $transaction->setExpiredDate('2030-12-31T00:21:46+0300');
    $transaction->setTestMode(true);

    $transaction->customer->setFirstName('John');
    $transaction->customer->setLastName('Doe');
    $transaction->customer->setCountry('LV');
    $transaction->customer->setAddress('Demo str 12');
    $transaction->customer->setCity('Riga');
    $transaction->customer->setZip('LV-1082');
    $transaction->customer->setIp('127.0.0.1');
    $transaction->customer->setEmail('john@example.com');

    return $transaction;
  }

  protected function getTestObjectInstance() {
    self::authorizeFromEnv();

    return new GetPaymentToken();
  }
}
