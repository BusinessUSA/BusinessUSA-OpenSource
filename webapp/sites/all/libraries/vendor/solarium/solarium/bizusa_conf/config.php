<?php

include('sites/default/settings.php');
ini_set('error_reporting', E_ALL);
$link = mysql_connect($databases['default']['default']['host'], $databases['default']['default']['username'], $databases['default']['default']['password']);
$db_selected = mysql_select_db($databases['default']['default']['database'], $link);

if (!$link) {
    die('Could not connect: ' . mysql_error());
}
//echo 'Connected successfully';

$solrServer = mysql_query('SELECT url FROM apachesolr_environment LIMIT 1');
$solrServer = mysql_fetch_assoc($solrServer);
$solrServer = str_replace('http://', '', $solrServer['url']);
$path = substr($solrServer, strpos($solrServer, '/')) . '/';
$port = substr($solrServer, strpos($solrServer, ':') + 1);
$port = substr($port, 0, strpos($port, '/'));
$solrServer = substr($solrServer, 0, strpos($solrServer, ':'));
$config = array(
    'endpoint' => array(
        'localhost' => array(
            'host' => $solrServer,
            'port' => $port,
            'path' => $path,
        )
    )
);
