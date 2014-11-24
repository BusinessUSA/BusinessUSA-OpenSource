<?php
require_once DRUPAL_ROOT . '/sites/all/libraries/PHPExcel/PHPExcel.php';
require_once DRUPAL_ROOT . '/sites/all/modules/custom/custom_report/common.inc';
require_once 'events_mysql.inc';

function downloadPHPExcelFile() {
  $sheet = new PHPExcel();
  $sheet->getProperties()
        ->setCreator('business.usa.gov')
        ->setLastModifiedBy('business.usa.gov')
        ->setTitle('Expired Event Summary')
        ->setKeywords('events');
  $sheet->getDefaultStyle()
        ->getAlignment()
        ->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
  $sheet->getDefaultStyle()
        ->getAlignment()
        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
  $sheet->getDefaultStyle()
        ->getFont()
        ->setName('Arial' );
  $sheet->getDefaultStyle()
        ->getFont()
        ->setSize(12);
  $sheet->setActiveSheetIndex(0);
  $activeSheet = $sheet->getActiveSheet();
  $activeSheet->setTitle('Events Summary');
  $activeSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE)->setFitToWidth(1)->setFitToHeight(0);
  $activeSheet->getHeaderFooter()->setOddHeader('&C&B&16' . $sheet->getProperties()->getTitle())->setOddFooter('&CPage &P of &N' );
  
  /**
   * Print cell value 'Expired Event Count'
   */
  $activeSheet->setCellValue('A1', 'Events Expired');
  foreach($results = get_expired_event_count_mysql() as $result) {
    $activeSheet->setCellValue('A2', $result->count);
  }
  
  /**
   * Current event count page
   */
  $activeSheet->setCellValue('B1', 'Events Current');
  foreach($results = get_current_event_count_mysql() as $result) {
    $activeSheet->setCellValue('B2', $result->count);
  }
  
  // Format cells A & B
  $styleArray = array(
    'font'          => array('bold' => true,),
    'alignment'     => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
    'fill'          => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
      'startcolor'  => array('argb' => 'EBEBEBEB',),
    ),
  );
  $allBorders = array(
    'borders'       => array(
      'allborders'  => array('style' => PHPExcel_Style_Border::BORDER_THIN,),
    ),
  );
  $activeSheet->getStyle('A1:B1')->applyFromArray($styleArray);
  $activeSheet->getStyle('A4:B4')->applyFromArray($styleArray);
  $activeSheet->getStyle('D4:E4')->applyFromArray($styleArray);
  $activeSheet->getStyle('G4:H4')->applyFromArray($styleArray);
  $activeSheet->getStyle('J4:K4')->applyFromArray($styleArray);
  
  // Loop to autoSize culumns width
  for ($col = 'A'; $col <= 'K'; $col++) {
    $activeSheet->getColumnDimension($col)->setAutoSize(true);
  }
  
  //Border formating
  $activeSheet->getStyle('A1:B2')->applyFromArray($allBorders);
  
  /**
   * Count by event type
   */
  // Print column table labels
  $header = array('Events Expired', 'Event Type');
  foreach ($header as $key => $value) {
    $activeSheet->setCellValueByColumnAndRow($key, 4, $value);
  }
  $rowNum = 5;
  $colNum = 0;
  foreach($results = get_expired_event_count_by_type_mysql() as $result) {
    $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->node_count);
    $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, ($result->event_type != '') ? $result->event_type : 'None');
    $rowNum++;
    $colNum = 0;
  }
  $resultsCount = 4 + count($results);
  $activeSheet->getStyle('A4:B' . $resultsCount)->applyFromArray($allBorders);

    $header = array('Events Current', 'Event Type');
    $rowNum = $resultsCount + 2;
    $initialRowNum = $rowNum;
    $colNum = 0;
    foreach ($header as $value) {
        $activeSheet->setCellValueByColumnAndRow($colNum, $rowNum, $value);
        $colNum++;
    }

    $rowNum = $initialRowNum + 1;
    $colNum = 0;
    foreach($results = get_current_event_count_by_type_mysql() as $result) {
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->node_count);
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, ($result->event_type != '') ? $result->event_type : 'None');
        $rowNum++;
        $colNum = 0;
    }
    $resultsCount = $initialRowNum + count($results);
    $activeSheet->getStyle('A'.$initialRowNum.':B' . $resultsCount)->applyFromArray($allBorders);
    $activeSheet->getStyle('A'.$initialRowNum.':B'.$initialRowNum)->applyFromArray($styleArray);

         
  /**
   * event count by agency
   */
  $header = array('Event Expired Count by', 'Agency');
  $colNum = 3;
  foreach ($header as $value) {
    $activeSheet->setCellValueByColumnAndRow($colNum, 4, $value);
    $colNum++;
  }
  $rowNum = 5;
  $colNum = 3;

  $final_list = GetEventsByAgencies(0);

    /**
     * Loop through the new array after organization mapping and print the output on the excel sheet.
     */
  foreach($final_list as $key => $value) {
      $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $value);
      $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $key);
      $rowNum++;
      $colNum = 3;
  }

  $resultsCount = 4 + count($final_list);
  $activeSheet->getStyle('D4:E' . $resultsCount)->applyFromArray($allBorders);

    $header = array('Event Current Count by', 'Agency');
    $rowNum = $resultsCount + 2;
    $initialRowNum = $rowNum;
    $colNum = 3;
    foreach ($header as $value) {
        $activeSheet->setCellValueByColumnAndRow($colNum, $rowNum, $value);
        $colNum++;
    }

    $rowNum = $initialRowNum + 1;
    $colNum = 3;

    $final_list = GetEventsByAgencies(1);
    foreach($final_list as $key => $value) {
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $value);
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $key);
        $rowNum++;
        $colNum = 3;
    }
    $resultsCount = $initialRowNum + count($final_list);
    $activeSheet->getStyle('D'.$initialRowNum.':E' . $resultsCount)->applyFromArray($allBorders);
    $activeSheet->getStyle('D'.$initialRowNum.':E'.$initialRowNum)->applyFromArray($styleArray);

  /**
   * print value for events entered manually
   */
  $header = array('Events Expired', 'User Name');
  $colNum = 6;
  foreach ($header as $value) {
    $activeSheet->setCellValueByColumnAndRow($colNum, 4, $value);
    $colNum++;
  }
  $rowNum = 5;
  $colNum = 6;
  foreach($results = get_expired_event_manual_entry_users_mysql() as $result) {
    $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->node_count);
    $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, ($result->user_name != null) ? $result->user_name : 'Anonymous');
    $rowNum++;
    $colNum = 6;
  }
  $resultsCount = 4 + count($results);
  $activeSheet->getStyle('G4:H' . $resultsCount)->applyFromArray($allBorders);

    $header = array('Events Current', 'User Name');
    $rowNum = $resultsCount + 2;
    $initialRowNum = $rowNum;
    $colNum = 6;
    foreach ($header as $value) {
        $activeSheet->setCellValueByColumnAndRow($colNum, $rowNum, $value);
        $colNum++;
    }

    $rowNum = $initialRowNum + 1;
    $colNum = 6;
    foreach($results = get_current_event_manual_entry_users_mysql() as $result) {
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->node_count);
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->user_name);
        $rowNum++;
        $colNum = 6;
    }
    $resultsCount = $initialRowNum + count($results);
    $activeSheet->getStyle('G'.$initialRowNum.':H' . $resultsCount)->applyFromArray($allBorders);
    $activeSheet->getStyle('G'.$initialRowNum.':H'.$initialRowNum)->applyFromArray($styleArray);
  
  /**
   * Count and names of Feed Importers
   */
  $header = array('Events Expired', 'Feed Importer');
  $colNum = 9;
  foreach ($header as $value) {
    $activeSheet->setCellValueByColumnAndRow($colNum, 4, $value);
    $colNum++;
  }
  $rowNum = 5;
  $colNum = 9;
  foreach($results = get_expired_event_feed_importers_name_mysql() as $result) {
    $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->node_count);
    $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->user_name);
    $rowNum++;
    $colNum = 9;
  }
  $resultsCount = 4 + count($results);
  $activeSheet->getStyle('J4:K' . $resultsCount)->applyFromArray($allBorders);

    $header = array('Events Current', 'Feed Importer');
    $rowNum = $resultsCount + 2;
    $initialRowNum = $rowNum;
    $colNum = 9;
    foreach ($header as $value) {
        $activeSheet->setCellValueByColumnAndRow($colNum, $rowNum, $value);
        $colNum++;
    }

    $rowNum = $initialRowNum + 1;
    $colNum = 9;
    foreach($results = get_current_event_feed_importers_name_mysql() as $result) {
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->node_count);
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, ($result->user_name != '') ? $result->user_name : 'Anonymous');
        $rowNum++;
        $colNum = 9;
    }
    $resultsCount = $initialRowNum + count($results);
    $activeSheet->getStyle('J'.$initialRowNum.':K' . $resultsCount)->applyFromArray($allBorders);
    $activeSheet->getStyle('J'.$initialRowNum.':K'.$initialRowNum)->applyFromArray($styleArray);

  /**
   * Create a new worksheet called "Event Detail" $secondSheet
   */
  $secondSheet = new PHPExcel_Worksheet($sheet, 'Event Detail');
  $sheet->addSheet($secondSheet, 1);
  $secondSheet = $sheet->getSheet(1);
  $secondSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
  $secondSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
  $secondSheet->getStyle('A1:N1')->applyFromArray($styleArray);
  
  // Loop to autoSize culumns width
  for ($col = 'A'; $col <= 'M'; $col++) {
    $secondSheet->getColumnDimension($col)->setAutoSize(true);
  }
  $secondSheet->getColumnDimension('N')->setWidth(60);
  
  // Print column table labels
  $header = array('Event Title', 'Detail Description', 'Event Start Date', 'Event End Date', 'Start Time', 'End Time', 'Address 1', 'Address 2', 'City', 'State', 'Zip Code', 'Country', 'Organization', 'Learn More URL');
  foreach ($header as $key => $value) {
    $secondSheet->setCellValueByColumnAndRow($key, 1, $value);
  }
  
  // Print cell values
  $rowNum = 2;
  $colNum = 0;
  foreach ($results = current_events_detail_list_mysql() as $result) {
    $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->title);
    $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, strip_tags($result->field_event_detail_desc_value));
    $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, date('M-d-Y', strtotime($result->field_event_date_value)));
    $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, (($result -> field_event_date_value2) != null) ? date('M-d-Y', strtotime($result->field_event_date_value2)) : $result->field_event_date_value2);
    $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, date('H:i:s', strtotime($result->field_event_date_value)));
    $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, date('H:i:s', strtotime($result->field_event_date_value2)));
    $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->field_event_address_1_value);
    $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->field_event_address_2_value);
    $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->field_event_city_value);
    $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->field_event_state_value);
    $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->field_event_zip_value);
    $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->field_event_country_value);
    $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->field_program_org_tht_owns_prog_value);
    $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->field_event_url_url);
    //$secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $GLOBALS['base_url'] . '/node/' . $result->nid);

    $rowNum++;
    $colNum = 0;
  }
  $resultsCount = 1 + count($results);
  $secondSheet->getStyle('A1:N' . $resultsCount)->applyFromArray($allBorders);
            
  /**
   * redirect output to client browser
   */
  header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment;filename="event-summary.xls"');
  header('Cache-Control: max-age=0');
  $objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
  $objWriter->save('php://output');

  return;
}

function GetEventsByAgencies($status){
    $organization_arrays = MappingArray();
    $final_list = array();
    /**
     * Loop through the results and replace the agency's name after mapping with the organization list we have.
     * After mapping, create a new array with organization name as the key and count as the value.
     * If after mapping, the organization name already exists in the new array as a key, sum up the count and replace the value of that key.
     */
    if ($status == 0){
        $results = get_expired_event_count_by_agency_mysql();
    }
    else{
        $results = get_current_event_count_by_agency_mysql();
    }

    foreach($results as $result) {
        $org = '';
        if ($result->agency_name != '' && $result->agency_name != null  && $result->agency_name != 'none'){
            $org = OrganizationMapping($organization_arrays,$result->agency_name);
        }else{
            $org = 'None';
        }

        $count = $result->node_count;
        if (array_key_exists($org, $final_list)){
            $final_list[$org] = (string)(intval($final_list[$org]) + intval($count));
        }
        else{
            $final_list[$org] = $count;
        }
    }

    return $final_list;
}

function MappingArray(){
    $org_mappings = array();
    $mapping = excelToArray('sites/all/modules/custom/custom_report/events/organization_mapping.xls');
    $agency_org_mappings = array();
    foreach ( $mapping as $key => $organization ){
        $agency_org_mappings[$organization['assoc']['Agency']] = trim($organization['assoc']['Organization']);
    }

    $distinct_org_names = GetUniqueValues($agency_org_mappings);
    foreach($distinct_org_names as $agency => $org){
        $agencies = array_keys($agency_org_mappings, trim($org));
        $org_mappings[$org] = $agencies;
    }

    /*$org_mappings=array(
        "D.O. State Program" => array("DOS","Department of State"),
        "DOC Program" => array("doc","Department of Commerce"),
        "DoD Program" => array("dod","Department of Defense"),
        "EEOC" => array("EEOC","Equal Employment Opportunity Commission"),
        "EPA" => array("Environmental Protection Agency"),
        "Export-Import Bank Program" => array("Export-Import"),
        "FCC" => array("FCC","Federal Communications Commission"),
        "GSA Program" => array("GSA","General Services Administration"),
        "IRS Program" => array("Internal Revenue Service"),
        "OPIC" => array("Overseas Private Investment Corporation"),
        "SBA Program" => array("sba","Small Business Administration","Career Works","MA District Office","SCORE"),
        "SBDC" => array("SBDC","Small Business Development Center","Small Business Center","Small Busniness Development Center"),
        "Treasury Program" => array("Treasury"),
        "USDA Program" => array("USDA","of Agriculture"),
        "ED" => array("of Education"),
        "DOE" => array("of Energy","Dept. of Energy"),
        "HHS" => array("Health and Human Services"),
        "DHS" => array("of Homeland Security"),
        "HUD" => array("of Housing and Urban Development"),
        "DOJ" => array("of Justice"),
        "DOL" => array("of Labor"),
        "DOI" => array("of the Interior"),
        "U.S. Department of Transportation (DOT)" => array("DOT","of Transportation"),
        "USTDA" => array("USTDA","United States Trade and Development Agency"),
        "SSA" => array("SSA","Social Security Administration"),
        "VA Program" => array("Veterans Affairs","VA Program"),
        "WH" => array("White House"),
        "ITA" => array("International Trade Administration"),
        "NSF" => array("NSF","National Science Foundation"),
    );*/

    $org_exact_mappings=array(
        "IRS Program" => array("IRS","EDD/IRS"),
        "ED" => array("ED"),
        "DOE" => array("DOE"),
        "OPIC"=> array("OPIC"),
        "EPA" => array("EPA"),
        "VA Program" => array("VA"),
        "ITA" => array("ITA"),
        "WH" => array("WH"),
    );

    return array($org_mappings, $org_exact_mappings);

}

function GetUniqueValues($array) {
    return array_intersect_key(
        $array,
        array_unique(array_map("StrToLower",$array))
    );
}

/**
 * function to map the organizations coming from the feed importers
 */
function OrganizationMapping($organization_mapping_arrays, $result){
    $org_exact_mappings = $organization_mapping_arrays[1];
    $org_mappings = $organization_mapping_arrays[0];

    if($result!=''){
        foreach($org_exact_mappings as $generic_org => $mappings){
            foreach($mappings as $target_str){
                if(strcasecmp($result,$target_str)===0){
                    //watchdog("events mapping - exact match",$target_str." mapped to ".$generic_org);
                    return $generic_org;
                }
            }
        }
        foreach($org_mappings as $generic_org => $mappings){
            foreach($mappings as $target_str){
                if(stripos($result,$target_str)!==FALSE){
                    return $generic_org;
                }
            }
        }
    }
    return $result;
}
