<div class="vendorLightBox" style="display: none;" rendersource="<?php print basename(__FILE__); ?>">
    <div rendersource="<?php print basename(__FILE__); ?>">
        <div class="vendorLightBoxWrapper">
            <!-- Logo -->
            <!-- <div class="vendorLightBox-logo">
                <div class="kill-vendorLightBox" onclick="jQuery.colorbox.close();">
                    Close
                </div>
                <div class="vendorLightBox-logo-innerwrapper">
                    <img src="/sites/all/themes/bizusa/images/logo.png" />
                </div>
            </div> -->
            <div class="vendorLightBox">
                <div class="alert-text" style="width:300px; text-align:center;">
                    <p class="lead">Please make a selection.</p>
                    <div class="center-block">
                        <button type="button" class="btn btn-primary btn-sm" onclick="jQuery.colorbox.close();">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

/** array ya_wizard_WizardFromExcel(string $spreadsheetPath)
  *
  *  Given a path to an Excel file, this function will read the target file, and return a renderable array for 
  *  wizards, for use with theme('yawizard', <ReturnedArray>)
  */

function _ya_wizard_WizardFromExcel($spreadsheetPath) {
    
    $ret = array(
        'title' => 'Untitled',
        'uniqueId' => 'untitled',
        'hasSidebar' => false,
        'resetFutureElementsOnAnwser' => false,
        'nextButtonDependencyLogic' => array(),
        'navigationDependencyLogic' => array('!slide0'),
        'slides' => array(
            0 => array(
                'title' => 'Get Started',
                'dependencyLogic' => array('slide0'),
                'clipart' => '',
                'backgroundColor' => '#AAAAAA',
                'ajaxLoad' => false,
                'questions' => array(
                    0 => array(
                        'widgetType' => 'image',
                        'label' => 'FILL IN THIS VALUE IN AN ICON FELD IN THE EXCEL FILE',
                        'wizardTag' => 'splashimage',
                        'options' => array(),
                        'containerClass' => 'splash-icon-image',
                        'inputClass' => 'wizard-splash-icon',
                        'inputAttrs' => '',
                        'dependencyLogic' => array()
                    ),
                    1 => array(
                        'widgetType' => 'label',
                        'label' => 'Wizard title goes here',
                        'wizardTag' => 'splash-title-label',
                        'options' => array(),
                        'containerClass' => 'splash-title',
                        'inputClass' => '',
                        'inputAttrs' => '',
                        'dependencyLogic' => array()
                    ),
                    2 => array(
                        'widgetType' => 'label',
                        'label' => 'Welcome message goes here',
                        'wizardTag' => 'splash-welcome-message',
                        'options' => array(),
                        'containerClass' => 'splash-welcome',
                        'inputClass' => '',
                        'inputAttrs' => '',
                        'dependencyLogic' => array()
                    ),
                    3 => array(
                        'widgetType' => 'nextbutton',
                        'label' => 'Get Started',
                        'wizardTag' => 'splash-next-button',
                        'options' => array(),
                        'containerClass' => 'splash-button',
                        'inputClass' => '',
                        'inputAttrs' => '',
                        'dependencyLogic' => array()
                    ),
                    4 => array(
                        'widgetType' => 'label',
                        'label' => 'footer message goes here',
                        'wizardTag' => 'splash-footer-message',
                        'options' => array(),
                        'containerClass' => 'splash-footer',
                        'inputClass' => '',
                        'inputAttrs' => '',
                        'dependencyLogic' => array()
                    ),
                )
            )
        )
    );
    
    $verbose = array();
    $excelArray = ya_wizard_excelToArray($spreadsheetPath);
    $verbose['excelArray'] = $excelArray;
    
    $verbose['addedQuestions'] = array();
    $verbose['assoc'] = array();
    
    $tagDependency = '';
    
    foreach ( $excelArray as $row ) {
        
        $assoc = $row['assoc'];
        $verbose['assoc'][] = $assoc;
        $cols = $row['cols'];
        
        if ( !empty($assoc['Step ID']) ) { // Ensure this row is targeting something (this is validation)
            
            $stepId = $assoc['Step ID'];
            $widgetType = strtolower($assoc['Widget Type / Field']);
            $setValue = $assoc['Question Text, Step Title, or ClipArt-URL'];
            $options = $assoc['Options'];
            $assocTag = $assoc['Association'];
            
            $tagDependency = array();
            if ( isset($assoc['Tag Dependency']) && is_null($assoc['Tag Dependency']) === false ) {
                $tagDependency = $assoc['Tag Dependency'];
                $tagDependency = ya_wizard_parseLogicStringToArrray($tagDependency);
            }
            
            if ( isset($assoc['Input Class']) && is_null($assoc['Input Class']) === false ) {
                $inputClass = strtolower( $assoc['Input Class'] );
            } else {
                $inputClass = false;
            }
            
            if ( isset($assoc['Container Class']) && is_null($assoc['Container Class']) === false ) {
                $containerClass = strtolower( $assoc['Container Class'] );
            } else {
                $containerClass = false;
            }
            
            if ( isset($assoc['Input Attrs']) && is_null($assoc['Input Attrs']) === false ) {
                $inputAttrs = $assoc['Input Attrs'];
            } else {
                $inputAttrs = false;
            }
            
            // Ensure this step number exists
            if ( is_numeric($stepId) ) {
                $stepId = intval($stepId);
                if ( !isset($ret['slides'][$stepId]) ) {
                
                    $ret['slides'][$stepId] = array(
                        'title' => 'Unknown',
                        'dependencyLogic' => array(),
                        'clipart' => '',
                        'backgroundColor' => '',
                        'questions' => array()
                    );
                
                }
            }
            
            switch ( $widgetType ) {
                case 'uniqueid':
                    $ret['uniqueId'] = $setValue;
                    break;
                case 'hide-no-result-sections':
                    error_log('Notice: Usage of hide-no-result-sections in Wizard excel sheet is depricated');
                    break;
                case 'no-next-slide-msg':
                    $ret['slides'][$stepId]['noNextSlideMessage'] = $setValue;
                    break;
                case 'welcome-message':
                    $ret['slides'][0]['questions'][2]['label'] = $setValue;
                    break;
                case 'post-welcome-message':
                    $ret['slides'][0]['questions'][4]['label'] = $setValue;
                    break;
                case 'uses-sidebar':
                    $ret['hasSidebar'] = $setValue;
                    break;
                case 'reset-future-elements-on-anwser':
                    if ( intval($setValue) === 1 || strtolower($setValue) === 'true' || strtolower($setValue) === 'yes' ) {
                        $ret['resetFutureElementsOnAnwser'] = true;
                    }
                    break;
                case 'background-color':
                    $ret['slides'][0]['backgroundColor'] = '#' . ltrim($setValue, '#');
                    break;
                case 'icon':
                    $ret['slides'][0]['questions'][0]['label'] = $setValue;
                    break;
                case 'subtitle':
                    $msg = "Notice: Usage of subtitle in Wizard excel sheet is depricated";
                    if ( function_exists('version_awareness_environment_isproduction') && version_awareness_environment_isproduction() === true ) {
                        dsm($msg);
                    } else {
                        drupal_set_message($msg, 'warning');
                    }
                    break;
                case 'clipart':
                    $ret['slides'][$stepId]['clipart'] = str_replace('/bususa/', '/bizusa/', $setValue);
                    break;
                case 'title':
                    if ( is_numeric($stepId) ) {
                        $ret['slides'][$stepId]['title'] = $setValue;
                        $ret['slides'][$stepId]['dependencyLogic'] = $tagDependency;
                    } else {
                        $ret['title'] = $setValue;
                        $ret['slides'][0]['questions'][1]['label'] = $setValue;
                    }
                    break;
                case 'disable-next-button':
                    $newRule = $tagDependency;
                    if ( is_numeric($stepId) ) {
                        $newRule = "slide$stepId" . $newRule;
                    }
                    $ret['nextButtonDependencyLogic'][] = $newRule;
                    break;
                case 'masterstep':
                    $msg = "Notice: Usage of masterstep in Wizard excel sheet is depricated";
                    if ( function_exists('version_awareness_environment_isproduction') && version_awareness_environment_isproduction() === true ) {
                        dsm($msg);
                    } else {
                        drupal_set_message($msg, 'warning');
                    }
                    break;
                case 'step-master-id':
                    $msg = "Notice: Usage of step-master-id in Wizard excel sheet is depricated";
                    if ( function_exists('version_awareness_environment_isproduction') && version_awareness_environment_isproduction() === true ) {
                        dsm($msg);
                    } else {
                        drupal_set_message($msg, 'warning');
                    }
                    break;
                default:
                    
                    if ( is_numeric($stepId) ) {
                        if ( trim($options) !== '' ) {
                            $options = explode(',', $options);
                        }
                        
                        $ret['slides'][$stepId]['questions'][] = array(
                            'widgetType' => $widgetType,
                            'label' => $setValue,
                            'wizardTag' => trim($assocTag),
                            'options' => $options,
                            'containerClass' => $containerClass,
                            'inputClass' => $inputClass,
                            'inputAttrs' => $inputAttrs,
                            'dependencyLogic' => $tagDependency
                        );
                    }
            }
        }
    }
    
    if ( strpos( request_uri(), '-DEBUG-LOADFROMEXCEL-VERBOSE-' ) !== false ) {
        dsm($verbose);
    }
    
    // If the $v[wizardTag] is a string of comma separated values, and the number of values match the number of elements in v[options], then assume these are wizard tags for each option
    foreach ( $ret['slides'] as &$slide ) {
        foreach ( $slide['questions'] as &$question ) {
            $wizardTags = explode(',', $question['wizardTag']);
            if ( is_array($question['options']) && count($wizardTags) == count($question['options']) ) {
                $question['options'] = array_combine($wizardTags, $question['options']);
                $question['wizardTag'] = cssFriendlyString($question['label']);
            }
        }
    }
    
    return $ret;
    
}

/** array ya_wizard_parseLogicStringToArrray(string $logicString)
  *
  *  Parses a logic string into an array for use with yawizard.js
  */
function _ya_wizard_parseLogicStringToArrray($logicString) {
    
    $ret = explode('||', $logicString);
    if ( count($ret) === 1 ) {
        $continueParseFrom = &$ret;
    } else {
        $ret = array('or' => $ret);
        $continueParseFrom = &$ret['or'];
    }
    
    foreach ( $continueParseFrom as &$subLogic ) {
        $subLogic = explode('+', $subLogic);
        if ( count($subLogic) === 1 ) {
            $subLogic = $subLogic[0];
        } else {
            $subLogic = array('and' => $subLogic);
        }
    }
    
    foreach ( $continueParseFrom as &$subLogic ) {
        if ( is_string($subLogic) ) {
            $subLogic = explode('|', $subLogic);
            if ( count($subLogic) === 1 ) {
                $subLogic = $subLogic[0];
            } else {
                $subLogic = array('or' => $subLogic);
            }
        }
    }
    
    reset($ret);
    if ( count($ret) === 1 && is_numeric(key($ret)) ) {
        $ret = $ret[0];
    }
    
    return $ret;
    
}

/** array ya_wizard_excelToArray(string $spreadsheetPath, [int $workSheetNumber = 0])
  *
  *  Given a path to an Excel file, this function will read the target file, and return the Excel spreadsheet data in an array/array
  *  Where the first array dimension references a row in the source spread sheet, and the second dimension references a cell.
  */
function _ya_wizard_excelToArray($spreadsheetPath, $workSheetNumber = 0) {

    if ( !ya_wizard_includePhpExcel() ) {
        error_log('Error - ya_wizard_excelToArray() - Could not find and include the PHPExcel library');
    }
    
    // load the spreadsheet
    $objPHPExcel = PHPExcel_IOFactory::load($spreadsheetPath);
    
    $excelHeaders = array();
    $excelArray = array();
    
    $currentWkSheetNumb = -1;
    foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
    
        $currentWkSheetNumb++;
    
        if ( $workSheetNumber === $currentWkSheetNumb ) {
        
            $highestRow = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $nrColumns = ord($highestColumn) - 64;

            // Get the headers - assume the cells on the first row are headers
            for ($col = 0; $col < $highestColumnIndex; ++ $col) {
                $excelHeaders[$col] = $worksheet->getCellByColumnAndRow($col, 1)->getValue();
            }
            
            for ($row = 2; $row <= $highestRow; ++ $row) {
                $excelArray['row-' . $row] = array(
                    'assoc' => array(),
                    'cols' => array()
                );
                for ($col = 0; $col < $highestColumnIndex; ++ $col) {
                    $thisColHeader = $excelHeaders[$col];
                    $thisCellValue = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                    $excelArray['row-' . $row]['assoc'][$thisColHeader] = $thisCellValue;
                    $excelArray['row-' . $row]['cols'][$col] = $thisCellValue;
                }
            }
        
        }
    }
    
    return $excelArray;
}

/** bool ya_wizard_includePhpExcel()
  *
  *  Searches for and inclues the PHPExcel library
  *  Returns TRUE only when the PHPExcel library is found and includes, FALSE otherwise.
  */
function _ya_wizard_includePhpExcel() {

    if ( is_dir('sites/all/libraries/PHPExcel') && strpos(get_include_path(), 'sites/all/libraries/PHPExcel') === false ) {
        set_include_path( get_include_path() . PATH_SEPARATOR . 'sites/all/libraries/PHPExcel/' );
    }
    
    if ( !@include_once('PHPExcel.php') ) {
        return false;
    } else {
        return true;
    }

}









