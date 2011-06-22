<?php

/*
 * This file is part of the Jirafe.
 * (c) Jirafe <http://www.jirafe.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Jirafe API client.
 *
 * @author knplabs.com
 */
class Jirafe_Client implements Jirafe_HttpConnection_Interface
{
    private $token;
    private $connection;

    /**
     * Initializes client.
     *
     * @param   string                          $token      API access token
     * @param   Jirafe_HttpConnection_Interface $connection optional connection instance
     */
    public function __construct($token, Jirafe_HttpConnection_Interface $connection = null)
    {
        $this->token = $token;

        if (null === $connection) {
            $connection = new Jirafe_HttpConnection_Curl('https://api.jirafe.com/v1');
        }

        $this->connection = $connection;
    }

    /**
     * Returns currently used token.
     *
     * @return  string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Returns applications collection or single application if you provide ID.
     *
     * @param   integer $id application id. If specified - method will return object instead of
     *                      collection
     * @return  Jirafe_Api_Collection_Applications|Jirafe_Api_Resource_Application
     */
    public function applications($id = null)
    {
        $applications = new Jirafe_Api_Collection_Applications($this);

        if (null !== $id) {
            return $applications->get($id);
        }

        return $applications;
    }

    /**
     * Returns users collection or single user if you provide ID.
     *
     * @param   integer $id user id. If specified - method will return object instead of
     *                      collection
     * @return  Jirafe_Api_Collection_Users|Jirafe_Api_Resource_User
     */
    public function users($id = null)
    {
        $users = new Jirafe_Api_Collection_Users($this);

        if (null !== $id) {
            return $users->get($id);
        }

        return $users;
    }

    /**
     * @see Jirafe_HttpConnection_Interface::get()
     */
    public function get($path, array $query = array())
    {
        $query += array('token' => $this->token);

        return $this->connection->get($path, $query);
    }

    /**
     * @see Jirafe_HttpConnection_Interface::head()
     */
    public function head($path, array $query = array())
    {
        $query += array('token' => $this->token);

        return $this->connection->head($path, $query);
    }

    /**
     * @see Jirafe_HttpConnection_Interface::post()
     */
    public function post($path, array $query = array(), array $parameters = array())
    {
        $query += array('token' => $this->token);

        return $this->connection->post($path, $query, $parameters);
    }

    /**
     * @see Jirafe_HttpConnection_Interface::put()
     */
    public function put($path, array $query = array(), array $parameters = array())
    {
        $query += array('token' => $this->token);

        return $this->connection->put($path, $query, $parameters);
    }

    /**
     * @see Jirafe_HttpConnection_Interface::delete()
     */
    public function delete($path, array $query = array(), array $parameters = array())
    {
        $query += array('token' => $this->token);

        return $this->connection->delete($path, $query, $parameters);
    }
}
