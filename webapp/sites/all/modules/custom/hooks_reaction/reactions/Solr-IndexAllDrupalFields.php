<?php

/**  implements hook apachesolr_index_documents_alter
  *  
  *  This (sub)module controles what Drupal-fields are indexed into Solr. By default, ALL fields
  *  will be indexed, and certain select fields will not (this decision is made in getDrupalNodeFieldsToAddToSolrDocument).
  */
hooks_reaction_add("apachesolr_index_documents_alter",
	function (array &$documents, $entity, $entity_type, $env_id) {
        
        // Get an array of all the fields (and values) we should add to Solr-index for this document (based on Drupal fields)
        $fieldsToAdd = getDrupalNodeFieldsToAddToSolrDocument($entity);
        
        // Add this data to the Solr-Document
        foreach ( $fieldsToAdd as $fieldNameToAdd => $fieldValueToAdd ) {
            foreach ( $documents as &$document ) {
                $document->addField($fieldNameToAdd, $fieldValueToAdd, false);
            }
        }

        /* We shall not index the following fields in Solr (for the given content-types) */
        $noIndexMsg = 'ThisFiledIsNotToBeIndexedForThisContentType';
        if ( $entity->type === 'swim_lane_page' ) {
            $document->setField('content', $noIndexMsg);
            $document->setField('teaser', $noIndexMsg);
            $document->setField('spell', $noIndexMsg);
            $document->setField('ts_custom_content', $noIndexMsg);
            $document->setField('ts_field_search_snippet_override', $noIndexMsg);
            $document->setField('ts_field_search_icon_override', $noIndexMsg);
            $document->setField('sm_field_program_wizard_type', $noIndexMsg);
            $document->setField('ts_field_program_wizard_type', $noIndexMsg);
            $document->setField('ts_wizard_indexable_text', $entity->body['und'][0]['value'] );
        }
        
        if ( strpos(request_uri(), '-DEBUG-HOOK-APACHESOLR-INDEX-DOCUMENTS-ALTER-') !== false ) {
            $debugMsg = array(
                'datetime' => date('U'),
                'Function' => 'hook_apachesolr_index_documents_alter',
                'Arguments' => array(
                    'documents' => $documents,
                    'entity' => $entity,
                    'env_id' => $env_id
                )
            );
        }
    }
);

function getDrupalNodeFieldsToAddToSolrDocument($entity) {
    
    $ret = array(
        'ts_custom_content' => ''
    );
    
    $nodeProperties = (array) $entity;
    foreach ( $nodeProperties as $nodePropertyName => $nodePropertyValueArray ) {
        
        if ( strpos($nodePropertyName, 'field_') !== false ) {
            
            $nodePropertyActualValues = array();
            
            // Loop through all languages and all value(s) looking for the actual value as it is stored in the Drupal node object
            foreach ( $nodePropertyValueArray as $lang => $nodePropertyValueLanguageArray ) { // for each item, (i.e. "und") in $node->field_name['und']
                foreach ( $nodePropertyValueLanguageArray as $entryId => $nodePropertyValueLanguageEntryArray ) { // for each item, (i.e. 0) in $node->field_name['und'][0]
            
                    if ( !empty($nodePropertyValueLanguageEntryArray['value']) ) { // value
                        $nodePropertyActualValues[] = $nodePropertyValueLanguageEntryArray['value'];
                    } elseif ( !empty($nodePropertyValueLanguageEntryArray['safe_value']) ) { // safe_value
                        $nodePropertyActualValues[] = $nodePropertyValueLanguageEntryArray['safe_value'];
                    } elseif ( !empty($nodePropertyValueLanguageEntryArray['filename']) ) { // filename
                        $nodePropertyActualValues[] = $nodePropertyValueLanguageEntryArray['filename'];
                    } elseif ( !empty($nodePropertyValueLanguageEntryArray['title']) ) { // title
                        $nodePropertyActualValues[] = $nodePropertyValueLanguageEntryArray['title'];
                    } elseif ( !empty($nodePropertyValueLanguageEntryArray['url']) ) { // url
                        $nodePropertyActualValues[] = $nodePropertyValueLanguageEntryArray['url'];
                    }
                }
            }
            
            if ( count($nodePropertyActualValues) === 0 ) {
                $nodePropertyActualValues = array('-NO-DATA-');
            } else {
                
                // Map (copy) all fields into a field called ts_custom_content
                $ret['ts_custom_content'] .= 'ts_' . $nodePropertyName . ': ' . implode(' -- ', $nodePropertyActualValues) . ' ----- ';
                
                /* Map (copy) all agency fields into a fields called ts_joined_agency */
                $agencyFields = array('field_agency_solr_sorting', 'agency', 'field_agencies', 'field_event_agency', 'field_forms_agency', 'field_fr_preamb_agency_new', 'field_grants_agency_solr_sorting', 'field_presol_agency', 'field_presol_agency_new', 'field_program_agency', 'field_rules_agency_acronym', 'field_rules_agency_name', 'field_services_agency', 'field_tools_agency', 'field_training_agency', 'field_video_agency');
                if ( in_array($nodePropertyName, $agencyFields) ) {
                
                    // Initialize this element in the $ret array if it has not been done so already
                    if ( !isset($ret['ts_joined_agency']) ) {
                        $ret['ts_joined_agency'] = '';
                    }
                    
                    // Convert all key-values in $nodePropertyActualValues to their label-(actual)-value.
                    $fieldInfo = field_info_field($nodePropertyName); // Get information about this field
                    if ( isset($fieldInfo['settings']['allowed_values']) ) { // if this field has a list of allowed values...
                        $allowedValuesMap = $fieldInfo['settings']['allowed_values']; // Get an array of = array( 'Acronym' => 'Actual Label', [...] )
                        foreach ( $nodePropertyActualValues as &$nodePropertyActualValue ) {
                            if ( !empty($allowedValuesMap[$nodePropertyActualValue]) ) { // If the given key (acronym) exists in the Allowed Values list...
                                $nodePropertyActualValue = $allowedValuesMap[$nodePropertyActualValue]; // ...then set it to the actual-label-value
                            }
                        }
                    }
                    
                    $ret['ts_joined_agency'] .= trim(', ' . implode(', ', $nodePropertyActualValues));
                    $ret['ts_joined_agency'] = trim($ret['ts_joined_agency'], ',');
                    $ret['ts_joined_agency'] = trim($ret['ts_joined_agency']);
                }
                
                /* Map (copy) all state fields into a fields called ts_joined_state */
                $stateFields = array('field_event_state', 'field_program_loc', 'field_state_resource_state');
                if ( in_array($nodePropertyName, $stateFields) ) {
                    // We assume that only ONE of the state fields (field_event_state, field_program_loc, field_state_resource_state) will be associated with this node
                    $stateAcronym = trim(implode(' ', $nodePropertyActualValues));
                    $stateAcronym = stateNameToAcronym($stateAcronym, $stateAcronym);
                    $ret['ts_joined_state'] = $stateAcronym;
                }
                
            }
            
            // Map (copy) field_event_type to "sm_field_event_type" in Solr - For some reason we need this both as ts_field_event_type AND sm_field_event_type
            if ( $nodePropertyName === 'field_event_type' ) {
                $ret['sm_field_event_type'] = implode(' -- ', $nodePropertyActualValues);
            }
            
            $ret['ts_' . $nodePropertyName] = implode(' -- ', $nodePropertyActualValues);
        }
        
    }
    
    $ret['ss_is_wizard_zero_one'] = strval( intval( $entity->type === 'swim_lane_page' ) );
    $ret['ss_is_wizard_tf'] = ( $entity->type === 'swim_lane_page' ? 'true' : 'false' ) ;
    if ( !empty($ret['ts_field_event_date']) && trim($ret['ts_field_event_date']) !== '-NO-DATA-' ) {
        $ret['ds_field_event_date'] = str_replace(' ', 'T', $ret['ts_field_event_date']) . "Z";
    } else {
        $ret['ts_field_event_date'] = '';
    }

    return $ret;
}









