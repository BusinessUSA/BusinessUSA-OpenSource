<?php
require_once DRUPAL_ROOT . '/sites/all/libraries/PHPExcel/PHPExcel.php';
require_once DRUPAL_ROOT . '/sites/all/modules/custom/custom_report/common.inc';
require_once 'resource_mysql.inc';

function downloadResourceCenterPHPExcelFile() {
  $sheet = new PHPExcel();
  $sheet->getProperties()
        ->setCreator('business.usa.gov')
        ->setLastModifiedBy('business.usa.gov')
        ->setTitle('Resource Center Report')
        ->setKeywords('Resource Center');
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
  $activeSheet->setTitle('Resource Center Summary');
  $activeSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE)->setFitToWidth(1)->setFitToHeight(0);
  $activeSheet->getHeaderFooter()->setOddHeader('&C&B&16' . $sheet->getProperties()->getTitle())->setOddFooter('&CPage &P of &N' );

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

    $activeSheet->setCellValue('A1', 'Resource Center Expired');
    foreach($results = get_expired_count_mysql('appointment_office') as $result) {
        $activeSheet->setCellValue('A2', $result->count);
    }

    $activeSheet->getStyle('A1:B2')->applyFromArray($allBorders);

    /**
     * Current resource centers count page
     */
    $activeSheet->setCellValue('B1', 'Resource Center Current');
    foreach($results = get_current_count_mysql('appointment_office') as $result) {
        $activeSheet->setCellValue('B2', $result->count);
    }

  //Apply cell color fill and border
    $activeSheet->getStyle('A1:B1')->applyFromArray($styleArray);
    $activeSheet->getStyle('A4:B4')->applyFromArray($styleArray);
    $activeSheet->getStyle('D4:E4')->applyFromArray($styleArray);
    $activeSheet->getStyle('G4:H4')->applyFromArray($styleArray);

  // Loop to autoSize culumns width
  for ($col = 'A'; $col <= 'J'; $col++) {
    $activeSheet->getColumnDimension($col)->setAutoSize(true);
  }

    /**
     * Count by office type
     */
    // Print column table labels
    $header = array('Resource Centers Expired', 'Office Type');
    foreach ($header as $key => $value) {
        $activeSheet->setCellValueByColumnAndRow($key, 4, $value);
    }
    $rowNum = 5;
    $colNum = 0;
    foreach($results = get_resource_center_count_by_type_mysql(0) as $result) {
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->node_count);
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, ($result->office_type != '') ? $result->office_type : 'None');
        $rowNum++;
        $colNum = 0;
    }
    $resultsCount = 4 + count($results);
    $activeSheet->getStyle('A4:B' . $resultsCount)->applyFromArray($allBorders);

    $header = array('Resource Centers Current', 'Office Type');
    $rowNum = $resultsCount + 2;
    $initialRowNum = $rowNum;
    $colNum = 0;
    foreach ($header as $value) {
        $activeSheet->setCellValueByColumnAndRow($colNum, $rowNum, $value);
        $colNum++;
    }

    $rowNum = $initialRowNum + 1;
    $colNum = 0;
    foreach($results = get_resource_center_count_by_type_mysql(1) as $result) {
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->node_count);
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, ($result->office_type != '') ? $result->office_type : 'None');
        $rowNum++;
        $colNum = 0;
    }
    $resultsCount = $initialRowNum + count($results);
    $activeSheet->getStyle('A'.$initialRowNum.':B' . $resultsCount)->applyFromArray($allBorders);
    $activeSheet->getStyle('A'.$initialRowNum.':B'.$initialRowNum)->applyFromArray($styleArray);

    /**
     * print value for resource centers entered manually
     */
    $header = array('Resource Centers Expired', 'User Name');
    $colNum = 3;
    foreach ($header as $value) {
        $activeSheet->setCellValueByColumnAndRow($colNum, 4, $value);
        $colNum++;
    }
    $rowNum = 5;
    $colNum = 3;
    foreach($results = get_manual_entry_users_mysql('appointment_office', 0) as $result) {
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->node_count);
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->user_name);
        $rowNum++;
        $colNum = 3;
    }
    $resultsCount = 4 + count($results);
    $activeSheet->getStyle('D4:E' . $resultsCount)->applyFromArray($allBorders);

    $header = array('Resource Centers Current', 'User Name');
    $rowNum = $resultsCount + 2;
    $initialRowNum = $rowNum;
    $colNum = 3;
    foreach ($header as $value) {
        $activeSheet->setCellValueByColumnAndRow($colNum, $rowNum, $value);
        $colNum++;
    }

    $rowNum = $initialRowNum + 1;
    $colNum = 3;
    foreach($results = get_manual_entry_users_mysql('appointment_office', 1) as $result) {
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->node_count);
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->user_name);
        $rowNum++;
        $colNum = 3;
    }
    $resultsCount = $initialRowNum + count($results);
    $activeSheet->getStyle('D'.$initialRowNum.':E' . $resultsCount)->applyFromArray($allBorders);
    $activeSheet->getStyle('D'.$initialRowNum.':E'.$initialRowNum)->applyFromArray($styleArray);

    /**
     * Count and names of Feed Importers
     */
    $header = array('Resource Centers Expired', 'Feed Importer');
    $colNum = 6;
    foreach ($header as $value) {
        $activeSheet->setCellValueByColumnAndRow($colNum, 4, $value);
        $colNum++;
    }
    $rowNum = 5;
    $colNum = 6;
    foreach($results = get_feed_importers_name_mysql('appointment_office', 0) as $result) {
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->node_count);
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, ($result->user_name != null) ? $result->user_name : 'Anonymous');
        $rowNum++;
        $colNum = 6;
    }
    $resultsCount = 4 + count($results);
    $activeSheet->getStyle('G4:H' . $resultsCount)->applyFromArray($allBorders);

    $header = array('Resource Centers Current', 'Feed Importer');
    $rowNum = $resultsCount + 2;
    $initialRowNum = $rowNum;
    $colNum = 6;
    foreach ($header as $value) {
        $activeSheet->setCellValueByColumnAndRow($colNum, $rowNum, $value);
        $colNum++;
    }

    $rowNum = $initialRowNum + 1;
    $colNum = 6;
    foreach($results = get_feed_importers_name_mysql('appointment_office', 1) as $result) {
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->node_count);
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, ($result->user_name != null) ? $result->user_name : 'Anonymous');
        $rowNum++;
        $colNum = 6;
    }
    $resultsCount = $initialRowNum + count($results);
    $activeSheet->getStyle('G'.$initialRowNum.':H' . $resultsCount)->applyFromArray($allBorders);
    $activeSheet->getStyle('G'.$initialRowNum.':H'.$initialRowNum)->applyFromArray($styleArray);


    /**
   * Create a new worksheet called "Resource Center Detail" $secondSheet
   */
  $secondSheet = new PHPExcel_Worksheet($sheet, 'Resource Center Detailed');
  $sheet->addSheet($secondSheet, 1);
  $secondSheet = $sheet->getSheet(1);
  $secondSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
  $secondSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
  $secondSheet->getStyle('A1:N1')->applyFromArray($styleArray);

    /**
     * Total programs content type count
     */
    $secondSheet->setCellValue('A1', 'Content Count');
    foreach($results = get_current_count_mysql('appointment_office') as $result) {
        $secondSheet->setCellValue('A2', $result->count);
    }

    for ($col = 'A'; $col <= 'M'; $col++) {
        $secondSheet->getColumnDimension($col)->setAutoSize(true);
    }
    $secondSheet->getColumnDimension('N')->setWidth(60);

    $header = array('Name', 'Description', 'Link', 'Phone No', 'Email Address', 'Location Name', 'Address Line 1', 'Street Address', 'City', 'State', 'Postal Code', 'Country');
    foreach ($header as $key => $value) {
        $secondSheet->setCellValueByColumnAndRow($key, 1, $value);
    }
    $rowNum = 2;
    $colNum = 0;
    foreach($results = _resource_center_mysql() as $result) {
        $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->title);
        $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->field_appoffice_desc_value);
        $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->field_appoffice_link_value);
        $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->field_appoffice_phone_value);
        $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->field_appoffice_email_value);
        $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->field_appoffice_loc_name_value);
        $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->field_address_line_1_value);
        $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->field_appoffice_address_value);
        $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->field_appoffice_city_value);
        $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->field_appoffice_state_value);
        $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->field_appoffice_postal_code_value);
        $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->field_appoffice_country_value);
        $rowNum++;
        $colNum = 0;
    }
            
  /**
   * redirect output to client browser
   */
  header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment;filename="resource-center-report.xls"');
  header('Cache-Control: max-age=0');
  $objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
  $objWriter->save('php://output');

  return;
}
