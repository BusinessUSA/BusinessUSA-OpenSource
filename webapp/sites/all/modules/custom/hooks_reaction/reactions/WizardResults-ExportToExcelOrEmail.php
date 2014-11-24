<?php

/* array emailWizardExcelResultsSpreadsheet(string $targetEMailAddress, string $excelFilePath)
 *
 * Dispatches an email to $targetEMailAddress, with the $excelFilePath file attached.
 * This function is meant to be called AFTER saveWizardResultsToExcel() has been executed, 
 * which creates the excel file (iow; the excel-file should already exist)
 *
 * This function is meant to be called by the JavaScript function phpFunction(), as this 
 * function is exposed through the phpFunctionAccessTunnel (php-function-access-tunnel.page.php)
 */
function emailWizardExcelResultsSpreadsheet($targetEMailAddress, $excelFilePath) {
    
    $emailTargets = array();
    $emailTargets[] = 'dfrey@reisys.com';
    $emailTargets[] = $targetEMailAddress;
    
    $msg = "
        Hello <EmailUserName>,

        You are receiving this message because you have requested the list of resources found through the a wizard on BusinessUSA. Please refer to the attached Excel Spreadsheet for a detailed list of these resources.

        If you do not have Microsoft Excel installed, you can obtain an Excel Viewer from <a href=\"http://www.microsoft.com/en-us/download/details.aspx?id=10\">http://www.microsoft.com/en-us/download/details.aspx?id=10</a>. 

        If you need more help, please refer to <a href=\"http://help.business.usa.gov/\">help.business.usa.gov</a>

        Thank you,
        - BusinessUSA team

        <small>[This is a system generated message]</small>
    ";
    $msg = str_replace("\n", "<br/>", trim($msg));
    
    return dispatchEmails(
        $emailTargets, 
        'no-reply@businessusa.gov', 
        'BusinessUSA - Resources and Results', 
        $msg, 
        array($excelFilePath) 
    );
    
}

/* string saveWizardResultsToExcel(array $wizardResults)
 *
 * Given an array ( the same array that is $arr in theme('yawizard_sections', $arr) )
 * will save the results into a excel file somewhere in sites/default/files
 * The returned string is a relative path (from DRUPAL_ROOT) to the file saved.
 * 
 * Returns an empty string on failure.
 */
function saveWizardResultsToExcel($wizRsltsVar) {
    
    // Include needed libraries for writeing Excel files
    if ( !function_exists('arrayToExcel') ) {
        include_once( 'sites/all/libraries/PHPExcelHelper/phpexcel-helper-functions.php' );
    }
    
    // $rows shall be an array of rows to be set in the spreadsheet, each element within $rows, shall be an array of cell values [to be set in the spreadsheet]
    $rows = array();
    
    foreach ( $wizRsltsVar['sections'] as $sectionMachineName => $wizardResultsArray ) {
        
        foreach ( $wizardResultsArray as $result ) {
        
            // Prepare to add a new row into the Excel sheet
            $newRowToAdd = array();
            
            // Determin the value for the cell under the "Category" column
            $resultType = '?';
            if ( !empty($result['ctype']) ) {
                $resultType = $result['ctype'];
            }
            if ( $resultType === '?' ) {
                $resultType = $sectionMachineName;
            }
            if ( !empty($wizRsltsVar['legend'][$resultType]['title']) ) {
                $resultType = $wizRsltsVar['legend'][$resultType]['title']; // This is more of a human-readable/friendly name
            }
            $newRowToAdd['Category'] = $resultType;
            
            // Determin the value for the cell under the "Title" column
            $newRowToAdd['Title'] = $result['title'];
            
            // Determin the value for the cell under the "Link" column
            $linkToContent = false;
            if ( !empty($result['link']) ) { 
                $linkToContent = $result['link'];
            }
            if ( !empty($result['url']) ) { 
                $linkToContent = $result['link'];
            }
            if ( !empty($result['nid']) && $linkToContent === false ) { 
                $linkToContent = drupal_get_path_alias('node/' . $result['nid']); // determin the Drupal [alias] URL-path to this node
            }
            if ( !empty($newRowToAdd['Link']) && $newRowToAdd['Link'] !== false ) {
                $newRowToAdd['Link'] = $linkToContent;
            }
            
            // Determine the value for the cell under the "Details" column
            if ( !empty($result['snippet']) ) {
                $newRowToAdd['Details'] = $result['snippet'];
            }
            
            // Add this row onto the $rows array
            $rows[] = $newRowToAdd;
        }
    }
    
    // Determine save location 
    $xlsDir = 'sites/default/files/wizard-excel-exports';
    if ( !is_dir($xlsDir) ) {
        if ( !mkdir($xlsDir) ) {
            $msg = "Error - Could not create directory " . $xlsDir;
            error_log($msg);
            print $msg;
            return false;
        }
    }

    //Added for  BUSUSA-3339 issue
    $wiz_uri =  $_SERVER['REQUEST_URI'];
    $wiz_uri_arr = explode("/", $wiz_uri);
    $strrest = str_replace(" ", "-", ucwords(str_replace("-", " ", $wiz_uri_arr[1])));

    if ($strrest == 'Browseregulations')
    {
        $strrest = "Browse-Regulations";
    }
    else if($strrest == 'Jobcenter-Wizard')
    {
        $strrest = "Job-Center";
    }


    //End

    $xlsPath = $xlsDir . '/'. $strrest. '-BusinessUSA-Wizard-Results-' . time() . '.xls';
    
    // Transform the $rows array into a PHPExcel spreadsheet (PHPExcel lib)
    $objPHPExcel = arrayToExcel(null, $rows, true); // Note: arrayToExcel() is defined in phpexcel-helper-functions.php - PHPExcel arrayToExcel($objPHPExcel = null, $rows, $writeArrayKeysAsHeader = false, $rowStartWrite = 1, $setActiveSheetTo = 0, $sheetName = null) 
    myExcel_SetRowBold($objPHPExcel, 1, 4);
    myExcel_SetRowAlignment($objPHPExcel, 1, 4);
    
    // Write the Excel file to disk
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save($xlsPath);
    
    if ( is_file($xlsPath) ) {
        return $xlsPath;
    } else {
        return '';
    }
}









