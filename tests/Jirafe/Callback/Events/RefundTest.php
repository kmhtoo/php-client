<?php

class Jirafe_Callback_Events_RefundTest extends PHPUnit_Framework_TestCase
{
    private $event;
    private $version = 123;
    private $action = 'refundCreate';

    protected function setUp()
    {
      $items = array();
      $items[] = array(
        'sku' => 1234,
        'name' => 'SomeItem',
        'price' => '12.34',
        'quantity' => 2,
        'category' => 'Widgets'
      );
      $this->event = new Jirafe_Callback_Events_Refund(
        $this->version,
        $this->action,
        array(
          'identifier' => 123,
          'orderIdentifier' => 456,
          'createdAt' => 'Thu Sep  6 12:34:56 UTC 2012',
          'grandTotal' => '100.000',
          'subTotal' => '80',
          'taxAmount' => '20.0',
          'shippingAmount' => '0.00',
          'discountAmount' => '-5.00',
          'items' => $items
        ));
    }

    /**
     * @test
     */
    public function action()
    {
      $this->assertEquals('refundCreate', $this->event->action());
    }

    /**
     * @test
     */
    public function version()
    {
      $this->assertEquals($this->version, $this->event->version());
    }

    /**
     * @test
     */
    public function data()
    {
      $eventData = $this->event->data();
      $this->assertEquals(123, $eventData['refundId']);
      $this->assertEquals(456, $eventData['orderId']);
      $this->assertEquals(1346934896, $eventData['time']);
      $this->assertEquals('100.00', $eventData['grandTotal']);
      $this->assertEquals('80.00', $eventData['subTotal']);
      $this->assertEquals('20.00', $eventData['taxAmount']);
      $this->assertEquals('0.00', $eventData['shippingAmount']);
      $this->assertEquals('5.00', $eventData['discountAmount']);
      $this->assertEquals(1234, $eventData['items'][0]['sku']);
    }
}

