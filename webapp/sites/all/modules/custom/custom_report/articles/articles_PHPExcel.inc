<?php
require_once DRUPAL_ROOT . '/sites/all/libraries/PHPExcel/PHPExcel.php';
require_once 'articles_mysql.inc';

function downloadArticlesPHPExcelFile() {
  $sheet = new PHPExcel();
  $sheet->getProperties()
        ->setCreator('business.usa.gov')
        ->setLastModifiedBy('business.usa.gov')
        ->setTitle('Article Report')
        ->setKeywords('Article');
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
    $activeSheet->setTitle('Article Detailed');
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

    $activeSheet->getStyle('A1:N1')->applyFromArray($allBorders);

    // Loop to autoSize culumns width
    for ($col = 'A'; $col <= 'J'; $col++) {
        $activeSheet->getColumnDimension($col)->setAutoSize(true);
    }

    /**
     * Total programs content type count
     */
    $activeSheet->setCellValue('A1', 'Content Count');
    foreach($results = get_current_count_mysql('article') as $result) {
        $activeSheet->setCellValue('A2', $result->count);
    }

    for ($col = 'A'; $col <= 'M'; $col++) {
        $activeSheet->getColumnDimension($col)->setAutoSize(true);
    }
    $activeSheet->getColumnDimension('N')->setWidth(60);

    $header = array('Title', 'Topic', 'Learn More URL', 'Detailed Text');
    foreach ($header as $key => $value) {
        $activeSheet->setCellValueByColumnAndRow($key, 1, $value);
    }
    $rowNum = 2;
    $colNum = 0;
    foreach($results = _articles_mysql() as $result) {
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->title);
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->field_article_topic_value);
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->field_article_url_url);
        $activeSheet->setCellValueByColumnAndRow($colNum++, $rowNum, $result->field_article_detail_desc_value);
        $rowNum++;
        $colNum = 0;
    }

    /**
     * redirect output to client browser
     */
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="articles-report.xls"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
    $objWriter->save('php://output');

    return;
}
