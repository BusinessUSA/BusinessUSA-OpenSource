<?php  /*

    [--] NOTICE [--]
    
    The purpose of this script is to detect when Events are created/updated with no latitude/longitude
    information, but have a [valid] address associated. In this scenario, the lat/long values should be 
    automatically populated.
*/

/** 
 * Implements hook_node_presave() 
 * For mode documentation on this hook, refer to: 
 *     https://api.drupal.org/api/drupal/modules%21node%21node.api.php/function/hook_node_presave/7
 */
hooks_reaction_add("node_presave",
	function ($node) {
        if ( $node->type === 'event' ) {
            event_check_latlong($node, false); // $node is passed byref here and updated as needed
        }
    }
);

/**
 * object event_check_latlong(object $node);
 * object event_check_latlong(int $nid, bool $doNodeSave);
 *
 * Given a node or node-id, will check to see if the given node has latitude/longitude information, if 
 * it does not, and has a [valid] address associated, the lat/long values will be populated.
 */
function event_check_latlong($node, $doNodeSave = false) {
    
    $thisFunct = __FUNCTION__;
    
    // If we are given a node-id instead of a node object, node the node
    if ( !is_object($node) && is_numeric($node) ) {
        $node = node_load($node);
        $doNodeSave = true;
    }
    
    // Validation, the given node must be under the Event content-type
    if ( $node->type !== 'event' ) {
        return $node;
    }
    
    // Detect if the latitude is missing
    $isMissingLatLong = false;
    if ( empty($node->field_event_latitude) ) {
        $isMissingLatLong = true;
    } else {
        if ( empty($node->field_event_latitude['und']) || !is_array($node->field_event_latitude['und']) || count($node->field_event_latitude['und']) === 0 ) {
            $isMissingLatLong = true;
        } else {
            if ( empty($node->field_event_latitude['und'][0]) || !is_array($node->field_event_latitude['und'][0]) || count($node->field_event_latitude['und'][0]) === 0 ) {
                $isMissingLatLong = true;
            } else {
                if ( empty($node->field_event_latitude['und'][0]['value']) || intval($node->field_event_latitude['und'][0]['value']) === 0 ) {
                    $isMissingLatLong = true;
                } else {
                    // then this node has a latitude value
                }
            }
        }
    }
    
    // Detect if the longitude is missing
    $isMissingLatLong = false;
    if ( empty($node->field_event_longitude) ) {
        $isMissingLatLong = true;
    } else {
        if ( empty($node->field_event_longitude['und']) || !is_array($node->field_event_longitude['und']) || count($node->field_event_longitude['und']) === 0 ) {
            $isMissingLatLong = true;
        } else {
            if ( empty($node->field_event_longitude['und'][0]) || !is_array($node->field_event_longitude['und'][0]) || count($node->field_event_longitude['und'][0]) === 0 ) {
                $isMissingLatLong = true;
            } else {
                if ( empty($node->field_event_longitude['und'][0]['value']) || intval($node->field_event_longitude['und'][0]['value']) === 0 ) {
                    $isMissingLatLong = true;
                } else {
                    // then this node has a longitude value
                }
            }
        }
    }

    if ( $isMissingLatLong ) {
        
        $address = '';
        if ( !empty($node->field_event_address_1['und'][0]['value']) ) {
            $address .= $node->field_event_address_1['und'][0]['value'];
        }
        if ( !empty($node->field_event_address_2['und'][0]['value']) ) {
            if ( $address !== '' ) {
                $address .= ', ';
            }
            $address .= $node->field_event_address_2['und'][0]['value'];
        }
        if ( !empty($node->field_event_city['und'][0]['value']) ) {
            if ( $address !== '' ) {
                $address .= ', ';
            }
            $address .= $node->field_event_city['und'][0]['value'];
        }
        if ( !empty($node->field_event_state['und'][0]['value']) ) {
            if ( $address !== '' ) {
                $address .= ', ';
            }
            $address .= $node->field_event_state['und'][0]['value'];
        }
        if ( !empty($node->field_event_zip['und'][0]['value']) ) {
            if ( $address !== '' ) {
                $address .= ' ';
            }
            $address .= $node->field_event_zip['und'][0]['value'];
        }
        
        if ( trim($address) === '' || empty($node->field_event_city['und'][0]['value']) ) {
            drupal_set_message('Notice: This even will not be able to shown on a map or included in proximity-searches because no address information was supplied.', 'warning');
            error_log("{$thisFunct}() could not even try to auto-populated the latitude/longitude values for the event {$node->nid} (\"{$node->title}\") because there was no address information given");
            return $node;
        }
        
        $locInfo = getLatLongFromAddress($address); // This function is defined in ZipCodeGeolocation.php
        if ( empty($locInfo['lat']) || empty($locInfo['lng']) ) {
            drupal_set_message("Error - Could not geolocate the address: \"{$address}\", is this a valid address?", 'error');
            error_log("{$thisFunct}() failed to auto-populated the latitude/longitude values for event-node {$node->nid} (\"{$node->title}\"). The given address was {$address}.");
            return $node;
        } else {
            $node->field_event_latitude = array(
                'und' => array(
                    0 => array(
                        'value' => strval($locInfo['lat']),
                        'format' => '',
                        'safe_value' => strval($locInfo['lat']),
                    )
                )
            );
            $node->field_event_longitude = array(
                'und' => array(
                    0 => array(
                        'value' => strval($locInfo['lng']),
                        'format' => '',
                        'safe_value' => strval($locInfo['lng']),
                    )
                )
            );
            error_log("{$thisFunct}() has auto-populated the latitude/longitude values for event-node {$node->nid} (\"{$node->title}\") ");
        }
        
    }
    
    if ( $doNodeSave ) {
        node_save($node);
    }
    
    return $node;
}









