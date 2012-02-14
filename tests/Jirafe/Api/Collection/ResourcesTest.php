<?php

class Jirafe_Api_Collection_ResourcesTest extends PHPUnit_Framework_TestCase
{
    private $clientMock;
    private $applications;
    private $application;
    private $resources;

    protected function setUp()
    {
        $this->clientMock = $this->getMockBuilder('Jirafe_Client')
            ->disableOriginalConstructor()
            ->getMock();

        $this->applications = new Jirafe_Api_Collection_Applications($this->clientMock);
        $this->application  = new Jirafe_Api_Resource_Application(41, $this->applications, $this->clientMock);
        $this->resources    = new Jirafe_Api_Collection_Resources($this->application, $this->clientMock);
    }

    /**
     * @test
     */
    public function shouldProvideCorrectPath()
    {
        $this->assertEquals('applications/41/resources', $this->resources->getPath());
    }

    /**
     * @test
     */
    public function shouldBeAbleToSyncResources()
    {
        $this->_shouldBeAbleToSyncResources(false);
    }
    
    /**
     * @test
     */
    public function shouldBeAbleToSyncResourcesOptingIn()
    {
        $this->_shouldBeAbleToSyncResources(true);
    }    
    
    protected function _shouldBeAbleToSyncResources($optin)
    {
        $sitesToSync = array(
            array('description' => 'site1', 'url' => 'http://site1'),
            array('description' => 'site1', 'url' => 'http://site2')
        );
        $usersToSync = array(
            array('email' => 'everzet@knplabs.com', 'username' => 'everzet'),
            array('email' => 'vjousse@knplabs.com', 'username' => 'vjousse')
        );

        $this->clientMock
            ->expects($this->once())
            ->method('post')
            ->with('applications/41/resources', array(), array(
                'platform_type' => 'other',
                'opt-in'=> $optin,
                'sites' => $sitesToSync,
                'users' => $usersToSync
            ))
            ->will($this->returnValue(new Jirafe_HttpConnection_Response('"hash"', array(), 0, '')));
            
        if ($optin) {    
            $this->assertEquals('hash', $this->resources->sync($sitesToSync, $usersToSync, true));
        } else {          
            $this->assertEquals('hash', $this->resources->sync($sitesToSync, $usersToSync));
        }
    }    
}
