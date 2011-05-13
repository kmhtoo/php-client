<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @package     Fooman_Jirafe
 * @copyright   Copyright (c) 2010 Jirafe Inc (http://www.jirafe.com)
 * @copyright   Copyright (c) 2010 Fooman Limited (http://www.fooman.co.nz)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Jirafe_Api_Application extends Jirafe_Api
{
    
    /**
     *
     * @param $name - The name of Application
     * @param $url - The URL of the application, which will be the admin URL for the Magento instance
     * @param $callbackUrl - The URL that will be redirected to after an account login
     */
    public function create ($name, $url)
    {
        if(empty($name) || empty($url)) {
            throw new Exception('Application name and application url can\'t be empty');
        }
        $data = array();
        $data['name'] = $name;
        $data['url'] = $url;
        return $this->sendData(self::JIRAFE_API_APPLICATIONS, $data, false, Zend_Http_Client::POST);
    }

    /**
     * Get application information for application ID
     *
     * @param $appId
     * @param $adminToken
     */
    public function getInfo ($appId, $adminToken)
    {
        if(empty($appId) || empty($adminToken)) {
            throw new Exception('Application id and admin token can\'t be empty');
        }
        return $this->sendData(self::JIRAFE_API_APPLICATIONS.'/'.$appId, false, $adminToken, Zend_Http_Client::GET);
    }

    /**
     * Get linked sites for application ID
     *
     * @param $appId
     * @param $adminToken
     */
    public function getLinkedSites ($appId, $adminToken)
    {
        if(empty($appId) || empty($adminToken)) {
            throw new Exception('Application id and admin token can\'t be empty');
        }
        return $this->sendData(self::JIRAFE_API_APPLICATIONS.'/'.$appId.self::JIRAFE_API_SITES, false, $adminToken, Zend_Http_Client::GET);
    }

    /**
     * Update application information
     *
     * @param $appId
     * @param $adminToken
     */
    public function update ($appId, $adminToken, $url)
    {
        if(empty($appId) || empty($adminToken) || empty($url)) {
            throw new Exception('Application id, admin token and url can\'t be empty');
        }
        $data = array();
        $data['url'] = $url;
        return $this->sendData(self::JIRAFE_API_APPLICATIONS.'/'.$appId, $data, $adminToken, Zend_Http_Client::PUT);
    }

    /**
     * Delete application (Caution! This operation can't be reverted and will delete all dependent data)
     *
     * @param $appId
     * @param $adminToken
     */
    public function delete ($appId, $adminToken)
    {
        if(empty($appId) || empty($adminToken)) {
            throw new Exception('Application id and admin token can\'t be empty');
        }
        return $this->sendData(self::JIRAFE_API_APPLICATIONS.'/'.$appId, false, $adminToken, Zend_Http_Client::DELETE);
    }
       
}