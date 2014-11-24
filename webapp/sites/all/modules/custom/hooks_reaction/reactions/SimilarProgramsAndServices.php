<?php

$tracking_path = path_to_theme() . '/scripts/landing-page-tracking.js';
drupal_add_js($tracking_path);

function similar_programs_and_services($type = 'program') {
  $minimum_required_nodes = 5;
  $results = _similar_programs_and_services_by_tracking($type);
  if (count($results) < $minimum_required_nodes) {
    $results = _similar_programs_and_services_by_facet($type);
  }
  return $results ;
}

/**
 * array _similar_programs_and_services_by_tracking(mixed $type = 'program', int $similarToNid = -1, int $limitReturnCount = 5)
 * array similar_content_by_tracking() = alias-function
 *
 * Based on user-navigation tracking, this function returns nodes that are "related" to a given node.
 * The user-navigation tracking that effects the return of this functioned is powered by landing-page-tracking.js and 
 * landing-page-hits.page.php
 *
 * Returns an array of nodes, with the node-ids as the keys
 */
function similar_content_by_tracking() { return call_user_func_array('_similar_programs_and_services_by_tracking', func_get_args()); }
function _similar_programs_and_services_by_tracking($type = 'program', $similarToNid = -1, $limitReturnCount = 5) {
    
    $additionalJoins = '';
    $additionalWheres = '';
    
    // Get the node to search similarity by
    if ( $similarToNid === -1 ) {
        $thisLandingPageNode = menu_get_object();
        $thisLandingPageNodeId = $thisLandingPageNode->nid;
    } else {
        $thisLandingPageNodeId = $similarToNid;
    }
    
    // Handel contant-type filtering
    $typeStatement = '';
    if ( is_array($type) ) {
        if ( count($type) > 0 ) {
            $typeStatement = '';
            foreach ( $type as $index => $T ) {
                if ( $index !== 0 ) {
                    $typeStatement .= ' OR ';
                }
                $typeStatement .= " n.type = '{$type}' ";
            }
            $typeStatement = " AND ( {$typeStatement} ) ";
        }
    } else {
        if ( trim($type) !== '' ) {
            $typeStatement = "AND n.type = '{$type}'";
        }
    }
    
    // If we are looking for Events [only],  we shall alter the query to search for events which have NOT passed
    if ( $type === 'event' ) {
        $additionalJoins .= ' LEFT JOIN field_data_field_event_date ed ON ( n.nid = ed.entity_id ) ';
        $additionalWheres .= ' AND ed.field_event_date_value > NOW() ';
    }
    
    // Query the database
    $query = "
        SELECT
            L1.nodeId as 'assoc_with_nid',
            L2.nodeId as 'nid_associated',
            COUNT(L2.nodeId) as 'ranking'
        FROM landing_page_tracking L1
        LEFT JOIN landing_page_tracking L2 ON ( L1.ipaddress = L2.ipaddress )
        LEFT JOIN node n ON ( n.nid = L2.nodeId )
        {$additionalJoins}
        WHERE 
            L1.nodeId = {$thisLandingPageNodeId} 
            AND L2.nodeId <> {$thisLandingPageNodeId} 
            {$typeStatement}
            {$additionalWheres}
        GROUP BY L2.nodeId
        ORDER BY COUNT(L2.nodeId) DESC
        LIMIT {$limitReturnCount}
    ";

    // Return output
    $listCount = 0;
    $relatedNodes = array();
    foreach ( db_query($query) as $record ) {
        $nid = $record->nid_associated;
        $assocNode = node_load($nid);
        $type = $assocNode->type;
        if ( $assocNode !== false) {
            $relatedNodes[$nid] = $assocNode;
            $listCount++;
            if ( $listCount > 4 ) {
                break;
            }
        }
    }
    return $relatedNodes;
}

function _similar_programs_and_services_by_facet( $type = 'program') {
  $thisLandingPageNode = menu_get_object();
  $node_wrapper = entity_metadata_wrapper('node', $thisLandingPageNode);
  $owner_values =    $node_wrapper->field_program_owner_share->value();
  $industry_values = $node_wrapper->field_program_industry->value();
  $program_values =  $node_wrapper->field_program_needs->value();

  $query = db_select('node', 'n');
  $query->fields('n', array('nid'));
  $query->distinct();
  $query->leftJoin('field_data_field_program_owner_share', 'fpos', 'fpos.entity_id = n.nid');
  $query->leftJoin('field_data_field_program_industry', 'fpi', 'fpi.entity_id = n.nid');
  $query->leftJoin('field_data_field_program_needs', 'fpn', 'fpn.entity_id = n.nid');
  $query->condition('n.type', $type);
  $query->condition('n.nid', $node_wrapper->nid->value(), '<>');

  $faceted_or = db_or();
  if (!empty($owner_values)) {
    $faceted_or->condition('fpos.field_program_owner_share_value', $owner_values, 'IN');
  }
  if (!empty($industry_values)) {
    $faceted_or->condition('fpi.field_program_industry_value', $industry_values, 'IN');
  }
  if (!empty($program_values)) {
    $faceted_or->condition('fpn.field_program_needs_value', $program_values, 'IN');
  }

  if (!empty($owner_values) || !empty($industry_values) || !empty($program_values) ) {
    $query->condition($faceted_or);
  }

  $query->range(0, 5);
  $results = $query->execute();

  $related_nodes = array();
  foreach ($results as $row_result) {
    $related_nodes[] = node_load($row_result->nid);
  }
  return $related_nodes;
}

/**
 * array _similar_programs_and_services_by_tracking(array $types = array(), int $similarToNid = -1, int $limitReturnCount = 5)
 *
 * Based on the content's title, looks in Solr for "associated" content
 * Returns an array, with node-ids as the keys
 */
function similar_content_by_solr($types = array(), $similarToNid = -1, $limitReturnCount = 5) {
    
    $ret = array();
    
    // Obtain the target node
    if ( $similarToNid === -1 ) {
        $thisLandingPageNode = menu_get_object();
        $thisLandingPageNodeId = $thisLandingPageNode->nid;
    } else {
        $thisLandingPageNodeId = $similarToNid;
    }
    $thisLandingPageNode = node_load($thisLandingPageNodeId);
    
    // Get the URL to the Solr server (which is saved in settings within the Drupal database)
    $solrURL = db_query("SELECT url FROM apachesolr_environment LIMIT 1")->fetchField();
    if ( $solrURL === false ) {
        return array();
    }
    
    // Use the FQ (FilterQuery) to select target ContentTypes (bundles) from Solr
    if ( is_string($types) ) {
        $types = array($types);
    }
    $fq = '';
    foreach ( array_values($types) as $index => $type ) {
        if ( $index > 0 ) {
            $fq .= ' OR ';
        }
        $fq .= 'bundle:' . $type;
    }

    /* We shall search for each word in the node's title seperated; When the title is "how now brown cow", we search for: 
    "how" OR "now" OR "brown" OR "cow" */
    $searchQuery = '';
    $nodeTitleWords = explode(' ', $thisLandingPageNode->title);
    foreach ( $nodeTitleWords as $index => $word ) {
        if ( $index > 0 ) {
            $searchQuery .= "\" OR \"";
        }
        $searchQuery .= $word;
    }
    $searchQuery = "\"{$searchQuery}\"";
    
    // Query Solr, parse results
    $searchQuery = urlencode($searchQuery);
    $fq = urlencode($fq);
    $solrQueryURL = $solrURL . "/select?q={$searchQuery}&fq={$fq}&fl=entity_id%2C+bundle&wt=json&indent=false";
    error_log($solrQueryURL);
    $retJson = file_get_contents($solrQueryURL);
    if ( $retJson === false ) {
        error_log("Error - " . __FUNCTION__ . "() failed to obtain data from Solr, the target URL was: " . $solrQueryURL);
        return array();
    }
    $retData = json_decode($retJson, true);
    if ( $retData === false ) {
        error_log("Error - " . __FUNCTION__ . "() failed to parse data returned from Solr");
        return array();
    }
    if ( !isset($retData['response']) || !isset($retData['response']['docs']) || !is_array($retData['response']['docs']) ) {
        error_log("Error - " . __FUNCTION__ . "() received data from Solr in an unexpected structure");
        return array();
    }
    
    // Build an array of nodes to return based on Solr results
    $docs = $retData['response']['docs'];
    foreach ( $docs as $doc ) {
        if ( intval($thisLandingPageNodeId) !== intval($doc['entity_id']) ) {
            $nodeToRet = node_load($doc['entity_id']);
            if ( $nodeToRet === false ) {
                error_log("Error - " . __FUNCTION__ . "() Could not load node {$doc['entity_id']} which is a node-id refereced in the Solr index");
            } else {
                $ret[intval($doc['entity_id'])] = $nodeToRet;
            }
            if ( $limitReturnCount > 0 && count($ret) >= $limitReturnCount ) {
                break; // We have hit the limit of nodes to return
            }
        }
    }
    
    return $ret;
}









