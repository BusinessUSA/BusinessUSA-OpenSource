<?php

    // PREREQUISITES - This page requires the user's ZipCode in order to function
    if ( empty($_GET['zip']) ) {
        drupal_goto('locate-my-closest-resource-centers');
        exit();
    }

    // PREREQUISITES - This page requires the given ZipCode to be valid
    $zip = intval($_GET['zip']);
    $locInfo = getLatLongFromZipCode($zip); // This variable will be used later in this script
    if ( $locInfo === false || !is_array($locInfo) || empty($locInfo['lat']) || empty($locInfo['lng']) ) {
        drupal_goto('locate-my-closest-resource-centers?badZip=' . $zip);
        exit();
    }

    $range = 50;
    if ( !empty($_GET['range']) ) {
        $range = intval($_GET['range']);
    }
    if ( !empty($_GET['resourcecenters-filter-rangearea-select']) ) {
        $range = intval($_GET['resourcecenters-filter-rangearea-select']);
    }
    if ( $range === 0 ) {
        $range = 50;
    }