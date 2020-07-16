<?php

namespace BeGateway;

class AuthorizationOperationTest extends TestCase
{
    public function test_setDescription()
    {
        $auth = $this->getTestObjectInstance();

        $description = 'Test description';

        $auth->setDescription($description);

        $this->assertEqual($auth->getDescription(), $description);
    }

    protected function getTestObjectInstance($threeds = false)
    {
        self::authorizeFromEnv($threeds);

        return new AuthorizationOperation();
    }

    public function test_setTrackingId()
    {
        $auth = $this->getTestObjectInstance();
        $tracking_id = 'Test tracking_id';
        $auth->setTrackingId($tracking_id);
        $this->assertEqual($auth->getTrackingId(), $tracking_id);
    }

    public function test_setNotificationUrl()
    {
        $auth = $this->getTestObjectInstance();
        $url = 'http://www.example.com';
        $auth->setNotificationUrl($url);
        $this->assertEqual($auth->getNotificationUrl(), $url);
    }

    public function test_setReturnUrl()
    {
        $auth = $this->getTestObjectInstance();
        $url = 'http://www.example.com';
        $auth->setReturnUrl($url);
        $this->assertEqual($auth->getReturnUrl(), $url);
    }

    public function test_setTestMode()
    {
        $auth = $this->getTestObjectInstance();
        $this->assertFalse($auth->getTestMode());
        $auth->setTestMode(true);
        $this->assertTrue($auth->getTestMode());
        $auth->setTestMode(false);
        $this->assertFalse($auth->getTestMode());
    }

    public function test_buildRequestMessage()
    {
        $auth = $this->getTestObject();
        $arr = array(
            'request' => array(
                'amount' => 1233,
                'currency' => 'EUR',
                'description' => 'test',
                'tracking_id' => 'my_custom_variable',
                'notification_url' => '',
                'return_url' => '',
                'language' => 'de',
                'test' => true,
                'credit_card' => array(
                    'number' => '4200000000000000',
                    'verification_value' => '123',
                    'holder' => 'BEGATEWAY',
                    'exp_month' => '01',
                    'exp_year' => '2030',
                ),

                'customer' => array(
                    'ip' => '127.0.0.1',
                    'email' => 'john@example.com',
                    'birth_date' => '1970-01-01',
                ),

                'billing_address' => array(
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'country' => 'LV',
                    'city' => 'Riga',
                    'state' => '',
                    'zip' => 'LV-1082',
                    'address' => 'Demo str 12',
                    'phone' => '',
                ),

                'additional_data' => array(
                    'receipt_text' => array(),
                    'contract' => array(),
                    'meta' => array(),
                ),
            ),
        );

        $reflection = new \ReflectionClass('BeGateway\AuthorizationOperation');
        $method = $reflection->getMethod('_buildRequestMessage');
        $method->setAccessible(true);

        $request = $method->invoke($auth, '_buildRequestMessage');

        $this->assertEqual($arr, $request);

        $arr['request']['test'] = false;
        $auth->setTestMode(false);
        $request = $method->invoke($auth, '_buildRequestMessage');

        $this->assertEqual($arr, $request);

        $arr['request']['credit_card'] = array(
            'token' => '12345',
            'skip_three_d_secure_verification' => true,
        );

        $auth->card->setCardNumber(null);
        $auth->card->setCardHolder(null);
        $auth->card->setCardExpMonth(null);
        $auth->card->setCardExpYear(null);
        $auth->card->setCardCvc(null);
        $auth->card->setCardToken('12345');
        $auth->card->setSkipThreeDSecure(true);

        $request = $method->invoke($auth, '_buildRequestMessage');

        $this->assertEqual($arr, $request);
    }

    protected function getTestObject($threed = false)
    {
        $transaction = $this->getTestObjectInstance($threed);

        $transaction->money->setAmount(12.33);
        $transaction->money->setCurrency('EUR');
        $transaction->setDescription('test');
        $transaction->setTrackingId('my_custom_variable');
        $transaction->setLanguage('de');
        $transaction->setTestMode(true);

        $transaction->card->setCardNumber('4200000000000000');
        $transaction->card->setCardHolder('BEGATEWAY');
        $transaction->card->setCardExpMonth(1);
        $transaction->card->setCardExpYear(2030);
        $transaction->card->setCardCvc('123');

        $transaction->customer->setFirstName('John');
        $transaction->customer->setLastName('Doe');
        $transaction->customer->setCountry('LV');
        $transaction->customer->setAddress('Demo str 12');
        $transaction->customer->setCity('Riga');
        $transaction->customer->setZip('LV-1082');
        $transaction->customer->setIp('127.0.0.1');
        $transaction->customer->setEmail('john@example.com');
        $transaction->customer->setBirthDate('1970-01-01');

        return $transaction;
    }

    public function test_buildRequestMessageWithEncryptedCard()
    {
        $auth = $this->getTestObject();
        $arr = array(
            'request' => array(
                'amount' => 1233,
                'currency' => 'EUR',
                'description' => 'test',
                'tracking_id' => 'my_custom_variable',
                'notification_url' => '',
                'return_url' => '',
                'language' => 'de',
                'test' => true,
                'credit_card' => array(
                    'token' => 'dddddd',
                    'skip_three_d_secure_verification' => true,
                ),
                'encrypted_credit_card' => array(
                    'number' => '$begatewaycsejs_1_0_0$AAAAAA',
                    'verification_value' => '$begatewaycsejs_1_0_0$BBBBBB',
                    'holder' => '$begatewaycsejs_1_0_0$BEGATEWAY',
                    'exp_month' => '$begatewaycsejs_1_0_0$01',
                    'exp_year' => '$begatewaycsejs_1_0_0$2030',
                ),
                'customer' => array(
                    'ip' => '127.0.0.1',
                    'email' => 'john@example.com',
                    'birth_date' => '1970-01-01',
                ),
                'billing_address' => array(
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'country' => 'LV',
                    'city' => 'Riga',
                    'state' => '',
                    'zip' => 'LV-1082',
                    'address' => 'Demo str 12',
                    'phone' => '',
                ),

                'additional_data' => array(
                    'receipt_text' => array(),
                    'contract' => array(),
                    'meta' => array(),
                ),
            ),
        );

        $auth->card->setCardNumber('$begatewaycsejs_1_0_0$AAAAAA');
        $auth->card->setCardHolder('$begatewaycsejs_1_0_0$BEGATEWAY');
        $auth->card->setCardExpMonth('$begatewaycsejs_1_0_0$01');
        $auth->card->setCardExpYear('$begatewaycsejs_1_0_0$2030');
        $auth->card->setCardCvc('$begatewaycsejs_1_0_0$BBBBBB');
        $auth->card->setCardToken('dddddd');
        $auth->card->setSkipThreeDSecure(true);

        $reflection = new \ReflectionClass('BeGateway\AuthorizationOperation');
        $method = $reflection->getMethod('_buildRequestMessage');
        $method->setAccessible(true);

        $request = $method->invoke($auth, '_buildRequestMessage');

        $this->assertEqual($arr, $request);
    }

    public function test_endpoint()
    {
        $auth = $this->getTestObjectInstance();

        $reflection = new \ReflectionClass('BeGateway\AuthorizationOperation');
        $method = $reflection->getMethod('_endpoint');
        $method->setAccessible(true);
        $url = $method->invoke($auth, '_endpoint');

        $this->assertEqual($url, Settings::$gatewayBase . '/transactions/authorizations');
    }

    public function test_successAuthorization()
    {
        $auth = $this->getTestObject();

        $amount = rand(0, 10000) / 100;

        $auth->money->setAmount($amount);
        $cents = $auth->money->getCents();

        $response = $auth->submit();

        $this->assertTrue($response->isValid());
        $this->assertTrue($response->isSuccess());
        $this->assertEqual($response->getMessage(), 'Successfully processed');
        $this->assertNotNull($response->getUid());
        $this->assertEqual($response->getStatus(), 'successful');
        $this->assertEqual($cents, $response->getResponse()->transaction->amount);

        $arResponse = $response->getResponseArray();
        $this->assertEqual($cents, $arResponse['transaction']['amount']);
    }

    public function test_incompleteAuthorization()
    {
        $auth = $this->getTestObject(true);

        $amount = rand(0, 10000) / 100;

        $auth->money->setAmount($amount);
        $auth->card->setCardNumber('4012001037141112');
        $cents = $auth->money->getCents();

        $response = $auth->submit();

        $this->assertTrue($response->isValid());
        $this->assertTrue($response->isIncomplete());
        $this->assertFalse($response->getMessage());
        $this->assertNotNull($response->getUid());
        $this->assertNotNull($response->getResponse()->transaction->redirect_url);
        $this->assertTrue(preg_match('/process/', $response->getResponse()->transaction->redirect_url));
        $this->assertEqual($response->getStatus(), 'incomplete');
        $this->assertEqual($cents, $response->getResponse()->transaction->amount);

        $arResponse = $response->getResponseArray();
        $this->assertEqual($cents, $arResponse['transaction']['amount']);
    }

    public function test_failedAuthorization()
    {
        $auth = $this->getTestObject();
        $auth->card->setCardNumber('4005550000000019');

        $amount = rand(0, 10000) / 100;

        $auth->money->setAmount($amount);
        $cents = $auth->money->getCents();
        $auth->card->setCardExpMonth(10);

        $response = $auth->submit();

        $this->assertTrue($response->isValid());
        $this->assertTrue($response->isFailed());
        $this->assertEqual($response->getMessage(), 'Authorization was declined');
        $this->assertNotNull($response->getUid());
        $this->assertEqual($response->getStatus(), 'failed');
        $this->assertEqual($cents, $response->getResponse()->transaction->amount);

        $arResponse = $response->getResponseArray();
        $this->assertEqual($cents, $arResponse['transaction']['amount']);
    }

    public function test_errorAuthorization()
    {
        $auth = $this->getTestObject();

        $amount = rand(0, 10000) / 100;

        $auth->money->setAmount($amount);
        $cents = $auth->money->getCents();
        $auth->card->setCardExpYear(10);

        $response = $auth->submit();

        $this->assertTrue($response->isValid());
        $this->assertTrue($response->isError());
        $this->assertEqual($response->getMessage(), 'Exp year Invalid. Format should be: yyyy. Date is expired.');
        $this->assertEqual($response->getStatus(), 'error');
    }
}

?>
