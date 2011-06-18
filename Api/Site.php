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
require_once dirname(dirname(__FILE__)).'/Api.php';

class Jirafe_Api_Site
{
   
    private $_api = null;
    
    public function __construct($api)
    {
        $this->_api = $api;
    }    
    
    /**
     * Get site information for site ID
     *
     * @param $siteId
     * @param $adminToken
     */
    public function getInfo ($siteId, $adminToken)
    {
        if(empty($siteId) || empty($adminToken)) {
            $this->_api->throwException('Site id and admin token can\'t be empty');
        }
        return $this->_api->sendData(Jirafe_Api::JIRAFE_API_SITES.'/'.$siteId, false, $adminToken, Jirafe_Api::HTTP_METHOD_GET);
    }

    /**
     * Get linked users for site ID
     *
     * @param $siteId
     * @param $adminToken
     */
    public function getLinkedUsers ($siteId, $adminToken)
    {
        if(empty($siteId) || empty($adminToken)) {
            $this->_api->throwException('Site id and admin token can\'t be empty');
        }
        return $this->_api->sendData(Jirafe_Api::JIRAFE_API_SITES.'/'.$siteId.Jirafe_Api::JIRAFE_API_USERS, false, $adminToken, Jirafe_Api::HTTP_METHOD_GET);
    }

    /**
     * Update site information
     *
     * @param $siteId
     * @param $adminToken
     * @param $timezone
     */
    public function update ($siteId, $adminToken, $timezone)
    {
        if(empty($siteId) || empty($adminToken)) {
            $this->_api->throwException('Site id and admin token can\'t be empty');
        }
        $data = array();
        $data['timezone'] = $timezone;
        return $this->_api->sendData(Jirafe_Api::JIRAFE_API_SITES.'/'.$siteId, $data, $adminToken, Jirafe_Api::HTTP_METHOD_PUT);
    }

    /**
     * Delete site (Caution! This operation can't be reverted and will delete all dependent data) 
     *
     * @param $siteId
     * @param $adminToken
     */
    public function delete ($siteId, $adminToken)
    {
        if(empty($siteId) || empty($adminToken)) {
            $this->_api->throwException('Site id and admin token can\'t be empty');
        }
        return $this->_api->sendData(Jirafe_Api::JIRAFE_API_SITES.'/'.$siteId, false, $adminToken, Jirafe_Api::HTTP_METHOD_DELETE);
    }
}