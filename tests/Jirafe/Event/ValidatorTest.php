<?php

class Jirafe_Event_ValidatorTest extends PHPUnit_Framework_TestCase
{
    const VALID_VERSION_NUMBER = 1;
    const VALID_ACTION = 'orderUpdate';
    const VALID_EVENT_DATA = '{"orderId":"100000001","status":"complete"}';
    const VALID_STATUS = 'new';
    const VALID_CUSTOMER_HASH = '3d976156b13e4fe4dcd1824af3e4f8c2';
    const VALID_VISITOR_ID = '21fd234f5d6a95cf';
    const VALID_AMOUNT = '123.4567';
    const VALID_ITEMS = '{sku: "code", name: "productName", category: "category", price: 4.5, quantity: 6}';

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

    protected function prepareOrderTestData($orderId, $status, $customerHash, $visitorId, $time, $grandTotal, $subTotal, $taxAmount, $shippingAmount, $discountAmount, $items, $refundId=false)
    {
        $testData = array();
        if (!is_null($orderId)) {
            if ($orderId === true) {
                $testData['orderId'] = 'XYZ100001';
            } else {
                $testData['orderId'] = $orderId;
            }
        }
        if (!is_null($status)) {
            if ($status === true) {
                $testData['status'] = self::VALID_STATUS;
            } else {
                $testData['status'] = $status;
            }
        }
        if (!is_null($customerHash)) {
            if ($customerHash === true) {
                $testData['customerHash'] = self::VALID_CUSTOMER_HASH;
            } else {
                $testData['customerHash'] = $customerHash;
            }
        }

        if ($visitorId === true) {
            $testData['visitorId'] = self::VALID_VISITOR_ID;
        } else {
            $testData['visitorId'] = $visitorId;
        }

        if (!is_null($time)) {
            if ($time === true) {
                $testData['time'] = time();
            } else {
                $testData['time'] = $time;
            }
        }
        $amounts =  array('grandTotal', 'subTotal', 'taxAmount', 'shippingAmount', 'discountAmount');
        foreach ($amounts as $amount) {
            if (!is_null($$amount)) {
                if ($$amount === true) {
                    $testData[$amount] = self::VALID_AMOUNT;
                } else {
                    $testData[$amount] = $$amount;
                }
            }
        }
        if (!is_null($items)) {
            if ($items === true) {
                $testData['items'] = json_decode(self::VALID_ITEMS,true);
            } else {
                $testData['items'] = $items;
            }
        }
        if (!is_null($refundId) && $refundId !== false) {
            if ($refundId === true) {
                $testData['refundId'] = 'REF100001';
            } else {
                $testData['refundId'] = $refundId;
            }
        }

        return $testData;
    }

    protected function prepareTestItems($sku, $name, $category, $price, $quantity, $multiple = false)
    {
        $testData = array();
        if (!is_null($sku)) {
            if ($sku === true) {
                $testData['sku'] = 'test sku';
            } else {
                $testData['sku'] = $sku;
            }
        }
        if (!is_null($name)) {
            if ($name === true) {
                $testData['name'] = 'Iñtërnâtiônàlizætiøn';
            } else {
                $testData['name'] = $name;
            }
        }

        if ($category === true) {
            $testData['category'] = 'CATEGORY - Iñtërnâtiônàlizætiøn';
        } else {
            $testData['category'] = $category;
        }

        if (!is_null($price)) {
            if ($price === true) {
                $testData['price'] = self::VALID_AMOUNT;
            } else {
                $price['price'] = $price;
            }
        }
        if (!is_null($quantity)) {
            if ($quantity === true) {
                $testData['quantity'] = 4;
            } else {
                $quantity['quantity'] = $quantity;
            }
        }
        $testItems = array($testData);
        if ($multiple) {
            $testItems[] = $testData;
        }
        return $testItems;
    }

    /**
     * @test
     */
    public function shouldPassWithValidEvent()
    {
        $testData = $this->prepareTestData(true, true, true);
        $this->assertTrue($this->validator->run($testData, true));
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage Error in reading JSON
     */
    public function shouldFailWhenNotUsingJson()
    {
        $testData = $this->prepareTestData(true, true, true);
        $this->validator->run('bogus-data', true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage Error in reading JSON
     */
    public function shouldReturnFalseWhenFailingAndNoExceptions()
    {
        $testData = $this->prepareTestData(true, true, true);
        $this->assertFalse($this->validator->run('bogus-data', false));
        $this->validator->run('bogus-data', true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage Event is expected in JSON format
     */
    public function shouldFailWhenSendingAnArray()
    {
        $testData = $this->prepareTestData(true, true, true);
        $this->validator->run(json_decode($testData, true), true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage Event requires a version
     */
    public function shouldFailWithMissingVersion()
    {
        $testData = $this->prepareTestData(null, true, true);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage Version is not an integer
     */
    public function shouldFailWithEmptyVersion()
    {
        $testData = $this->prepareTestData('', true, true);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage Version is negative
     */
    public function shouldFailWithNegativeVersion()
    {
        $testData = $this->prepareTestData(-1, true, true);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage Version is not an integer
     */
    public function shouldFailWithNonintVersion()
    {
        $testData = $this->prepareTestData('A', true, true);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage Event requires an action
     */
    public function shouldFailWithMissingAction()
    {
        $testData = $this->prepareTestData(true, null, true);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage Action is not supported
     */
    public function shouldFailWithEmptyAction()
    {
        $testData = $this->prepareTestData(true, '', true);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage Action is not supported
     */
    public function shouldFailWithUnsupportedAction()
    {
        $testData = $this->prepareTestData(true, 'bogus-action', true);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage Event requires data
     */
    public function shouldFailWithMissingData()
    {
        $testData = $this->prepareTestData(true, true, null);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage Missing Data
     */
    public function shouldFailWithEmptyData()
    {
        $testData = $this->prepareTestData(true, true, '');
        $this->validator->run($testData, true);
    }

    /**
     * @test
     */
    public function shouldPassWithValidData()
    {
        $testOrderData = $this->prepareOrderTestData(true,true,true,true,true,true,true,true,true,true,true);
        $testData = $this->prepareTestData(true, 'orderCreate', $testOrderData);
        $this->assertTrue($this->validator->run($testData, true));
    }

    /**
     * @test
     */
    public function shouldPassWithMinimalValidData()
    {
        $testOrderData = $this->prepareOrderTestData(true,true,true,true,true,true,null,null,null,null,null);
        $testData = $this->prepareTestData(true, 'orderCreate', $testOrderData);
        $this->assertTrue($this->validator->run($testData, true));
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage Required field orderId is missing or empty
     */
    public function shouldFailWithMissingOrderId()
    {
        $testOrderData = $this->prepareOrderTestData(null,true,true,true,true,true,true,true,true,true,true);
        $testData = $this->prepareTestData(true, 'orderCreate', $testOrderData);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage Error in reading JSON
     */
    public function shouldFailWithInvalidOrderId()
    {
        $testOrderData = $this->prepareOrderTestData('test',true,true,true,true,true,true,true,true,true,true);
        $testData = $this->prepareTestData(true, 'orderCreate', $testOrderData);
        $testData = str_replace('test',iconv('UTF-8', 'ISO-8859-1', 'Iñtërnâtiônàlizætiøn'),$testData);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     * @covers Jirafe_Event_Validator::validateStrings
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage orderId is not UTF-8 encoded
     */
    public function shouldFailWithInvalidEncoding()
    {
        $testOrderData = $this->prepareOrderTestData(true,true,true,true,true,true,true,true,true,true,true);
        $testOrderData['orderId'] = iconv('UTF-8', 'ISO-8859-1', 'Iñtërnâtiônàlizætiøn');

        $method = new ReflectionMethod(
            'Jirafe_Event_Validator', 'validateStrings'
        );
        $method->setAccessible(TRUE);
        $method->invokeArgs(new Jirafe_Event_Validator, array($testOrderData));
    }

    /**
     * @test
     * @covers Jirafe_Event_Validator::validateStrings
     */
    public function shouldPassWithValidEncoding()
    {
        $testOrderData = $this->prepareOrderTestData(true,true,true,true,true,true,true,true,true,true,true);
        $testOrderData['orderId'] = iconv('UTF-8', 'UTF-8', 'Iñtërnâtiônàlizætiøn');

        $method = new ReflectionMethod(
            'Jirafe_Event_Validator', 'validateStrings'
        );
        $method->setAccessible(TRUE);
        $method->invokeArgs(new Jirafe_Event_Validator, array($testOrderData));
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage Required field status is missing or empty
     */
    public function shouldFailWithMissingStatus()
    {
        $testOrderData = $this->prepareOrderTestData(true,null,true,true,true,true,true,true,true,true,true);
        $testData = $this->prepareTestData(true, 'orderCreate', $testOrderData);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage Status bogus is not one of new,pendingPayment,processing,complete,closed,canceled,held,paymentReview,unknown
     */
    public function shouldFailWithInvalidStatus()
    {
        $testOrderData = $this->prepareOrderTestData(true,'bogus',true,true,true,true,true,true,true,true,true);
        $testData = $this->prepareTestData(true, 'orderCreate', $testOrderData);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage Required field customerHash is missing or empty
     */
    public function shouldFailWithMissingCustomerHash()
    {
        $testOrderData = $this->prepareOrderTestData(true,true,null,true,true,true,true,true,true,true,true);
        $testData = $this->prepareTestData(true, 'orderCreate', $testOrderData);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage customerHash is not in the expected format (md5)
     */
    public function shouldFailWithInvalidCustomerHash()
    {
        $testOrderData = $this->prepareOrderTestData(true,true,'bogus-customerhash',true,true,true,true,true,true,true,true);
        $testData = $this->prepareTestData(true, 'orderCreate', $testOrderData);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage visitorId is not in the expected format (md5)
     */
    public function shouldFailWithInvalidVisitorId()
    {
        $testOrderData = $this->prepareOrderTestData(true,true,true,'bogus-visitorid',true,true,true,true,true,true,true);
        $testData = $this->prepareTestData(true, 'orderCreate', $testOrderData);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     */
    public function shouldPassWithNullVisitorId()
    {
        $testOrderData = $this->prepareOrderTestData(true,true,true,null,true,true,true,true,true,true,true);
        $testData = $this->prepareTestData(true, 'orderCreate', $testOrderData);
        $this->assertTrue($this->validator->run($testData, true));
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage Required field time is missing or empty
     */
    public function shouldFailWithMissingTime()
    {
        $testOrderData = $this->prepareOrderTestData(true,true,true,true,null,true,true,true,true,true,true);
        $testData = $this->prepareTestData(true, 'orderCreate', $testOrderData);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage Time is not a Unix UTC timestamp
     */
    public function shouldFailWithInvalidTime()
    {
        $testOrderData = $this->prepareOrderTestData(true,true,true,true,'2008-07-25 01:24:40',true,true,true,true,true,true);
        $testData = $this->prepareTestData(true, 'orderCreate', $testOrderData);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage Required field grandTotal is missing or empty
     */
    public function shouldFailWithMissingGrandtotal()
    {
        $testOrderData = $this->prepareOrderTestData(true,true,true,true,true,null,true,true,true,true,true);
        $testData = $this->prepareTestData(true, 'orderCreate', $testOrderData);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage grandTotal is not in the format xxx.yyyy
     */
    public function shouldFailWithInvalidGrandtotal()
    {
        $testOrderData = $this->prepareOrderTestData(true,true,true,true,true,'12,9000',true,true,true,true,true);
        $testData = $this->prepareTestData(true, 'orderCreate', $testOrderData);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage subTotal is not in the format xxx.yyyy
     */
    public function shouldFailWithInvalidSubtotal()
    {
        $testOrderData = $this->prepareOrderTestData(true,true,true,true,true,true,2,true,true,true,true);
        $testData = $this->prepareTestData(true, 'orderCreate', $testOrderData);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage taxAmount is not in the format xxx.yyyy
     */
    public function shouldFailWithInvalidTaxAmount()
    {
        $testOrderData = $this->prepareOrderTestData(true,true,true,true,true,true,true,'bogus',true,true,true);
        $testData = $this->prepareTestData(true, 'orderCreate', $testOrderData);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage shippingAmount is not in the format xxx.yyyy
     */
    public function shouldFailWithInvalidShippingAmount()
    {
        $testOrderData = $this->prepareOrderTestData(true,true,true,true,true,true,true,true,'12.45',true, true);
        $testData = $this->prepareTestData(true, 'orderCreate', $testOrderData);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage discountAmount is not in the format xxx.yyyy
     */
    public function shouldFailWithInvalidDiscountAmount()
    {
        $testOrderData = $this->prepareOrderTestData(true,true,true,true,true,true,true,true,true,45,true);
        $testData = $this->prepareTestData(true, 'orderCreate', $testOrderData);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     */
    public function shouldPassWithValidItems()
    {
        $items = $this->prepareTestItems(true,true,true,true,true,true);
        $testOrderData = $this->prepareOrderTestData(true,true,true,true,true,true,true,true,true,true,$items);
        $testData = $this->prepareTestData(true, 'orderCreate', $testOrderData);
        $this->assertTrue($this->validator->run($testData, true));
    }

    /**
     * @test
     * @covers Jirafe_Event_Validator::validateItems
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage sku is not UTF-8 encoded
     */
    public function shouldFailWithInvalidEncodedItem()
    {
        $items = $this->prepareTestItems('test',true,true,true,true,true);
        $testOrderData = $this->prepareOrderTestData(true,true,true,true,true,true,true,true,true,true,$items);
        $testOrderData['items'][0]['sku'] = iconv('UTF-8', 'ISO-8859-1', 'Iñtërnâtiônàlizætiøn');

        $method = new ReflectionMethod(
            'Jirafe_Event_Validator', 'validateItems'
        );
        $method->setAccessible(TRUE);
        $method->invokeArgs(new Jirafe_Event_Validator, array($testOrderData));
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage sku is missing for item
     */
    public function shouldFailWithMissingItemSku()
    {
        $items = $this->prepareTestItems(null,true,true,true,true);
        $testOrderData = $this->prepareOrderTestData(true,true,true,true,true,true,true,true,true,true,$items);
        $testData = $this->prepareTestData(true, 'orderCreate', $testOrderData);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage name is missing for item
     */
    public function shouldFailWithMissingItemName()
    {
        $items = $this->prepareTestItems(true,null,true,true,true);
        $testOrderData = $this->prepareOrderTestData(true,true,true,true,true,true,true,true,true,true,$items);
        $testData = $this->prepareTestData(true, 'orderCreate', $testOrderData);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     */
    public function shouldPassWithMissingCategory()
    {
        $items = $this->prepareTestItems(true,true,null,true,true);
        $testOrderData = $this->prepareOrderTestData(true,true,true,true,true,true,true,true,true,true,$items);
        $testData = $this->prepareTestData(true, 'orderCreate', $testOrderData);
        $this->assertTrue($this->validator->run($testData, true));
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage price is missing for item
     */
    public function shouldFailWithMissingItemPrice()
    {
        $items = $this->prepareTestItems(true,true,true,null,true);
        $testOrderData = $this->prepareOrderTestData(true,true,true,true,true,true,true,true,true,true,$items);
        $testData = $this->prepareTestData(true, 'orderCreate', $testOrderData);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage quantity is missing for item
     */
    public function shouldFailWithMissingItemQuantity()
    {
        $items = $this->prepareTestItems(true,true,true,true, null);
        $testOrderData = $this->prepareOrderTestData(true,true,true,true,true,true,true,true,true,true,$items);
        $testData = $this->prepareTestData(true, 'orderCreate', $testOrderData);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage Required field orderId is missing or empty
     */
    public function shouldFailWithMissingOrderIdOnUpdate()
    {
        $testOrderData = $this->prepareOrderTestData(null,true,null,null,null,null,null,null,null,null,null);
        $testData = $this->prepareTestData(true, 'orderUpdate', $testOrderData);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage Update needs at least one of these fields status,time,customerHash,grandTotal,subTotal,taxAmount,shippingAmount,discountAmount,items,customToken,customData
     */
    public function shouldFailWithOnlyOrderId()
    {
        $testOrderData = $this->prepareOrderTestData(true,null,null,null,null,null,null,null,null,null,null);
        $testData = $this->prepareTestData(true, 'orderUpdate', $testOrderData);
        $this->validator->run($testData, true);
    }

    /**
     * @test
    */
    public function shouldPassWithOrderIdAndStatusUpdate()
    {
        $testOrderData = $this->prepareOrderTestData(true,true,null,null,null,null,null,null,null,null,null);
        $testData = $this->prepareTestData(true, 'orderUpdate', $testOrderData);
        $this->assertTrue($this->validator->run($testData, true));
    }

    /**
     * @test
     */
    public function shouldPassWithOrderIdAndGrandTotalUpdate()
    {
        $testOrderData = $this->prepareOrderTestData(true,null,null,null,null,true,null,null,null,null,null);
        $testData = $this->prepareTestData(true, 'orderUpdate', $testOrderData);
        $this->assertTrue($this->validator->run($testData, true));
    }

    /**
     * @test
     */
    public function shouldPassWithMultipleValidOrders()
    {
        $testOrderData = array();
        $testOrderData['orders'][] = $this->prepareOrderTestData('10000001',true,true,true,true,true,true,true,true,true,true);
        $testOrderData['orders'][] = $this->prepareOrderTestData('10000002',true,true,true,true,true,true,true,true,true,true);
        $testOrderData['orders'][] = $this->prepareOrderTestData('10000003',true,true,true,true,true,true,true,true,true,true);
        $testData = $this->prepareTestData(true, 'orderImport', $testOrderData);
        $this->assertTrue($this->validator->run($testData, true));
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage Required field status is missing or empty
     */
    public function shouldFailWithOneInvalidOrder()
    {
        $testOrderData = array();
        $testOrderData['orders'][] = $this->prepareOrderTestData('10000001',true,true,true,true,true,true,true,true,true,true);
        $testOrderData['orders'][] = $this->prepareOrderTestData('10000002',null,true,true,true,true,true,true,true,true,true);
        $testOrderData['orders'][] = $this->prepareOrderTestData('10000003',true,true,true,true,true,true,true,true,true,true);
        $testData = $this->prepareTestData(true, 'orderImport', $testOrderData);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage Order events are missing
     */
    public function shouldFailWithMissingOrders()
    {
        $testOrderData = array();
        $testOrderData[] = $this->prepareOrderTestData('10000001',true,true,true,true,true,true,true,true,true,true);
        $testOrderData[] = $this->prepareOrderTestData('10000002',null,true,true,true,true,true,true,true,true,true);
        $testOrderData[] = $this->prepareOrderTestData('10000003',true,true,true,true,true,true,true,true,true,true);
        $testData = $this->prepareTestData(true, 'orderImport', $testOrderData);
        $this->assertTrue($this->validator->run($testData, true));
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage Order events are missing
     */
    public function shouldFailWithNoOrders()
    {
        $testOrderData['orders'] = array();
        $testData = $this->prepareTestData(true, 'orderImport', $testOrderData);
        $this->assertTrue($this->validator->run($testData, true));
    }

    /**
     * @test
     */
    public function shouldPassWithNoop()
    {
        $testData = $this->prepareTestData(true, 'noop', '');
        $this->assertTrue($this->validator->run($testData, true));
    }

    /**
     * @test
     */
    public function shouldPassWithValidRefund()
    {
        $testOrderData = $this->prepareOrderTestData(true,null,null,null,true,true,true,true,true,true,true,true);
        $testData = $this->prepareTestData(true, 'refundCreate', $testOrderData);
        $this->assertTrue($this->validator->run($testData, true));
    }

    /**
     * @test
     */
    public function shouldPassWithMinimalValidRefund()
    {
        $testOrderData = $this->prepareOrderTestData(true,null,null,null,true,true,null,null,null,null,null,true);
        $testData = $this->prepareTestData(true, 'refundCreate', $testOrderData);
        $this->assertTrue($this->validator->run($testData, true));
    }

    /**
 * @test
 * @expectedException        Jirafe_Exception
 * @expectedExceptionMessage Required field orderId is missing or empty
 */
    public function shouldFailRefundWithMissingOrderId()
    {
        $testOrderData = $this->prepareOrderTestData(null,null,null,null,true,true,true,true,true,true,true,true);
        $testData = $this->prepareTestData(true, 'refundCreate', $testOrderData);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage Required field refundId is missing or empty
     */
    public function shouldFailRefundWithMissingRefundId()
    {
        $testOrderData = $this->prepareOrderTestData(null,null,null,null,true,true,true,true,true,true,true,null);
        $testData = $this->prepareTestData(true, 'refundCreate', $testOrderData);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage Required field grandTotal is missing or empty
     */
    public function shouldFailRefundWithMissingGrandTotal()
    {
        $testOrderData = $this->prepareOrderTestData(true,null,null,null,true,null,true,true,true,true,true,true);
        $testData = $this->prepareTestData(true, 'refundCreate', $testOrderData);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage Required field time is missing or empty
     */
    public function shouldFailRefundWithMissingTime()
    {
        $testOrderData = $this->prepareOrderTestData(true,null,null,null,null,true,true,true,true,true,true,true);
        $testData = $this->prepareTestData(true, 'refundCreate', $testOrderData);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     */
    public function shouldPassWithMultipleValidRefunds()
    {
        $testOrderData = array();
        $testOrderData['refunds'][] = $this->prepareOrderTestData('R1000001',null,null,null,true,true,true,true,true,true,true,true);
        $testOrderData['refunds'][] = $this->prepareOrderTestData('R1000002',null,null,null,true,true,true,true,true,true,true,true);
        $testOrderData['refunds'][] = $this->prepareOrderTestData('R1000003',null,null,null,true,true,true,true,true,true,true,true);
        $testData = $this->prepareTestData(true, 'refundImport', $testOrderData);
        $this->assertTrue($this->validator->run($testData, true));
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage Required field grandTotal is missing or empty
     */
    public function shouldFailWithOneInvalidRefund()
    {
        $testOrderData = array();
        $testOrderData['refunds'][] = $this->prepareOrderTestData('R1000001',null,null,null,true,true,true,true,true,true,true,true);
        $testOrderData['refunds'][] = $this->prepareOrderTestData('R1000002',null,null,null,true,null,true,true,true,true,true,true);
        $testOrderData['refunds'][] = $this->prepareOrderTestData('R1000003',null,null,null,true,true,true,true,true,true,true,true);
        $testData = $this->prepareTestData(true, 'refundImport', $testOrderData);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage Refund events are missing
     */
    public function shouldFailWithMissingRefunds()
    {
        $testOrderData = array();
        $testOrderData[] = $this->prepareOrderTestData('R1000001',null,null,null,true,true,true,true,true,true,true,true);
        $testOrderData[] = $this->prepareOrderTestData('R1000002',null,null,null,true,true,true,true,true,true,true,true);
        $testOrderData[] = $this->prepareOrderTestData('R1000003',null,null,null,true,true,true,true,true,true,true,true);
        $testData = $this->prepareTestData(true, 'refundImport', $testOrderData);
        $this->validator->run($testData, true);
    }

    /**
     * @test
     * @expectedException        Jirafe_Exception
     * @expectedExceptionMessage Refund events are missing
     */
    public function shouldFailWithNoRefunds()
    {
        $testOrderData['refunds'] = array();
        $testData = $this->prepareTestData(true, 'refundImport', $testOrderData);
        $this->assertTrue($this->validator->run($testData, true));
    }
}