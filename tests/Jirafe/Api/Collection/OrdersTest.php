<?php

class Jirafe_AdminApi_Collection_OrdersTest extends PHPUnit_Framework_TestCase
{
    private $clientMock;
    private $applications;
    private $application;
    private $sites;
    private $site;
    private $orders;

    protected function setUp()
    {
        $this->clientMock = $this->getMockBuilder('Jirafe_AdminApi_Client')
            ->disableOriginalConstructor()
            ->getMock();

        $this->applications = new Jirafe_AdminApi_Collection_Applications($this->clientMock);
        $this->application  = new Jirafe_AdminApi_Resource_Application(45, $this->applications, $this->clientMock);
        $this->sites        = new Jirafe_AdminApi_Collection_Sites($this->application, $this->clientMock);
        $this->site         = new Jirafe_AdminApi_Resource_Site(234, $this->sites, $this->clientMock);
        $this->orders       = new Jirafe_AdminApi_Collection_Orders($this->site, $this->clientMock);
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
    public function shouldProvideStatusResource()
    {
        $status = $this->orders->status();
        $this->assertInstanceOf('Jirafe_AdminApi_Resource_Status',$status);
    }
}
