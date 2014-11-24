<?php

/**
 * Implements hook_cron().
 * Hook to periodically trigger cleanup function(s)
 */
hooks_reaction_add("cron",
	function () {
        cleanup();
    }
);

/**
 * void cleanup()
 * Triggers functions to be fired on "clean up" cycle.
 */
function cleanup() {
    
    error_log("Cleanup cycle has startted (CleanUp.php)");
    cleanup_ExcelExportDir();
    cleanup_DeleteDuplicateEvents();
    cleanup_UnpublishPastEvents();
    cleanup_FixMissingLatLongForResourceCenters();
    export_gov_micro_page_cleanup();
    cleanup_FixBadTextFormatUsage();
}


/**
 * void cleanup_FixBadTextFormatUsage()
 * 
 * This function will search for fields that are using the php_code  
 * text-format that shouldn't. And set them to full_html
 *
 * Some fields should not be allowed to use the php_code text-
 * format because users under the Editor role are denied from 
 * touching any field where this format is set on.
 */
function cleanup_FixBadTextFormatUsage() {

    /* **************************************************
    DETAIL DESCRIPTION FOR PROGRAMS 
    ************************************************** */

    // First, get a list of Node-IDs that will be effected by this process
    $nids = db_query("SELECT entity_id, entity_id FROM field_data_field_program_detail_desc WHERE field_program_detail_desc_format = 'php_code' ")->fetchAllKeyed();
    $nids = array_values($nids);
    
    // Fix php_code usage on the field_data_field_program_detail_desc field
    $badFormatCount = count($nids);
    if ( $badFormatCount > 0 ) {
        $thisFunct = __FUNCTION__;
        error_log("{$thisFunct}() has found {$badFormatCount} programs that are using the php_code text-format, which will now be changed to full_html");
        dispatchEmails(
            array(
                'dfrey@reisystems.com',
                'jimmy.cretney@reisystems.com',
                'naga.tejaswini@reisystems.com'
            ),
            'no-reply@businessusa.gov', 
            'BUSA WARNING MESSAGE',
            "The {$thisFunct}() function has found <b>{$badFormatCount} programs</b> that are using the php_code text-format, "
                ."please note that we should not be using this text-format on fields that users under the Editor role may want "
                ."to access (<small>the Content-Management-Team does not, and cannot, have access to this Filter</small>). "
                ."Using this text-format disabels Editors from touching these field-values.<br/><br/>"
                ."All node->field_program_detail_desc text-formats will be set to full_html for the following node-ids:<br/>"
                ." - " . implode($nids, '<br/> - ')
        );
        db_query("
            UPDATE field_data_field_program_detail_desc 
            SET field_program_detail_desc_format = 'full_html'
            WHERE field_program_detail_desc_format = 'php_code'
        ");
    }
        
    /* **************************************************
    DETAIL DESCRIPTION FOR SERVICES 
    ************************************************** */

    // First, get a list of Node-IDs that will be effected by this process
    $nids = db_query("SELECT entity_id, entity_id FROM field_data_field_services_detail_desc WHERE field_services_detail_desc_format = 'php_code' ")->fetchAllKeyed();
    $nids = array_values($nids);
    
    // Fix php_code usage on the field_data_field_program_detail_desc field
    $badFormatCount = count($nids);
    if ( $badFormatCount > 0 ) {
        $thisFunct = __FUNCTION__;
        error_log("{$thisFunct}() has found {$badFormatCount} programs that are using the php_code text-format, which will now be changed to full_html");
        dispatchEmails(
            array(
                'dfrey@reisystems.com',
                'jimmy.cretney@reisystems.com',
                'naga.tejaswini@reisystems.com'
            ),
            'no-reply@businessusa.gov', 
            'BUSA WARNING MESSAGE',
            "The {$thisFunct}() function has found <b>{$badFormatCount} services</b> that are using the php_code text-format, "
                ."please note that we should not be using this text-format on fields that users under the Editor role may want "
                ."to access (<small>the Content-Management-Team does not, and cannot, have access to this Filter</small>). "
                ."Using this text-format disabels Editors from touching these field-values.<br/><br/>"
                ."All node->field_services_detail_desc text-formats will be set to full_html for the following node-ids:<br/>"
                ." - " . implode($nids, '<br/> - ')
        );
        db_query("
            UPDATE field_data_field_services_detail_desc 
            SET field_services_detail_desc_format = 'full_html'
            WHERE field_services_detail_desc_format = 'php_code'
        ");
    }


    /* **************************************************
    DETAIL DESCRIPTION FOR EVENTS
    ************************************************** */

    // First, get a list of Node-IDs that will be effected by this process
    $nids = db_query("SELECT entity_id, entity_id FROM field_data_field_event_detail_desc WHERE field_event_detail_desc_format = 'php_code' ")->fetchAllKeyed();
    $nids = array_values($nids);

    // Fix php_code usage on the field_data_field_event_detail_desc field
    $badFormatCount = count($nids);
    if ( $badFormatCount > 0 ) {
        $thisFunct = __FUNCTION__;
        error_log("{$thisFunct}() has found {$badFormatCount} events that are using the php_code text-format, which will now be changed to full_html");
        dispatchEmails(
            array(
                'dfrey@reisystems.com',
                'jimmy.cretney@reisystems.com',
                'naga.tejaswini@reisystems.com'
            ),
            'no-reply@businessusa.gov',
            'BUSA WARNING MESSAGE',
            "The {$thisFunct}() function has found <b>{$badFormatCount} events</b> that are using the php_code text-format, "
            ."please note that we should not be using this text-format on fields that users under the Editor role may want "
            ."to access (<small>the Content-Management-Team does not, and cannot, have access to this Filter</small>). "
            ."Using this text-format disabels Editors from touching these field-values.<br/><br/>"
            ."All node->field_event_detail_desc text-formats will be set to full_html for the following node-ids:<br/>"
            ." - " . implode($nids, '<br/> - ')
        );
        db_query("
            UPDATE field_data_field_event_detail_desc
            SET field_event_detail_desc_format = 'full_html'
            WHERE field_event_detail_desc_format = 'php_code'
        ");
    }

}

/**
 * void cleanup_FixMissingLatLongForResourceCenters()
 * 
 * This function will search for any nodes under the 'Resource Center' 
 * content-type that; has missing latitude/longitude information AND has 
 * a ZipCode value. 
 *
 * Content/nodes found that match this search will be supplied with 
 * lat/long information, the lat/long will be assumed based on the ZipCode.
 */
function cleanup_FixMissingLatLongForResourceCenters() {

    /* The following query should find all content under the  'Resource Centers' 
        content-type (appointment_office) that does NOT have latitude/longitude 
        information, but DOES have ZipCode information
    */
    $query = "
        SELECT
            n.nid AS 'nid',
            field_appoffice_lat_value,
            field_appoffice_long_value, 
            field_appoffice_postal_code_value
        FROM node n 
        LEFT JOIN field_data_field_appoffice_lat ln ON ( ln.entity_id = n.nid )
        LEFT JOIN field_data_field_appoffice_long lg ON ( lg.entity_id = n.nid )
        LEFT JOIN field_data_field_appoffice_postal_code pc ON ( pc.entity_id = n.nid )
        WHERE
            n.type = 'appointment_office'
            AND field_appoffice_postal_code_value IS NOT NULL
            AND (
                field_appoffice_lat_value IS NULL
                OR field_appoffice_long_value IS NULL
            )
    ";
    
    foreach ( db_query($query) as $nodeInfo ) {
        
        // Init
        $n = null;
        $lat = 0;
        $long = 0;
        
        // Load this node and node information
        $n = node_load($nodeInfo->nid);
        $zip = $n->field_appoffice_postal_code['und'][0]['value'];
        if ( intval($zip) === 0 ) {
            drupal_set_message(
                "Error - <a target=\"_blank\" href=\"/node/{$n->nid}/edit\">Node {$n->nid}</a> does not have a valid ZipCode.
                The ZipCode associated with this node is: <i>{$n->field_appoffice_postal_code['und'][0]['value']}</i>",
                "error"
            );
        }
        
        // Get the lant/long for the given ZipCode
        $locationInfo = getLatLongFromZipCode($zip); // Note: This function is stored in ZipCodeGeolocation.php
        $lat = $locationInfo['lat'];
        $long = $locationInfo['lng'];
        $city = $locationInfo['city'];
        $state = $locationInfo['state'];
        
        // Store this information into the instanced node (in memory)
        $n->field_appoffice_lat = array(
            'und' => array(
                0 => array(
                    'value' => $lat,
                    'format' => null,
                    'safe_value' => $lat,
                )
            )
        );
        $n->field_appoffice_long = array(
            'und' => array(
                0 => array(
                    'value' => $long,
                    'format' => null,
                    'safe_value' => $long,
                )
            )
        );
        if ( empty($n->field_appoffice_city['und'][0]['value']) ) {
            $n->field_appoffice_city = array(
                'und' => array(
                    0 => array(
                        'value' => $city,
                        'format' => null,
                        'safe_value' => $city,
                    )
                )
            );
        }
        if ( empty($n->field_appoffice_state['und'][0]['value']) ) {
            $n->field_appoffice_state = array(
                'und' => array(
                    0 => array(
                        'value' => $state,
                    )
                )
            );
        }
        
        // Save changes to this node
        node_save($n);
        
        // We are done now
        $msg = "Updated node {$n->nid} with missing latitude/longitude information<br/>\n";
        error_log($msg);
        unset($n);
    }
}

/**
 * void cleanup_UnpublishPastEvents()
 * 
 * Events which have ending dates that have pased shall be unpublished.
 */
function cleanup_UnpublishPastEvents() {

    $toReturn = array();
    error_log(basename(__FILE__) . '::' . __FUNCTION__ . ' has startted.');

    // Find nodes in the database which are Events, Published, and with a past ending date
    $results = db_query("
        SELECT nid, field_event_date_value2
        FROM node n 
        LEFT JOIN field_data_field_event_date ed ON (ed.entity_id = n.nid)
        WHERE 
            n.type='event' 
            AND n.status=1
            AND ed.field_event_date_value2 IS NOT NULL 
            AND DateDiff(ed.field_event_date_value2, NOW()) < 0
    ");
    
    // Unpublish these Events
    foreach ($results as $result) {
        if ( intval($result->nid) > 0 ) {
            set_time_limit(900); // Keep resetting the PHP's-death-timer so this script dosnt time out
            db_query("UPDATE node n SET n.status=0 WHERE n.nid=" . intval($result->nid));
            $toReturn[] = $result->nid;
            apachesolr_mark_node($result->nid); // Mark this node as needing to be indexed into Solr
        }
    }
    
    // Return the array of Node-IDs updated
    error_log(basename(__FILE__) . '::' . __FUNCTION__ . ' has finnished, ' . count($toReturn) . ' event nodes have been unpublished.');
    return $toReturn;
    
}

/**
 * void cleanup_ExcelExportDir()
 * Deletes old and uneeded files: sites/default/files/wizard-excel-exports/*.xls
 */
function cleanup_ExcelExportDir() {

    if ( is_dir('sites/default/files/wizard-excel-exports') ) {
    
        // Get a list of all files in this directory
        $files = scandir('sites/default/files/wizard-excel-exports');
        
        foreach ( $files as $file ) {
            if ( strpos($file, 'BusinessUSA-Wizard-Results-') === 0 ) {
            
                // Get the [unix] timestamp from the filename
                $timeStamp = str_replace('BusinessUSA-Wizard-Results-', '', $file);
                $timeStamp = str_replace('.xls', '', $timeStamp);
                $timeStamp = intval($timeStamp);
                
                // If the file (unix timestamp) is more than 12 hours old, delete the file
                $secondsIn12Hours = 43200;
                if ( $timeStamp + $secondsIn12Hours < time() ) {
                    unlink('sites/default/files/wizard-excel-exports/' . $file);
                    error_log("CleanUp.php::cleanup_ExcelExportDir() - Deleted {$file}");
                } else {
                    error_log("CleanUp.php::cleanup_ExcelExportDir() - Did not delete {$file}");
                }
            }
        }
        
    }
    
}

/**
 * array cleanup_DeleteDuplicateEvents()
 * Deletes duplicate Events - Detects duplicate events based on looking to a duplicate-combination of 
 * NodeTitle+EventStartDate+EventEndDate
 *
 * Returns an array of node IDs in which were delete.
 */
function cleanup_DeleteDuplicateEvents($printDebugReport = false) {
    
    $ret = array();
    
    $query= "
        SELECT 
            n1.title as 'Title', 
            field_event_date_value as 'StartDate', 
            field_event_date_value2 as 'EndDate',
            count(n1.title) as 'Count',
            GROUP_CONCAT(n1.nid) as 'nodeIds'
        FROM  node n1
        LEFT JOIN field_data_field_event_date d1 ON d1.entity_id = n1.nid
        WHERE n1.type='event' AND n1.status=1
        GROUP BY n1.title, field_event_date_value, field_event_date_value2
        HAVING count(n1.title) > 1
    ";
    
    $results = db_query($query);
    foreach ( $results as $result ) {
        $duplicateNids = explode(',', $result->nodeIds);
        foreach ( $duplicateNids as $index => $dupNodeId ) {
            if ( $index === 0 ) {
                // This is the first Event-Node of its kind, dont delete this one
                $firstEventOfItsKind = $dupNodeId;
            } else {
                // This is not the first Event-Node of its kind, delete this one
                $ret[] = $dupNodeId;
                node_delete($dupNodeId);
                $msg = __FILE__ . '::' . __FUNCTION__ . " - Deleted Event node {$dupNodeId} because it is a duplicate of node {$firstEventOfItsKind}. StartDate={$result->StartDate},EndDate={$result->EndDate},Title={$result->Title}";
                error_log($msg);
                if ( $printDebugReport ) {
                    drupal_set_message($msg, 'warning');
                }
            }
        }
    }
    
    return $ret;
}

/**
 * Deletes contents that don't have titles, "Document Moved", "Export.gov Page Not Found" and "The Web site cannot be found".
 * These contents are scrapped from export.gov and should not be indexed in solr search engine,
 * Unless otherwise they are properly rendering searched contents.
 */
function export_gov_micro_page_cleanup() {
  $query = db_select('node', 'n');
  $query->join('field_data_field_exportrip_origin', 'origin', 'n.nid = origin.entity_id');
  $query->fields('n', array('nid', 'title')) 
        ->condition('n.type', 'export_gov_micro_site_page', '=')
        ->condition(db_and()
          ->condition(db_or()
            ->condition('n.status', 0, '=')
            ->condition('n.status', 1, '=')
           )
         )
        ->condition(db_and()
          ->condition(db_or()
            ->condition('n.title', '', '=')
            ->condition('n.title', ' ', '=')
            ->condition('n.title', NULL, '=')
            ->condition('n.title', 'Export.gov Page Not Found', '=')
            ->condition('n.title', 'Document Moved', '=')
            ->condition('n.title', 'The Web site cannot be found', '=')
           )
         )
        ->range(0,100);
  $result = $query->execute();
  $results = $result->fetchAll();
  
  $w = 0;
  $x = 0; 
  $y = 0; 
  $z = 0;
  foreach ($results as $record) {
      if($record->title == '' || $record->title == ' ' || $record->title == NULL) {
        $a[] = $record->nid;
        $x++;
      } 
      elseif($record->title == 'Export.gov Page Not Found') {
        $b[] = $record->nid;
        $y++;
      } 
      elseif($record->title == 'Document Moved') {
        $c[] = $record->nid;
        $z++;
      }
      elseif($record->title == 'The Web site cannot be found') {
        $d[] = $record->nid;
        $w++;
      }
  }
  
  drupal_set_message(t('Total # of contents to be deleted: %count', array('%count' => count($results))), 'warning');
  
  if(!empty($results)){
    if(count($a) > 0){
      node_delete_multiple($a);
      drupal_set_message(t('Deleted %count contents that don\'t have titles.', array('%count' => count($a))), 'warning');
    }
    if(count($b) > 0) {
      node_delete_multiple($b);
      drupal_set_message(t('Deleted %count content that says "Export.gov Page Not Found".', array('%count' => count($b))), 'warning');
    } 
    if(count($c) > 0) {
      node_delete_multiple($c);
      drupal_set_message(t('Deleted %count content that has "Document Moved".', array('%count' => count($c))), 'warning');
    }
    if(count($d) > 0) {
      node_delete_multiple($d);
      drupal_set_message(t('Deleted %count content that has "The Web site cannot be found".', array('%count' => count($d))), 'warning');
    } 
  } else {
    drupal_set_message(t('Contents found in DB for deletion: %count', array('%count' => count($w + $x + $y + $z) - 1)), 'warning');
  }
}

//Code for deleting duplicate from solicitations 50 at a time.
//Note: Create a cron to do it frequently. or can add in the function cleanup() on top of this page to run it once every day.
function cleanup_DeleteDuplicateSolicitations($printDebugReport = false, $param = 50) {

    $ret = array();

    $query= "
        SELECT n1.title as 'Title',
        count(n1.title) as 'Count',
        GROUP_CONCAT(n1.nid) as 'nodeIds'
        FROM  node n1
        WHERE n1.type='solicitations' AND n1.status=1
        GROUP BY n1.title
        HAVING count(n1.title) > 1 LIMIT ".$param.";";

    $results = db_query($query);
    foreach ( $results as $result ) {
        $duplicateNids = explode(',', $result->nodeIds);
        foreach ( $duplicateNids as $index => $dupNodeId ) {
            if ( $index === 0 ) {
                // This is the first Node of its kind, do not delete this one
                $firstEventOfItsKind = $dupNodeId;
            } else {
                // This is not the first Node of its kind, delete this one
                $ret[] = $dupNodeId;
                node_delete($dupNodeId);
                $msg = __FILE__ . '::' . __FUNCTION__ . " - Deleted Solicitation node {$dupNodeId} because it is a duplicate of node {$firstEventOfItsKind}. Title={$result->Title}";
                error_log($msg);
                if ( $printDebugReport ) {
                    drupal_set_message($msg, 'warning');
                }
            }
        }
    }

    return $ret;
}

//Code for deleting duplicate from Licences And Permits 50 at a time.
//Note: Create a cron to do it frequently. or can add in the function cleanup() on top of this page to run it once every day.
function cleanup_DeleteDuplicateLicenceAndPermits($printDebugReport = false,$param = 50) {

    $ret = array();

    $query= "
        SELECT n1.title as 'Title',
        count(n1.title) as 'Count',
        GROUP_CONCAT(n1.nid) as 'nodeIds'
        FROM  node n1
        WHERE n1.type='LICENSES_AND_PERMITS' AND n1.status=1
        GROUP BY n1.title
        HAVING count(n1.title) > 1 LIMIT ".$param.";";

    $results = db_query($query);
    foreach ( $results as $result ) {
        $duplicateNids = explode(',', $result->nodeIds);
        foreach ( $duplicateNids as $index => $dupNodeId ) {
            if ( $index === 0 ) {
                // This is the first Node of its kind, do not delete this one
                $firstEventOfItsKind = $dupNodeId;
            } else {
                // This is not the first Node of its kind, delete this one
                $ret[] = $dupNodeId;
                node_delete($dupNodeId);
                $msg = __FILE__ . '::' . __FUNCTION__ . " - Deleted Licences And Permits node {$dupNodeId} because it is a duplicate of node {$firstEventOfItsKind}. Title={$result->Title}";
                error_log($msg);
                if ( $printDebugReport ) {
                    drupal_set_message($msg, 'warning');
                }
            }
        }
    }

    return $ret;
}


//Code for deleting duplicate from Export Articles 50 at a time.
//Note: Create a cron to do it frequently. or can add in the function cleanup() on top of this page to run it once every day.
function cleanup_DeleteDuplicateExportArticles($printDebugReport = false, $param = 50) {

    $ret = array();

    $query= "
        SELECT n1.title as 'Title',
        count(n1.title) as 'Count',
        GROUP_CONCAT(n1.nid) as 'nodeIds'
        FROM  node n1
        WHERE n1.type='export_gov_micro_site_page' AND n1.status=1
        GROUP BY n1.title
        HAVING count(n1.title) > 1 LIMIT ".$param.";";

    $results = db_query($query);
    foreach ( $results as $result ) {
        $duplicateNids = explode(',', $result->nodeIds);
        foreach ( $duplicateNids as $index => $dupNodeId ) {
            if ( $index === 0 ) {
                // This is the first Node of its kind, do not delete this one
                $firstEventOfItsKind = $dupNodeId;
            } else {
                // This is not the first Node of its kind, delete this one
                $ret[] = $dupNodeId;
                node_delete($dupNodeId);
                $msg = __FILE__ . '::' . __FUNCTION__ . " - Deleted Export Articles node {$dupNodeId} because it is a duplicate of node {$firstEventOfItsKind}. Title={$result->Title}";
                error_log($msg);
                if ( $printDebugReport ) {
                    drupal_set_message($msg, 'warning');
                }
            }
        }
    }

    return $ret;
}
