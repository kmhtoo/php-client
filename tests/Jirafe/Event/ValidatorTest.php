<?php

class Jirafe_Event_ValidatorTest extends PHPUnit_Framework_TestCase
{
    const VALID_VERSION_NUMBER = 1;
    const VALID_ACTION = 'orderUpdate';
    const VALID_EVENT_DATA = '{"orderId":"100000001","status":"complete"}';

    private $validator;

    protected function setUp()
    {
        $this->validator = new Jirafe_Event_Validator();
    }

    protected function prepareTestData($v, $a, $d)
    {
        $testData = array();
        if (!is_null($v)) {
            if ($v === true) {
                $testData['v'] = self::VALID_VERSION_NUMBER;
            } else {
                $testData['v'] = $v;
            }
        }
        if (!is_null($a)) {
            if ($a === true) {
                $testData['a'] = self::VALID_ACTION;
            } else {
                $testData['a'] = $a;
            }
        }
        if (!is_null($d)) {
            if ($d === true) {
                $testData['d'] = json_decode(self::VALID_EVENT_DATA, true);
            } else {
                $testData['d'] = $d;
            }
        }
        return json_encode($testData);
    }

    /**
     * @test
     */
    public function shouldPassWithValidEvent()
    {
        $testData = $this->prepareTestData(true, true, true);
        $this->assertTrue($this->validator->run($testData));
    }

    /**
     * @test
     */
    public function shouldFailWhenNotUsingJson()
    {
        $testData = $this->prepareTestData(true, true, true);
        $this->assertFalse($this->validator->run(json_decode($testData, true)));
    }

    /**
     * @test
     */
    public function shouldFailWithMissingVersion()
    {
        $testData = $this->prepareTestData(null, true, true);
        $this->assertFalse($this->validator->run($testData));
    }

    /**
     * @test
     */
    public function shouldFailWithEmptyVersion()
    {
        $testData = $this->prepareTestData('', true, true);
        $this->assertFalse($this->validator->run($testData));
    }

    /**
     * @test
     */
    public function shouldFailWithNegativeVersion()
    {
        $testData = $this->prepareTestData(-1, true, true);
        $this->assertFalse($this->validator->run($testData));
    }

    /**
     * @test
     */
    public function shouldFailWithNonintVersion()
    {
        $testData = $this->prepareTestData('A', true, true);
        $this->assertFalse($this->validator->run($testData));
    }

    /**
     * @test
     */
    public function shouldFailWithMissingAction()
    {
        $testData = $this->prepareTestData(true, null, true);
        $this->assertFalse($this->validator->run($testData));
    }

    /**
     * @test
     */
    public function shouldFailWithEmptyAction()
    {
        $testData = $this->prepareTestData(true, '', true);
        $this->assertFalse($this->validator->run($testData));
    }

    /**
     * @test
     */
    public function shouldFailWithUnsupportedAction()
    {
        $testData = $this->prepareTestData(true, 'bogus-action', true);
        $this->assertFalse($this->validator->run($testData));
    }

    /**
     * @test
     */
    public function shouldFailWithMissingData()
    {
        $testData = $this->prepareTestData(true, true, null);
        $this->assertFalse($this->validator->run($testData));
    }

    /**
     * @test
     */
    public function shouldPassWithValidCustomerHash()
    {

    }

    /**
     * @test
     */
    public function shouldPassWithNullCustomerHash()
    {

    }

    /**
     * @test
     */
    public function shouldFailWithInvalidCustomerHash()
    {

    }
}