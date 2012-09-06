<?php

class Jirafe_Callback_EventTest extends PHPUnit_Framework_TestCase
{
    private $event;
    private $version = 123;
    private $action = 'someAction';
    private $data = array('foo' => 'bar');

    protected function setUp()
    {
      $this->event = new Jirafe_Callback_Event($this->version, $this->action, $this->data);
    }

    /**
     * @test
     */
    public function action()
    {
      $this->assertEquals('someAction', $this->event->action());
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
      $this->assertEquals('bar', $eventData['foo']);
    }
}


