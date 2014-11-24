<?php

/*
    This script provides backwards compatability with some old Solr functionality.
    
    i.e. - The function apachesolr_mark_node() has been removed from the ApacheSolr Drupal module and 
    replaced with apachesolr_mark_entity()
    While this has been done in the ApacheSolr module, not all other modules may be aware of this...
*/

// The function apachesolr_mark_node() has been replaced with apachesolr_mark_entity()
if ( !function_exists('apachesolr_mark_node') ) {

    function apachesolr_mark_node($nid) {
        if ( function_exists('apachesolr_mark_entity') ) {
            
            // Bug killer - Check if the target nodeId exists in the apachesolr_index_entities_node table...
            $nodeExistsInIndexTbl = false;
            $query = "SELECT count(entity_id) AS 'count' FROM apachesolr_index_entities_node WHERE entity_id={$nid} ";
            $results = db_query($query);
            foreach ( $results as $record ) {
                if ( intval($record->count) > 0 ) {
                    $nodeExistsInIndexTbl = true;
                }
                break;
            }
            
            // Bug killer - If the target nodeId does not exist in the apachesolr_index_entities_node table, insert it
            if ( $nodeExistsInIndexTbl === false ) {
                
                $results = db_query("SELECT nid, type, status, changed FROM node WHERE nid={$nid} LIMIT 1");
                foreach ( $results as $record ) {
                    $nodeInfo = $record;
                }
                
                if ( !empty($nodeInfo) ) {
                    db_insert('apachesolr_index_entities_node')->fields(
                        array(
                          'entity_type' => 'node',
                          'entity_id' => $nid,
                          'bundle' => $nodeInfo->type,
                          'status' => $nodeInfo->status,
                          'changed' => $nodeInfo->changed,
                        )
                    )->execute();
                }
            }
            
            apachesolr_mark_entity('node', $nid);
        }
    }
    
}