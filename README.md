JirafeClient
============

PHP 5.2 client for [Jirafe](http://jirafe.com/) web analytics.

Usage
-----

``` php
<?php

require_once 'path/to/client/autoload.php';

// init API client
$client = new Jirafe_Client(YOUR_API_TOKEN);

// get visits report from site #12 in application #23
$reportHash = $client->applications(23)->sites(12)->visits()->fetch();

// get 'Jack' user information
$userHash = $client->users('Jack')->fetchValues();

```

Methods
-------

To see all available methods & their parameters, visit [Jirafe API Reference](http://api.jirafe.com/v1/docs).

Copyright
---------

JirafeClient Copyright (c) 2011 Jirafe <http://www.jirafe.com>.

