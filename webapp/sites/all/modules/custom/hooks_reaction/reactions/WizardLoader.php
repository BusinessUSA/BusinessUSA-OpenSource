<?php

/** array getAllWizards()
  *
  * [!!] WARNING [!!] - This is a legacy function inported from the old repository, and may need to be refactored a bit.
  *
  * Returns an array of all published Swimlane-Page nodes in the BusinessUSA database. When given TRUE as the
  * first parameter, will return all Swimlane-Pages with excels associated with them (BUSA Wizards).
  *
  * An example return would be:
        Array
        (
            [0] => Array
                (
                    [nid] => 4652132
                    [title] => Start a Business
                    [body] => Start, Business, Start Up, Incorporation, New Business, How to Start, SBA Small Business, Licenses, Business Permits, Market Research, Business Plan
                    [field_swimlane_wizexcelfile_fid] => 25761
                    [field_swimlane_color_value] => 359AB7
                    [field_swimlane_wizurl_value] => start-a-business
                    [field_search_snippet_override_value] => The Start a Business wizard will guide you through aspects of starting a business and finding information you need to succeed. Some useful resources available in this wizard include information on Licenses and Permits, creating a Business Plan, conducting Market Research and connecting with Local Business Counselors.
                    [field_search_icon_override_url] => /sites/all/themes/bususa/images/icon-fs1.png
                )
        [...]
  */
function getAllWizards($returnWizardsWithExcels = false) {

    /*
        [!!] WARNING [!!]
        This is a legacy function inported from the old repository, and may need to be refactored a bit.
    */

    if ( $returnWizardsWithExcels === false ) {
        $returnWizardsWithExcels = '';
    } else {
        $returnWizardsWithExcels = ' AND field_swimlane_wizexcelfile_fid IS NOT NULL ';
    }
    $results = db_query("
        SELECT
            nid,
            title,
            body_value AS 'body',
            field_swimlane_wizexcelfile_fid,
            field_swimlane_color_value,
            field_swimlane_wizurl_value,
            field_search_snippet_override_value,
            field_search_icon_override_url
        FROM node n
        LEFT JOIN field_data_field_swimlane_wizexcelfile wex ON ( wex.entity_id = n.nid )
        LEFT JOIN field_data_field_swimlane_color c ON ( c.entity_id = n.nid )
        LEFT JOIN field_data_field_swimlane_wizurl sso ON ( sso.entity_id = n.nid )
        LEFT JOIN field_data_field_search_snippet_override wu ON ( wu.entity_id = n.nid )
        LEFT JOIN field_data_field_search_icon_override siou ON ( siou.entity_id = n.nid )
        LEFT JOIN field_data_body b ON ( b.entity_id = n.nid )
        WHERE n.type='swim_lane_page' AND n.status = 1 $returnWizardsWithExcels
    ");
    $toReturn = array();
    foreach ($results as $record) {
        $thisWizardInfo = (array) $record;
        $thisWizardInfo['wizexcelfile'] = ' !! DEPRICATED !! Coder Bookmark: CB-EGQ4X9K-BC !! DEPRICATED !! ';
        $toReturn[] = $thisWizardInfo;
    }
    return $toReturn;
}


/** array findRelatedWizardsToSearchTerms()
  *
  * This function is meant to be used in order to determin what wizards should be shown at the top of the
  * search results page, based on the search-input (free text) supplied by the user.
  *
  * Returns an empty array when there are no results. When there are results, returns an array of arrays in
  * a similar structure as what getAllWizards() returns, with two additional descriptors, trigger-phrase, and
  * search-query. (see example below)
  *
  * An example return would be:
        Array
        (
            [4652132] => Array
                (
                    [nid] => 4652132
                    [title] => Start a Business
                    [body] => Start, Business, Start Up, Incorporation, New Business, How to Start, SBA Small Business, Licenses, Business Permits, Market Research, Business Plan
                    [field_swimlane_wizexcelfile_fid] => 25761
                    [field_swimlane_color_value] => 359AB7
                    [field_swimlane_wizurl_value] => start-a-business
                    [field_search_snippet_override_value] => The Start a Business wizard will guide you through aspects of starting a business and finding information you need to succeed. Some useful resources available in this wizard include information on Licenses and Permits, creating a Business Plan, conducting Market Research and connecting with Local Business Counselors.
                    [field_search_icon_override_url] => /sites/all/themes/bususa/images/icon-fs1.png
                    [search-query] => how to get startting a business
                    [trigger-phrase] => business
                )
        [...]
  */
function findRelatedWizardsToSearchTerms($userGivenSearchWords) {

    // This functions return buffer - this function will return this variable when it terminates
    $ret = array();

    // Get the $userGivenSearchWords into a better form for comparison purposes
    $userGivenSearchWords = strtolower($userGivenSearchWords);
    $userGivenSearchWords = str_replace('-', ' ', $userGivenSearchWords);
    $userGivenSearchWords = explode(' ', $userGivenSearchWords);

    // Obtain a list of all wizards in BusinessUSA and information about them
    $busaWizards = getAllWizards(false); // Note: getAllWizards() is defined in this file above

    // For each wizard in BusinessUSA
    foreach ( $busaWizards as $busaWizard ) {

        /* The text in the BODY field of the Swimlane-Page node (wizard), should be a comma seperated list of
        phrases that should trigger the wizard to show up on the search results page, should ALL the words in
        any individual element of this array be present in the search query*/
        $triggerPhrases = explode(',', $busaWizard['body']);

        // For each trigger-phrase [associated with this wizard]
        foreach ( $triggerPhrases as $triggerPhrase ) {

            $triggerPhrase = strtolower( trim($triggerPhrase) );
            $triggerWords = explode(' ', $triggerPhrase);

            /* If ALL of the $triggerWords exist in the user-given search-query, then this wizard should be shown
            on the search results page */
            $wizardIsRelated = true;
            foreach ( $triggerWords as $triggerWord ) {
                if ( !in_array($triggerWord, $userGivenSearchWords) ) {
                    $wizardIsRelated = false;
                }
            }

            if ( $wizardIsRelated ) {
                // It seems that all of the words in this $triggerPhrase exist in the user-given search-query...
                $busaWizard['search-query'] = implode(' ', $userGivenSearchWords);
                $busaWizard['trigger-phrase'] = $triggerPhrase;
                $ret[$busaWizard['nid']] = $busaWizard;
            }
        }
    }

    // Return the contents in the return buffer
    return $ret;
}

function wizardAutoDetect($searchTitle = '') {

    // We expect the URL to contain the Swimlane-Page title - Strip the query string from the URL if it exists
    $ruri = request_uri();
    if ( strpos($ruri, '?') !== false ) {
        $ruri = substr( $ruri, 0, strpos($ruri, '?') );
    }

    // We expect the URL to contain the Swimlane-Page (node) title for the target swimlane/wizard
    $ruri = explode('/', $ruri);
    $swimlaneTitle = $ruri[ count($ruri) - 1 ];
    $swimlaneTitle = urldecode($swimlaneTitle);

    // We may have been given the title to search for
    if ( $searchTitle !== '' ) {
        $swimlaneTitle = $searchTitle;
    }

    // SQL to find the Swimlane-Page node based on the title
    $q = "
        SELECT nid AS nid
        FROM node n
        WHERE
            n.type = 'swim_lane_page'
            AND InStr(n.title, '$swimlaneTitle')<>0
        LIMIT 1
    ";

    // Debug
    if ( strpos(request_uri(), '-DEBUG-WIZARDDETECT-QUERY-') !== false ) {
        dsm($q);
    }

    // Search for this node
    $result = db_query($q);
    $swimlaneNid = false;
    foreach ($result as $record) {
        $swimlaneNid = $record->nid;
    }
    if ( $swimlaneNid === false ) {
        return "Error - Could not find the target swimlane title ($swimlaneTitle)";
    }

    // Get the File-Id of the Wizard-Excel-Source field in the target Swimlane-Page node
    $n = node_load($swimlaneNid);
    $fid = false;
    if ( !empty($n->field_swimlane_wizexcelfile) && is_array($n->field_swimlane_wizexcelfile) && !empty($n->field_swimlane_wizexcelfile['und']) ) {
        $fid = $n->field_swimlane_wizexcelfile['und'][0]['fid'];
    }
    if ( $fid === false ) {
        return 'Error - This this swimlane does not have an associated wizard';
    }

    // Get the real path of this file
    $dFile = file_load($fid);
    if ( $dFile === false ) {
        print 'Error - Wizard (source-file) not found';
        return;
    }
    $wizSrcPath = drupal_realpath($dFile->uri);

    // Verify this path truly exists
    if ( !file_exists($wizSrcPath) ) {
        print 'Error - The Wizard-Excel-Source sheet is missing! - ' . $wizSrcPath . '<br/>';
        print '<span class="admin-only">Please <a href="/node/' . $swimlaneNid . '/edit">edit the swimlane</a>, and upload the appropriate excel spreadsheet</span>';
        if ( function_exists('debugEmail') ) {
            $serverDomain = 'https://' . $_SERVER['SERVER_NAME'];
            debugEmail(
                'critical',
                'An Excel-Source spreadsheet is missing for the "' . $n->title . '" Wizard! <br/>' .
                    'The expected path of this file was: ' . $wizSrcPath . '<br/>' .
                    "This swimlane/wizard can be editted from <a href=\"$serverDomain/node/$swimlaneNid/edit\">$serverDomain/node/$swimlaneNid/edit</a><br/> " .
                    '
                        <form action="/dev/ulswimlanefile" method="post">
                            <input type="hidden" name="nid" id="nid" value="' . $swimlaneNid . '" /><br/>
                            <b>Please upload the excel file for this swimlane now:</b><br/>
                            <input type="file" name="excelFile" id="excelFile" /><br/>
                            <input type="submit" />
                        </form>
                    '
            );
        }
        return;
    }

    // Load BUSA Wizard dependencies
    include('sites/all/themes/bususa/templates/wizard/BusinessUSAWizard.classes.php');

    // Create and print the wizard
    $wiz = new BusinessUSAWizard();
    $wiz->swimlaneNid = $swimlaneNid;
    $wiz->loadFromExcel($wizSrcPath);
    $html = $wiz->render();

    // Debug
    if ( strpos( request_uri(), '-DEBUG-VERBOSE-WIZARD-' ) !== false ) {
        dsm(
            array(
                'wiz' => $wiz,
                'wiz->getAllTagsUsedInWizard' => $wiz->getAllTagsUsedInWizard()
            )
        );
    }

    // Build Breadcrumbs
    $breadcrumb = array();
    $breadcrumb[] = l('Home', '<front>');
    $breadcrumb[] = $wiz->title;
    drupal_set_breadcrumb($breadcrumb);

    // Fix a styling bug where the width of the content area is not taking all space (can this be done through contexts while applying single-column layout?)
    $html .= '
        <style>
            /* The following style(s) are stored inside sites\all\themes\bususa\templates\wizard\wizard.php */
            #region-content {
                width: 100%;
            }
        </style>
    ';

    // If this wizard should be loaded in Widget mode, then we only want everything in $html to be the ONLY markup printed within the body tag...
    // for this we use the global $overrideBodyMarkup variable - refer to html.tpl.php to see the use of this override
    if ( !empty($_GET['widget']) && ( intval($_GET['widget']) === 1 || trim(strtolower($_GET['widget'])) === 'true' ) ) {
        global $overrideBodyMarkup;
        $overrideBodyMarkup = $html;
    }

    return $html;
}


function sortResultsArrayByFields($arr, $fieldName1, $fieldName2) {

    if ( strpos(request_uri(), '-DEBUG-sortResultsArrayByFields-') !== false ) {
        print '
            <div class="debug-info debug-info-sortResultsArrayByFields" style="display: none;">
                <!--
                    BEFORE:
                        fieldName1 is ' . $fieldName1 . '
                        fieldName2 is ' . $fieldName2 . '
                        arr is ' . print_r($arr, true) . '
                -->
            </div>
        ';
    }

    $rtn = array();
    $eleTrack = 0;

    foreach ($arr as $ele) {

        if ( isset($ele[$fieldName1]) ) {
            $eleFieldValue1 = $ele[$fieldName1];
        } else {
            $eleFieldValue1 = 1;
        }
        $eleFieldValue1++;

        if ( isset($ele[$fieldName2]) ) {
            $eleFieldValue2 = $ele[$fieldName2];
        } else {
            $eleFieldValue2 = 1;
        }
        $eleFieldValue2++;

        $eleNumber1 = intval($eleFieldValue1);
        $eleNumber2 = intval($eleFieldValue2);

        if (  $eleNumber1 === 0 ) { exit("sortResultsArrayByFields - Error; Cannot handel 0 - $eleFieldValue1 \n"); }
        if (  $eleNumber2 === 0 ) { exit("sortResultsArrayByFields - Error; Cannot handel 0 - $eleFieldValue2 \n"); }

        $newKeyName = substr('   ' . $eleNumber1, -3) . '_'  . substr('   ' . $eleNumber2, -3) . '_ele' . $eleTrack;

        $rtn[$newKeyName] = $ele;
        $eleTrack++;
    }

    ksort($rtn);
    $rtn = array_reverse($rtn);

    if ( strpos(request_uri(), '-DEBUG-sortResultsArrayByFields-') !== false ) {
        print '
            <div class="debug-info debug-info-sortResultsArrayByFields" style="display: none;">
                <!--
                    AFTER:
                        fieldName1 is ' . $fieldName1 . '
                        fieldName2 is ' . $fieldName2 . '
                        rtn is ' . print_r($rtn, true) . '
                -->
            </div>
        ';
    }

    return array_values($rtn);
}







