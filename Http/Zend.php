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


class Jirafe_Http_Zend extends Zend_Http_Client implements Jirafe_Http_Interface
{
    public function isReponseError ()
    {
        return $this->getLastResponse()->isError();         
    }

    public function getResponseStatus ()
    {
        return $this->getLastResponse()->getStatus();        
    }

    public function getResponseMessage ()
    {
        return $this->getLastResponse()->getMessage();
    }

    public function getResponseBody ()
    {
        return $this->getLastResponse()->getBody();
    }
    
    public function setAuth($user, $password = '', $type = null)
    {
        return parent::setAuth($user, $password = '', $type = self::AUTH_BASIC);
    }

}