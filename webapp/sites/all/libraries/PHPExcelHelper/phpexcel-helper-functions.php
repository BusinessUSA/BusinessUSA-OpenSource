<?php

include_once('sites/all/libraries/PHPExcel/PHPExcel.php');

if ( function_exists('excelToArray') === false ) {
    function excelToArray($spreadsheetPath, $workSheetNumber = 0, $rowwOffset = 0) {
    
        // Include the PHPExcel library
        set_include_path(get_include_path() . PATH_SEPARATOR . 'sites/all/libraries/PHPExcel/');
        include_once 'PHPExcel.php';
        
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
                    $excelHeaders[$col] = $worksheet->getCellByColumnAndRow($col, 1 + $rowwOffset)->getValue();
                }
                
                for ($row = 2 + $rowwOffset; $row <= $highestRow; ++ $row) {
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
        
        // Array cleanup - Check the last rows of the array, if all of the cells (coumns) for this row are null, remove the row
        $rowsInArray_ReverseOrder = array_reverse( array_keys($excelArray) );
        foreach ( $rowsInArray_ReverseOrder as $rowKey ) {
            if ( strval(@implode('', $excelArray[$rowKey]['cols'])) === '' ) { // If there is no information (if all the elements in this array are null)...
                unset( $excelArray[$rowKey] ); // Remove this row from the array
            } else {
                break; // Do not check any more rows in the array
            }
        }
        
        return $excelArray;
    }
}

if ( function_exists('arrayToExcel') === false ) {
    function arrayToExcel($objPHPExcel = null, $rows, $writeArrayKeysAsHeader = false, $rowStartWrite = 1, $setActiveSheetTo = 0, $sheetName = null) {

        include_once('sites/all/libraries/PHPExcel/PHPExcel/Writer/Excel2007.php');

        // Create an object/instance for the output spreadsheet
            if ( is_null($objPHPExcel) ) {
                $objPHPExcel = new PHPExcel();
            }
            myExcel_setActiveRow($rowStartWrite);
            
        // Create new sheet is needed
            $sheetOk = true;
            do {
                try {
                    $objPHPExcel->setActiveSheetIndex($setActiveSheetTo);
                    $sheetOk = true;
                } catch(Exception $e) {
                    $sheetOk = false;
                    $objPHPExcel->createSheet();
                }
            } while( $sheetOk === false );
            
            if ( is_null($sheetName) ) {
                $objPHPExcel->getActiveSheet()->setTitle("Sheet $setActiveSheetTo");
            } else {
                $objPHPExcel->getActiveSheet()->setTitle($sheetName);
            }
            
        // Set basic properties to output spreadsheet
            $objPHPExcel->getProperties()->setCreator("Business USA");
            $objPHPExcel->getProperties()->setLastModifiedBy("Business USA");
            $objPHPExcel->getProperties()->setTitle("Business USA");
            $objPHPExcel->getProperties()->setSubject("Business USA");
            $objPHPExcel->getProperties()->setDescription("Business USA");
        
        // Debug
        if ( strpos(request_uri(), '-DEBUG-NOEXCELWRITE-REPORTWRITE-') !== false ) {
            ob_end_clean();
        }
        
        // Write headders
        if ( $writeArrayKeysAsHeader === true ) {
            $headders = array();
            foreach ($rows[0] as $key=>$cell) {
                $headders[] = $key;
            }
            myExcel_WriteValuesToActiveRow($objPHPExcel, $headders, true);
            // Set the next row as "active" so the next time myExcel_WriteValuesToActiveRow() is called it will write to the next
            myExcel_setActiveRow( myExcel_getActiveRow() + 1 );
        }
        
        // Write rows
        foreach ($rows as $row) {
            // Add a row into the spreadsheet
            myExcel_WriteValuesToActiveRow($objPHPExcel, $row);
            // Set the next row as "active" so the next time myExcel_WriteValuesToActiveRow() is called it will write to the next
            myExcel_setActiveRow( myExcel_getActiveRow() + 1 );
        }
        
        // Debug
        if ( strpos(request_uri(), '-DEBUG-NOEXCELWRITE-REPORTWRITE-') !== false ) {
            flush();
            exit();
        }
        
        myExcel_decideColumnWidths($objPHPExcel, $rows);
        return $objPHPExcel;
    }
}

if ( function_exists('myExcel_getActiveRow') === false ) {
    function myExcel_getActiveRow() {
        global $myExcel_myActiveRow;
        if ( !isset($myExcel_myActiveRow) ) {
            $myExcel_myActiveRow = 1;
        }
        return $myExcel_myActiveRow;
    }
}

if ( function_exists('myExcel_setActiveRow') === false ) {
    function myExcel_setActiveRow($rowNumber) {
        global $myExcel_myActiveRow;
        $myExcel_myActiveRow = $rowNumber;
        return $myExcel_myActiveRow;
    }
}

if ( function_exists('myExcel_WriteValuesToActiveRow') === false ) {
    function myExcel_WriteValuesToActiveRow(&$objPHPExcel, $arrValues, $rowBold = false) {
        $x = 0;
        $activeSheet = $objPHPExcel->getActiveSheet();
        foreach ( $arrValues as $value ) {
            
            // Get cell location i.e. A1 or C3
            $columnLetter = PHPExcel_Cell::stringFromColumnIndex($x);
            $cellLocation = $columnLetter . myExcel_getActiveRow();
            
            // Debug
            if ( strpos(request_uri(), '-DEBUG-NOEXCELWRITE-REPORTWRITE-') !== false ) {
                print "write to $cellLocation with $value <br/>\n";
            } else {
            
                // Set this cell value
                $activeSheet->SetCellValue( $cellLocation, strip_tags($value) );
                
                // Set this cell style
                if ($rowBold === true) {
                    $activeSheet->getStyle($cellLocation)->applyFromArray(
                        array(
                            'font' => array(
                                'bold' => true
                            )
                        )
                    );
                }
            }
            
            $x++;
        }
    }
}

if ( function_exists('myExcel_SetRowBold') === false ) {
    function myExcel_SetRowBold(&$objPHPExcel, $rowId = 1, $columnsInRowCount = 0) {
        $x = 0;
        $activeSheet = $objPHPExcel->getActiveSheet();
        for ( $x = 1 ; $x < $columnsInRowCount ; $x++ ) {
            
            // Get cell location i.e. A1 or C3
            $columnLetter = PHPExcel_Cell::stringFromColumnIndex($x);
            $cellLocation = $columnLetter . $rowId;
            
            // Set this cell style
            $activeSheet->getStyle($cellLocation)->applyFromArray(
                array(
                    'font' => array(
                        'bold' => true
                    )
                )
            );
        }
    }
}

if ( function_exists('myExcel_SetRowAlignment') === false ) {
    function myExcel_SetRowAlignment(&$objPHPExcel, $rowId = 1, $columnsInRowCount = 0, $alignment = PHPExcel_Style_Alignment::HORIZONTAL_CENTER) {
        $x = 0;
        $activeSheet = $objPHPExcel->getActiveSheet();
        for ( $x = 1 ; $x < $columnsInRowCount ; $x++ ) {
            
            // Get cell location i.e. A1 or C3
            $columnLetter = PHPExcel_Cell::stringFromColumnIndex($x);
            $cellLocation = $columnLetter . $rowId;
            
            // Set this cell style
            $activeSheet->getStyle($cellLocation)->getAlignment()->setHorizontal($alignment);
        }
    }
}

if ( function_exists('myExcel_decideColumnWidths') === false ) {
    function myExcel_decideColumnWidths(&$objPHPExcel, $rowsData, $maxCharsWithAuto = 70, $maxWidth = 50) {

        // Make this column auto-width unless it has more than X number of characters
        $cellWithMostCharacters = 0;
        $mostCharCount = 0;
        $x = 0;
        $y = 0;
        
        // For each column
        for ( $x = 0 ; $x < count($rowsData[0]) ; $x++ ) {
            for ( $y = 0 ; $y < count($rowsData) ; $y++ ) {
                
                $rowsData[$y] = array_values($rowsData[$y]);
                $cell = $rowsData[$y][$x];
                if ( strlen($cell) > $mostCharCount ) {
                    $mostCharCount = strlen($cell);
                    $cellWithMostCharacters = array('x' => $x, 'y' => $y);
                }
                
            }
            
            $activeSheet = $objPHPExcel->getActiveSheet();

            if ( $mostCharCount >$maxCharsWithAuto ) {
                $activeSheet->getColumnDimension( chr( ord('A') + $x ) )->setAutoSize(false);
                $activeSheet->getColumnDimension( chr( ord('A') + $x ))->setWidth($maxWidth);
            } else {
                $activeSheet->getColumnDimension( chr( ord('A') + $x ) )->setAutoSize(true);
            }
            
            $mostCharCount = 0;
            $cellWithMostCharacters = null;
        }
    }
}