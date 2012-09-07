<?php

class Jirafe_Callback_Events_EmptyTest extends PHPUnit_Framework_TestCase
{
    private $event;
    private $version = 123;

    protected function setUp()
    {
      $this->event = new Jirafe_Callback_Events_Empty($this->version);
    }

    /**
     * @test
     */
    public function action()
    {
      $this->assertEquals('noop', $this->event->action());
    }

    /**
     * @test
     */
    public function version()
    {
      $this->assertEquals($this->version, $this->event->version());
    }
}

