<?php

class Jirafe_AdminApi_Resource_SiteTest extends PHPUnit_Framework_TestCase
{
    private $clientMock;
    private $applications;
    private $application;
    private $sites;
    private $site;

    protected function setUp()
    {
        $this->clientMock = $this->getMockBuilder('Jirafe_AdminApi_Client')
            ->disableOriginalConstructor()
            ->getMock();

        $this->applications = new Jirafe_AdminApi_Collection_Applications($this->clientMock);
        $this->application  = new Jirafe_AdminApi_Resource_Application(23, $this->applications, $this->clientMock);
        $this->sites        = new Jirafe_AdminApi_Collection_Sites($this->application, $this->clientMock);
        $this->site         = new Jirafe_AdminApi_Resource_Site(104, $this->sites, $this->clientMock);
    }

    /**
     * @test
     */
    public function shouldProvideCorrectPath()
    {
        $this->assertEquals('applications/23/sites/104', $this->site->getPath());
    }
}
