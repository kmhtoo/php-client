<?php

class Jirafe_Callback_ObjectTest extends PHPUnit_Framework_TestCase
{
    private $objectInstance;

    protected function setUp()
    {
      $this->objectInstance = new Jirafe_Callback_Object();
    }

    /**
     * @test
     */
    public function baseUrl()
    {
      // $this->assertEquals('https://data.jirafe.com', $this->objectInstance->baseUrl());
    }
}

