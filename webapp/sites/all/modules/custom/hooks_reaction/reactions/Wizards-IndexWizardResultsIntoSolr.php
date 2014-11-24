<?php
/*
    [--] BACKGROUND [--]
    
    The results shown in wizards in BusinessUSA currently* are stored in the Wizard's source-spreadsheet. The 
    wizard-tags which are associated with the results are also ONLY held in the Wizard's source-spreadsheet.
    There may or may not be content (nodes) in the Drupal database that is referenced by these spreadsheets.
    Due to this, it is impossible for Solr to index ALL of the content (results) within wizards. This script is meant to solve 
    this problem.
    
    * = this comment was written on 2013/08/22
    
*/

/**
 * Implements hook_cron()
 * 
 * Hook to periodically trigger associateWizardTagsWithNodesInDrupalDatabase()
 */
hooks_reaction_add("cron",
	function () {
		associateWizardTagsWithNodesInDrupalDatabaseForAllWizards(true);
	}
);

/** array associateWizardTagsWithNodesInDrupalDatabase()
  *
  * Triggers associateWizardTagsWithNodesInDrupalDatabase() for each wizard in BusinessUSA
  *
  * Returns an associative array of wizard-excelsheet-paths (parameter given to associateWizardTagsWithNodesInDrupalDatabase()) as the key, and 
  * the return of associateWizardTagsWithNodesInDrupalDatabase() as the values.
  */
function associateWizardTagsWithNodesInDrupalDatabaseForAllWizards($returnSummaryOnly = false) {
    
    // Dont let this process run if it has already ran within the past 12 hours
    $secondsIn12Hours = 43200;
    if ( time() < variable_get('associateWizardTagsWithNodes_lastRun', 0) + $secondsIn12Hours ) {
        error_log(__FUNCTION__ . " will not execute its functionality, it has ran too recently, Coder Bookmark: CB-NOGJX2V-BC.");
        return;
    }
    
    // Note that this process has "last run" at this time
    variable_set('associateWizardTagsWithNodes_lastRun', time());
    
    // The return buffer, this array will be what the function returns when it terminates
    $toReturn = array();

    //$node_type = 'wizard_result_reference_for_solr';
    //$mySqlLink = connectMySQL();

    $result = db_query("delete FROM node WHERE type='wizard_result_reference_for_solr'");
    //mysql_query($result, $mySqlLink);


    // Select the nodes that we want to delete.
    /*$result = db_select('node', 'n')
        ->fields('n', array('nid'))
        ->condition('type', $node_type, '=')
        ->execute();

    foreach ($result as $record) {
        node_delete($record->nid);

    }*/


    // Build a list of all WizardResult-Excel files used, across all wizards in BusinessUSA
    $busaWizardResultExcels = array(
        'sites/all/pages/access-financing/wizard-results.xls',
        'sites/all/pages/begin-exporting/wizard-results.xls',
        'sites/all/pages/disaster-assistance/wizard-results.xls',
        'sites/all/pages/expand-exporting/wizard-results.xls',
        'sites/all/pages/find-opportunities/wizard-results.xls',
        'sites/all/pages/healthcare/wizard-results.xls',
        'sites/all/pages/jobcenter-wizard/wizard-results.xls',
        'sites/all/pages/rural-exporting/wizard-results.xls',
        'sites/all/pages/select-usa/wizard-results.xls',
        'sites/all/pages/start-a-business/wizard-results.xls',
        'sites/all/pages/taxes-and-credits/wizard-results.xls',
        'sites/all/pages/veterans/wizard-results.xls'
    );
    
    // For each Swimlane-Page node (foreach wizard)
    foreach ( $busaWizardResultExcels as $busaWizardResultExcel ) {
        if ( file_exists($busaWizardResultExcel) ) {
            dsm( $busaWizardResultExcel );
            $report = associateWizardTagsWithNodesInDrupalDatabase($busaWizardResultExcel, $returnSummaryOnly);
            $toReturn[$busaWizardResultExcel] = $report;
        }
    }
    
    // Return $toReturn, the return buffer
    return $toReturn;
}

/** array associateWizardTagsWithNodesInDrupalDatabase()
  *
  * This function has two main purposes:
  *     1) To associate wizard-tags with nodes in the database (the nodes which exist in the Drupal database and are 
  *         referenced by the wizards).
  *     2) To find wizard-results which are not referencing nodes in the Drupal database, and create a node 
  *         for them (clone wizard-results into nodes).
  */
function associateWizardTagsWithNodesInDrupalDatabase($wizardExcelFile, $returnSummaryOnly = false) {
    
    $ret = array();
    $excelName = $wizardExcelFile;
    
    // Load dependency functions
    include_once('sites/all/libraries/PHPExcelHelper/phpexcel-helper-functions.php');
    
    // Get a detailed list of all results this wizard may/can show (loaded from the excel spreadsheet)
    $allWizardPotentialResults = ya_wizard_excelToArray($wizardExcelFile);
    
    // Foreach results this wizard may show...
    foreach ( $allWizardPotentialResults as $wizardPotentialResult ) {
        
        $title = false;
        if ( !empty($wizardPotentialResult['assoc']['title']) ) {
            $title = $wizardPotentialResult['assoc']['title'];
        } elseif ( !empty($wizardPotentialResult['assoc']['Title']) ) {
            $title = $wizardPotentialResult['assoc']['Title'];
        } else {
            error_log(basename(__FILE__) . '::' . __FUNCTION__ .  " - ERROR - Title not found in spreadsheet {$excelName}, row is " . print_r($wizardPotentialResult, true));
        }
        
        if ( $title !== false ) {
        
            // Check if this wizard-result is in reference to a node already in the Drupal database
            $assocUrl = '';
            $nodesWithThisTitle = findNodesByTitle($title, 'wizard_result_reference_for_solr');
            if ( count($nodesWithThisTitle) > 0 ) {
                $nodesWithThisTitle = array_values($nodesWithThisTitle);
                $assocUrl = drupal_get_path_alias('node/' . $nodesWithThisTitle[0]['nid']);
            }
            if ( isset($wizardPotentialResult['assoc']['target']) ) { $assocUrl = $wizardPotentialResult['assoc']['target']; }
            if ( isset($wizardPotentialResult['assoc']['Target']) ) { $assocUrl = $wizardPotentialResult['assoc']['Target']; }
            if ( isset($wizardPotentialResult['assoc']['link']) ) { $assocUrl = $wizardPotentialResult['assoc']['link']; }
            if ( isset($wizardPotentialResult['assoc']['Link']) ) { $assocUrl = $wizardPotentialResult['assoc']['Link']; }
            if ( isset($wizardPotentialResult['assoc']['url']) ) { $assocUrl = $wizardPotentialResult['assoc']['url']; }
            if ( isset($wizardPotentialResult['assoc']['Url']) ) { $assocUrl = $wizardPotentialResult['assoc']['Url']; }
            if ( isset($wizardPotentialResult['assoc']['URL']) ) { $assocUrl = $wizardPotentialResult['assoc']['URL']; }
            
            // Decide what this data in the field_assoc_wiz_terms field should be set to - make it a list of all the wizard-tags and wizard-tag-question-texts
            $newAssociatedWizardTagsValue = 'WizardAssosicatedTags: ' . implode(', ', getAssocTagsBasedOnExcelRow($wizardPotentialResult['assoc']));
            
            // Decide snippet
            $snippet = '';
            if ( !empty($wizardPotentialResult['assoc']['snippet']) ) {
                $snippet = $wizardPotentialResult['assoc']['snippet'];
            }
            if ( !empty($wizardPotentialResult['assoc']['Snippet']) ) {
                $snippet = $wizardPotentialResult['assoc']['Snippet'];
            }
            
            $node = createWizardResultReferenceForSolr(
                $title, 
                $newAssociatedWizardTagsValue, 
                $assocUrl, 
                $snippet, 
                $excelName
            );
            
            $ret[] = $node->nid;
            unset($node); // free memory
        }
    }
    
    return $ret;
}

function getAssocTagsBasedOnExcelRow($excelRow) {
    
    $ret = array();
    foreach ( $excelRow as $key => $val ) {
        if ( intval($val) > 0 ) {
            $ret[] = str_replace('_', ' ', $key);
        }
    }
    
    return $ret;
}

/** node createWizardResultReferenceForSolr()
  *
  * Saves a node into the Drupal database under the 'Wizard-Result reference for Solr' content-type.
  *
  * This function will NOT create duplicates. Duplicates will be detected based on Title. If a node under 
  * this content-type with the given title already exists, the other parameters will be set to the existing 
  * node, and saved.
  *
  * Returns the node - the $node after it has gone through node_save($node)
  */
function createWizardResultReferenceForSolr($nodeTitle, $associatedWizardTagsValue = '', $referenceURL = '', $searchPageSnippetOverride = '', $excelSource = '') {
    
    // Check if this node already exists in the database, if it dosnt, construct new node
    $foundNodes = findNodesByTitle($nodeTitle, 'wizard_result_reference_for_solr');
    $foundNodes = array_values($foundNodes);
    if ( count($foundNodes) > 0 ) {
        $node = node_load( $foundNodes[0]['nid'] );
        error_log(basename(__FILE__) . '::' . __FUNCTION__ .  " is updating node {$node->nid} (\"{$node->title}\")");
    } else {
        $node = new StdClass();
        $node->type = 'wizard_result_reference_for_solr';
        $node->title = $nodeTitle;
        $node->status = 1;
        $node->log = 'Created by ' . __FUNCTION__ . '() in ' . basename(__FILE__);
        error_log(basename(__FILE__) . '::' . __FUNCTION__ .  " is creating a new {$node->type} node (\"{$node->title}\")");
    }
    
    // Pull the user ID of sys_AssociateWizardTermsWithContentDotPHP - this will be the user/author the new node will be created under
    $userObj = user_load_by_name('sys_AssociateWizardTermsWithContentDotPHP');
    $authorId = $userObj->uid;
    $node->uid = $authorId;
    
    if ( $associatedWizardTagsValue !== '' ) {
        $node->field_assoc_wiz_terms = array(
            'und' => array(
                0 => array(
                    'value' => $associatedWizardTagsValue,
                    'format' => null,
                    'safe_value' => strval($associatedWizardTagsValue)
                )
            )
        );
    }
    if ( $referenceURL !== '' ) {
        $node->field_wizref_url = array(
            'und' => array(
                0 => array(
                    'value' => $referenceURL,
                    'format' => null,
                    'safe_value' => strval($referenceURL)
                )
            )
        );
    }
    if ( $searchPageSnippetOverride !== '' ) {
        $node->field_search_snippet_override = array(
            'und' => array(
                0 => array(
                    'value' => $searchPageSnippetOverride,
                    'format' => null,
                    'safe_value' => strval($searchPageSnippetOverride)
                )
            )
        );
    }
    if ( $excelSource !== '' ) {
        $node->field_wizref_excel = array(
            'und' => array(
                0 => array(
                    'value' => strval($excelSource)
                )
            )
        );
    }
    
    // Save this new node
    node_save($node);
    
    return $node;
}

