<?php

class Jirafe_Api_Collection_OrdersTest extends PHPUnit_Framework_TestCase
{
    private $clientMock;
    private $applications;
    private $application;
    private $sites;
    private $site;

    protected function setUp()
    {
        $this->clientMock = $this->getMockBuilder('Jirafe_Client')
            ->disableOriginalConstructor()
            ->getMock();

        $this->applications = new Jirafe_Api_Collection_Applications($this->clientMock);
        $this->application  = new Jirafe_Api_Resource_Application(45, $this->applications, $this->clientMock);
        $this->sites        = new Jirafe_Api_Collection_Sites($this->application, $this->clientMock);
        $this->site         = new Jirafe_Api_Resource_Site(234, $this->sites, $this->clientMock);
        $this->orders       = new Jirafe_Api_Collection_Orders($this->site, $this->clientMock);
    }

    /**
     * @test
     */
    public function shouldProvideCorrectPath()
    {
        $this->assertEquals('applications/45/sites/234/orders', $this->orders->getPath());
    }

    /**
     * @test
     */
    public function shouldBeAbleToProvideStatus()
    {
        $statusResponse = array('ok'=>1,'errors'=>1,'version'=>0);
        $this->clientMock
            ->expects($this->once())
            ->method('get')
            ->with('applications/45/sites/234/orders')
            ->will($this->returnValue(new Jirafe_HttpConnection_Response(json_encode($statusResponse), array(), 0, '')));

        $this->assertEquals($statusResponse, $this->orders->status());
    }
}
