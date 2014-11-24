<?php

function assignNodeFieldValuesAsApprovedTagsForAllPrograms() {
    
    $results = db_query("SELECT nid FROM node WHERE type='program' ");
    foreach ( $results as $record ) {
        error_log("Triggering assignNodeFieldValuesAsApprovedTags({$record->nid}) ");
        assignNodeFieldValuesAsApprovedTags( $record->nid );
    }
    
}

function assignNodeFieldValuesAsApprovedTagsForAllServices() {
    
    $results = db_query("SELECT nid FROM node WHERE type='services' ");
    foreach ( $results as $record ) {
        error_log("Triggering assignNodeFieldValuesAsApprovedTags({$record->nid}) ");
        assignNodeFieldValuesAsApprovedTags( $record->nid );
    }
    
}

function assignNodeFieldValuesAsApprovedTags($nodID) {
    
    $n = node_load($nodID);
    $nodeHasBeenChanged = false;
    $tagLabelsToAssign = array();
    
    // Check the Ownership field for this program/service
    if ( !empty($n->field_program_owner_share['und']) ) {
        foreach ( $n->field_program_owner_share['und'] as $fieldValueInfo ) {
            $fieldValue = $fieldValueInfo['value'];
            $tagLabelsToAssign[] = $fieldValue;
        }
    }

    // Check the Need field for this program/service
    if ( !empty($n->field_program_needs['und']) ) {
        foreach ( $n->field_program_needs['und'] as $fieldValueInfo ) {
            $fieldValue = $fieldValueInfo['value'];
            $tagLabelsToAssign[] = $fieldValue;
        }
    }
    
    // Check the Company Maturity field for this program/service
    if ( !empty($n->field_program_comp_maturity['und']) ) {
        foreach ( $n->field_program_comp_maturity['und'] as $fieldValueInfo ) {
            $fieldValue = $fieldValueInfo['value'];
            $tagLabelsToAssign[] = $fieldValue;
        }
    }
    
    // Check the Exporting Maturity field for this program/service
    if ( !empty($n->field_program_exporting_maturity['und']) ) {
        foreach ( $n->field_program_exporting_maturity['und'] as $fieldValueInfo ) {
            $fieldValue = $fieldValueInfo['value'];
            $tagLabelsToAssign[] = $fieldValue;
        }
    }
    
    // Check the Company Size field for this program/service
    if ( !empty($n->field_program_company_size['und']) ) {
        foreach ( $n->field_program_company_size['und'] as $fieldValueInfo ) {
            $fieldValue = $fieldValueInfo['value'];
            $tagLabelsToAssign[] = $fieldValue;
        }
    }
    
    // Get the vocabulary ID for User Submittted Tags
    $vid = createVocabIfNotExist('User Submittted Tags', 'user_submittted_tags'); // createVocabIfNotExist() returns the vid (Vocab ID)

    // Create the parent term.
    $parent = 'Assumed From Field Values';
    $termAlreadyExistsWithTidOf = db_query("SELECT tid FROM taxonomy_term_data WHERE name='{$parent}' LIMIT 1")->fetchField();
    if ( $termAlreadyExistsWithTidOf === false ) {
        taxonomy_term_save(
            (object) array(
                'name' => $parent,
                'vid' => $vid,
            )
        ); // [!!] WARNING [!!] - taxonomy_term_save() WILL create duplicate taxonomy terms, ALWAYS check if a taxonomy terms with the same titles exists before calling this fucntion
    }
    
    // Get the parent-term's ID.
    $parentTermID = db_query("SELECT tid FROM taxonomy_term_data WHERE name='{$parent}' LIMIT 1")->fetchField();

    // Create all terms
    foreach ( $tagLabelsToAssign as $term ) {
        
        // Create the child term.
        $termAlreadyExistsWithTidOf = db_query("SELECT tid FROM taxonomy_term_data WHERE name='{$term}' LIMIT 1")->fetchField();
        if ( $termAlreadyExistsWithTidOf === false ) {
            taxonomy_term_save(
                (object) array(
                    'name' => $term,
                    'vid' => $vid,
                    'parent' => array($parentTermID),
                )
            ); // [!!] WARNING [!!] - taxonomy_term_save() WILL create duplicate taxonomy terms, ALWAYS check if a taxonomy terms with the same titles exists before calling this fucntion
        }
        
        // Get the child term's ID
        $termID = db_query("SELECT tid FROM taxonomy_term_data WHERE name='{$term}' LIMIT 1")->fetchField();
        
        // Ensure there is an array for the "Approved Tags" field in this node
        if ( empty($n->field_tagged_terms) ) {
            $n->field_tagged_terms = array(
                'und' => array()
            );
        }
        
        // Assign this $termID under the "Approved Tags" field in this node
        $thisNodeAlreadyHasThisTerm = false;
        foreach ( $n->field_tagged_terms['und'] as $alreadyExistingTermInfo ) {
            $alreadyExistingTermID = $alreadyExistingTermInfo['tid'];
            if ( intval($alreadyExistingTermID) === intval($termID) ) {
                $thisNodeAlreadyHasThisTerm = true;
                break;
            }
        }
        if ( !$thisNodeAlreadyHasThisTerm ) {
            $n->field_tagged_terms['und'][] = array(
                'tid' => $termID
            );
            $nodeHasBeenChanged = true;
        }
    }
    
    if ( $nodeHasBeenChanged ) {
        //dsm($n);
        node_save($n);
    }
    
}

function createVocabIfNotExist($vocabLabel, $vocabMachineName) {
    
    $vid = db_query("SELECT vid FROM taxonomy_vocabulary WHERE machine_name = '{$vocabMachineName}'")->fetchField();
    if ( $vid !== false ) {
        return $vid; // This vocabulary already exists
    }
    
    $edit = array(
        'name' => $vocabLabel,
        'machine_name' => $vocabMachineName,
        'description' => t('Use keywords to identify contents.'),
        'module' => 'taxonomy',
    );
    $vocabulary = (object) $edit;
    taxonomy_vocabulary_save($vocabulary);

    return db_query("SELECT vid FROM taxonomy_vocabulary WHERE machine_name = '{$vocabMachineName}'")->fetchField();
}
