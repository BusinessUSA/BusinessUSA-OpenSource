<?php
    db_query("
        CREATE TABLE IF NOT EXISTS landing_page_tracking
        (unixtime TEXT, ipaddress TEXT, nodeId TEXT)
    ");

    if ( empty($_GET['nids']) ) {
        print json_encode(false);
    }

    $nodeIDs = $_GET['nids'];

    // $nodeIDs should be an array of Node-IDs

    foreach ( $nodeIDs as $nodeId ) {

        db_insert('landing_page_tracking')->fields(
            array(
                'unixtime' => time(), /* The time() function gets the current unix time as an integer */
                'ipaddress' => $_SERVER['REMOTE_ADDR'],
                'nodeId' => $nodeId
            )
        )->execute();

    }

    print json_encode($nodeIDs);
    drupal_exit();
?>
