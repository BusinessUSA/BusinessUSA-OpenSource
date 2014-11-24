<?php
require_once DRUPAL_ROOT . '/sites/all/libraries/PHPExcel/PHPExcel.php';
require_once DRUPAL_ROOT . '/sites/all/modules/custom/custom_report/common.inc';
require_once 'services_mysql.inc';

function downloadServicesPHPExcelFile() {
  $sheet = new PHPExcel();
  $sheet->getProperties()
        ->setCreator('business.usa.gov')
        ->setLastModifiedBy('business.usa.gov')
        ->setTitle('Services Report')
        ->setKeywords('Services');
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
  $activeSheet->setTitle('Services Summary');
  $activeSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE)->setFitToWidth(1)->setFitToHeight(0);
  $activeSheet->getHeaderFooter()->setOddHeader('&C&B&16' . $sheet->getProperties()->getTitle())->setOddFooter('&CPage &P of &N' );

    $activeSheet->setCellValue('A1', 'Services Expired');
    foreach($results = get_expired_count_mysql('services') as $result) {
        $activeSheet->setCellValue('A2', $result->count);
    }

    /**
     * Current event count page
     */
    $activeSheet->setCellValue('B1', 'Services Current');
    foreach($results = get_current_count_mysql('services') as $result) {
        $activeSheet->setCellValue('B2', $result->count);
    }

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
     * service count by organization
     */
    // Print column table labels
    $header = array('Services Count Expired', 'Organization');
    foreach ($header as $key => $value) {
        $activeSheet->setCellValueByColumnAndRow($key, 4, $value);
    }
    $rowNum = 5;
    $colNum = 0;
    foreach($results = get_service_count_by_organization_mysql(0) as $result) {
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->node_count);
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, ($result->org_name != '') ? $result->org_name : 'None');
        $rowNum++;
        $colNum = 0;
    }
    $resultsCount = 4 + count($results);
    $activeSheet->getStyle('A4:B' . $resultsCount)->applyFromArray($allBorders);

    $header = array('Service Count Current', 'Organization');
    $rowNum = $resultsCount + 2;
    $initialRowNum = $rowNum;
    $colNum = 0;
    foreach ($header as $value) {
        $activeSheet->setCellValueByColumnAndRow($colNum, $rowNum, $value);
        $colNum++;
    }

    $rowNum = $initialRowNum + 1;
    $colNum = 0;
    foreach($results = get_service_count_by_organization_mysql(1) as $result) {
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->node_count);
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, ($result->org_name != '') ? $result->org_name : 'None');
        $rowNum++;
        $colNum = 0;
    }
    $resultsCount = $initialRowNum + count($results);
    $activeSheet->getStyle('A'.$initialRowNum.':B' . $resultsCount)->applyFromArray($allBorders);
    $activeSheet->getStyle('A'.$initialRowNum.':B'.$initialRowNum)->applyFromArray($styleArray);

    /**
     * service count by agency
     */
    $header = array('Service Count Expired', 'Agency');
    $colNum = 3;
    foreach ($header as $value) {
        $activeSheet->setCellValueByColumnAndRow($colNum, 4, $value);
        $colNum++;
    }
    $rowNum = 5;
    $colNum = 3;
    foreach($results = get_service_count_by_agency_mysql(0) as $result) {
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->node_count);
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, ($result->agency_name != '') ? $result->agency_name : 'None');
        $rowNum++;
        $colNum = 3;
    }
    $resultsCount = 4 + count($results);
    $activeSheet->getStyle('D4:E' . $resultsCount)->applyFromArray($allBorders);

    $header = array('Service Count Current', 'Agency');
    $rowNum = $resultsCount + 2;
    $initialRowNum = $rowNum;
    $colNum = 3;
    foreach ($header as $value) {
        $activeSheet->setCellValueByColumnAndRow($colNum, $rowNum, $value);
        $colNum++;
    }

    $rowNum = $initialRowNum + 1;
    $colNum = 3;
    foreach($results = get_service_count_by_agency_mysql(1) as $result) {
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->node_count);
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, ($result->agency_name != '') ? $result->agency_name : 'None');
        $rowNum++;
        $colNum = 3;
    }
    $resultsCount = $initialRowNum + count($results);
    $activeSheet->getStyle('D'.$initialRowNum.':E' . $resultsCount)->applyFromArray($allBorders);
    $activeSheet->getStyle('D'.$initialRowNum.':E'.$initialRowNum)->applyFromArray($styleArray);


    /**
     * print value for services entered manually
     */
    $header = array('Service Expired', 'User Name');
    $colNum = 6;
    foreach ($header as $value) {
        $activeSheet->setCellValueByColumnAndRow($colNum, 4, $value);
        $colNum++;
    }
    $rowNum = 5;
    $colNum = 6;
    foreach($results = get_manual_entry_users_mysql('services', 0) as $result) {
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->node_count);
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, ($result->user_name != null) ? $result->user_name : 'Anonymous');
        $rowNum++;
        $colNum = 6;
    }
    $resultsCount = 4 + count($results);
    $activeSheet->getStyle('G4:H' . $resultsCount)->applyFromArray($allBorders);

    $header = array('Service Current', 'User Name');
    $rowNum = $resultsCount + 2;
    $initialRowNum = $rowNum;
    $colNum = 6;
    foreach ($header as $value) {
        $activeSheet->setCellValueByColumnAndRow($colNum, $rowNum, $value);
        $colNum++;
    }

    $rowNum = $initialRowNum + 1;
    $colNum = 6;
    foreach($results = get_manual_entry_users_mysql('services', 1) as $result) {
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
    $header = array('Service Expired', 'Feed Importer');
    $colNum = 9;
    foreach ($header as $value) {
        $activeSheet->setCellValueByColumnAndRow($colNum, 4, $value);
        $colNum++;
    }
    $rowNum = 5;
    $colNum = 9;
    foreach($results = get_feed_importers_name_mysql('services', 0) as $result) {
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->node_count);
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->user_name);
        $rowNum++;
        $colNum = 9;
    }
    $resultsCount = 4 + count($results);
    $activeSheet->getStyle('J4:K' . $resultsCount)->applyFromArray($allBorders);

    $header = array('Service Current', 'Feed Importer');
    $rowNum = $resultsCount + 2;
    $initialRowNum = $rowNum;
    $colNum = 9;
    foreach ($header as $value) {
        $activeSheet->setCellValueByColumnAndRow($colNum, $rowNum, $value);
        $colNum++;
    }

    $rowNum = $initialRowNum + 1;
    $colNum = 9;
    foreach($results = get_feed_importers_name_mysql('services', 1) as $result) {
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->node_count);
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, ($result->user_name != null) ? $result->user_name : 'Anonymous');
        $rowNum++;
        $colNum = 9;
    }
    $resultsCount = $initialRowNum + count($results);
    $activeSheet->getStyle('J'.$initialRowNum.':K' . $resultsCount)->applyFromArray($allBorders);
    $activeSheet->getStyle('J'.$initialRowNum.':K'.$initialRowNum)->applyFromArray($styleArray);

    /**
     * Create a new worksheet called "Event Detail" $secondSheet
     */
    $secondSheet = new PHPExcel_Worksheet($sheet, 'Services Detailed');
    $sheet->addSheet($secondSheet, 1);
    $secondSheet = $sheet->getSheet(1);
    $secondSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
    $secondSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
    $secondSheet->getStyle('A1:N1')->applyFromArray($styleArray);

    /**
     * Total programs content type count
     */
    $secondSheet->setCellValue('A1', 'Content Count');
    foreach($results = get_current_event_count_mysql() as $result) {
        $secondSheet->setCellValue('A2', $result->count);
    }

    for ($col = 'A'; $col <= 'M'; $col++) {
        $secondSheet->getColumnDimension($col)->setAutoSize(true);
    }
    $secondSheet->getColumnDimension('N')->setWidth(60);


    /**
   * print value for events entered manually
   */
  $header = array('Service Title', 'Detail Description', 'Eligibility', 'Learn More URL', 'Industry', 'City', 'Ownership', 'Executive Summary', 'Agency', 'Program Office within Organization');
  foreach ($header as $key => $value) {
      $secondSheet->setCellValueByColumnAndRow($key, 1, $value);
  }
  $rowNum = 2;
  $colNum = 0;
  foreach($results = _services_mysql() as $result) {
    $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->title);
    $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->field_services_detail_desc_value);
    $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->field_services_eligibility_value);
    $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->field_services_ext_url_url);
    $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->field_program_industry_value);
    $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->field_program_city_value);
    $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->field_program_owner_share_value);
    $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->field_services_purpose_value);
    $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->field_services_agency_value);
    $secondSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->field_services_office_in_org_value);
    $rowNum++;
    $colNum = 0;
  }
            
  /**
   * redirect output to client browser
   */
  header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment;filename="services-report.xls"');
  header('Cache-Control: max-age=0');
  $objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
  $objWriter->save('php://output');

  return;
}
