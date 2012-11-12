<?php

class Jirafe_AdminApi_Resource_UserTest extends PHPUnit_Framework_TestCase
{
    private $clientMock;
    private $users;
    private $user;

    protected function setUp()
    {
        $this->clientMock = $this->getMockBuilder('Jirafe_AdminApi_Client')
            ->disableOriginalConstructor()
            ->getMock();

        $this->users = new Jirafe_AdminApi_Collection_Users($this->clientMock);
        $this->user  = new Jirafe_AdminApi_Resource_User(15, $this->users, $this->clientMock);
    }

    /**
     * @test
     */
    public function shouldProvideCorrectPath()
    {
        $this->assertEquals('users/15', $this->user->getPath());
    }
}
