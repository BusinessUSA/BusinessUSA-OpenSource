<?php
	
/** Error reporting */
error_reporting(E_ALL);
ini_set('include_path', ini_get('include_path').';../Classes/');

/** PHPExcel */
include 'PHPExcel.php';
include 'PHPExcel/Writer/Excel2007.php';
$objPHPExcel = new PHPExcel();

// Set properties
$objPHPExcel->getProperties()->setCreator("Business USA");
$objPHPExcel->getProperties()->setLastModifiedBy("Business USA");
$objPHPExcel->getProperties()->setTitle("Business USA - CMS Reports");
$objPHPExcel->getProperties()->setSubject("Business USA - CMS Reports");
$objPHPExcel->getProperties()->setDescription("Business USA CMS Reports.");

// Add some data
$objPHPExcel->setActiveSheetIndex(0);
myExcel_WriteValuesToActiveRow(
	$objPHPExcel,
	array(
		'this',
		'is a',
		'test'
	)
);

// Save sheet
$objPHPExcel->getActiveSheet()->setTitle('Simple');
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save('BusinessUSA - CMS Reports.xlsx');

function myExcel_getActiveRow() {
	global $myExcel_myActiveRow;
	if ( !isset($myExcel_myActiveRow) ) {
		$myExcel_myActiveRow = 1;
	}
	return $myExcel_myActiveRow;
}
function myExcel_setActiveRow($rowNumber) {
	global $myExcel_myActiveRow;
	$myExcel_myActiveRow = $rowNumber;
	return $myExcel_myActiveRow;
}
function myExcel_WriteValuesToActiveRow(&$objPHPExcel, $arrValues) {
	for ( $x = 0 ; $x < count($arrValues) ; $x++ ) {
		$columnLetter = chr( ord('A') + $x );
		$objPHPExcel->getActiveSheet()->SetCellValue(
			$columnLetter . myExcel_getActiveRow(),
			$arrValues[$x]
		);
	}
}

