<?php

/*
 * This file is part of the Jirafe.
 * (c) Jirafe <http://www.jirafe.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Jirafe API report abstraction.
 *
 * @author knplabs.com
 */
abstract class Jirafe_AdminApi_Report extends Jirafe_AdminApi_Collection
{
    private $name;

    /**
     * Initializes report instance.
     *
     * @param   Jirafe_AdminApi_Resource_Site    $site   site object to report
     * @param   Jirafe_AdminApi_Client               $client API client
     */
    public function __construct(Jirafe_AdminApi_Resource_Site $site, Jirafe_AdminApi_Client $client)
    {
        parent::__construct($site, $client);
    }

    /**
     * Makes get reports to report.
     *
     * @param   string  $name   report name
     * @param   array   $query  report query string
     *
     * @return  Jirafe_HttpConnection_Response
     */
    public function doReportGet($name = null, array $query = array())
    {
        $this->name = $name;
        $response = $this->doGet($query);
        $this->name = null;

        return $response;
    }

    /**
     * Returns report collection name.
     *
     * @return  string
     */
    protected function getCollectionName()
    {
        if (null !== $this->name) {
            return sprintf(
                '%s/%s',
                parent::getCollectionName(),
                ltrim($this->name, '/')
            );
        }

        return parent::getCollectionName();
    }
}
