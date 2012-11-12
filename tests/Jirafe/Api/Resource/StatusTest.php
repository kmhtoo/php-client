<?php

class Jirafe_AdminApi_Resource_StatusTest extends PHPUnit_Framework_TestCase
{
    private $clientMock;
    private $applications;
    private $application;
    private $sites;
    private $site;
    private $orders;
    private $status;

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
        $this->status       = new Jirafe_AdminApi_Resource_Status($this->orders, $this->clientMock);
    }

    /**
     * @test
     */
    public function shouldProvideCorrectPath()
    {
        $this->assertEquals('applications/45/sites/234/orders/status', $this->status->getPath());
    }

    /**
     * @test
     */
    public function shouldProvideOrdersStatus()
    {
        $statusResponse = array('ok'=>1,'errors'=>1,'version'=>0);
        $this->clientMock
            ->expects($this->once())
            ->method('get')
            ->with('applications/45/sites/234/orders/status')
            ->will($this->returnValue(new Jirafe_HttpConnection_Response(json_encode($statusResponse), array(), 0, '')));

        $this->assertEquals($statusResponse, $this->status->fetch());
    }
}
