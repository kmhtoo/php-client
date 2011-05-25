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


interface Jirafe_Http_Interface
{
    public function setUri($uri);
    public function setParameterGet($param, $value = null);
    public function setParameterPost($param, $value = null); 
    public function setAuth($user, $pwd ='',$type = null);
    public function isReponseError();
    public function getResponseStatus();
    public function getResponseMessage();
    public function getResponseBody();
    
}