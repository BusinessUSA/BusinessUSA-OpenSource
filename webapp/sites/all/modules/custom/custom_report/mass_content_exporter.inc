<?php

function downloadAllContentTypes_handler($form, &$form_state){
    $publishedContentOnly = $form_state['input']['published'];
    if (!empty($form_state['input']['content_types'])){
        if ( $form_state['input']['format'] == 'debug' ) {
            $queryInfo = generateNodeDumpSQL($form_state['input']['content_types']);
            dsm( $queryInfo );
            print '<br/><br/><textarea style="height 300px; width: 100%;">' . $queryInfo['query'] . '</textarea>';
        } else {
            contentMassExport($form_state['input']['content_types'], 'DDL', $form_state['input']['format'], intval($publishedContentOnly), 1);
        }
    }

    /*if ( !empty($_GET['mass']['content_types']) ) {
        if ( $_GET['mass']['format'] == 'debug' ) {
            $queryInfo = generateNodeDumpSQL($_GET['mass']['content_types']);
            dsm( $queryInfo );
            print '<br/><br/><textarea style="height 300px; width: 100%;">' . $queryInfo['query'] . '</textarea>';
        } else {
            contentMassExport($_GET['mass']['content_types'], 'DDL', $_GET['mass']['format'], intval($publishedContentOnly), 1);
        }
    }*/
}

function contentMassExport($contentTypeMachineName, $fileWriteTo = null, $format = 'csv', $publishedContentOnly = true, $pullInChunks = true) {

    set_time_limit(900);

    $pullInChunks = intval($pullInChunks);
    $publishedContentOnly = intval($publishedContentOnly);

    // Preparation for direct DL (as a file through the web-browser)
    $doDie = false;
    if ( $fileWriteTo === 'DDL' && $format !== 'debug' ) {
        ob_end_clean(); ob_end_clean(); ob_end_clean();
        header('Content-type: text/csv');
        header("Content-disposition: attachment;filename=BusinessUSA_Content-Dump_of_" . $contentTypeMachineName . "_" . date('Y-m-d') . ".csv");
        $fileWriteTo = null; // Set my file-write to print mode
        $doDie = true; // Terminate by calling exit() for this PHP thread after this function is complete
    }

    // Setup my file-writer
    $write = function ($fileWriteTo, $data) {
        if ( is_null($fileWriteTo) ) {
            print $data;
            flush();
        } else {
            fwrite($fileWriteTo, $data);
            fflush();
        }
    };

    // Get the MySQL queries to run in order to get all the desiered nodes and associated fields
    $nodeDumpSQL = generateNodeDumpSQL($contentTypeMachineName, $publishedContentOnly, false);

    // Write output headers (pre-record write)
    if ($format === 'csv') {

        // Write CSV headers
        $headCount = 0;
        foreach ( $nodeDumpSQL['expect_return_headers'] as $header ) {
            if ( $headCount !== 0 ) { $write($fileWriteTo, ','); }
            $write($fileWriteTo, '"' . $header . '"');
            $headCount++;
        }
        $write($fileWriteTo, "\n");
    }

    // The nodeDumpSQL() function should have given us a query to run which would collect ALL records from the database at once...
    // We want to grab records from the database in small chunks rather than a massive hit to be esier on the database-server.
    $startAtRecord = 0;
    $chunkCount = 50;
    $baseQuery = $nodeDumpSQL['query'];
    do {

        // Let the server "breath"
        sleep(3);

        // Collect the next $chunkCount records from the database
        $query = $baseQuery;
        if ( $pullInChunks === 1 ) {
            $query .= " LIMIT $startAtRecord,$chunkCount";
        }

        // Pull information from MySQL
        db_query('SET group_concat_max_len = 1048576');
        db_query('SET SESSION group_concat_max_len = 1048576');
        $result = db_query($query);

        // Break from this loop if there is not more data to pull from the database
        if ( is_numeric($result->rowCount()) && $result->rowCount() < 1 ) {
            break;
        }

        // Write output records
        if ( $format === 'csv' ) {

            // Write CSV rows
            foreach ($result as $record) {

                // Convert from object to array
                $record = (array) $record;

                // Write each field of this row
                $fieldIndex = 0;
                foreach ( $nodeDumpSQL['expect_return_headers'] as $header ) {
                    if ( $fieldIndex !== 0 ) { $write($fileWriteTo, ','); }
                    $fieldData = $record[$header];
                    $fieldData = str_replace("\"", "\"\"", $fieldData);
                    $fieldData = str_replace(chr(10), "\\n", $fieldData);
                    $fieldData = str_replace(chr(13), "\\n", $fieldData);
                    $write($fileWriteTo, '"' . $fieldData . '"');
                    $fieldIndex++;
                }

                // We are done with this line (row) of this csv file
                $write($fileWriteTo, "\n");
            }

            fclose($fileWriteTo);
        }

        // In the next run of the loop, collect the NEXT 100 records
        $startAtRecord += $chunkCount;

        // Break from this loop if there is not going to be more data to pull from the database
        if ( $pullInChunks !== 1 ) {
            break;
        }

    } while (true);

    if ( $doDie ) {
        exit();
    }
}

function generateNodeDumpSQL($cType, $publishedContentOnly = true, $getCountOnly = false) {

    $publishedContentOnly = intval($publishedContentOnly);

    $fieldStorageInfos = getFiledsOfContentType($cType);
    $expRetHeaders = array(
        'nid' => 'nid',
        'content_type' => 'content_type',
        'link' => 'link',
        'node_creation_date_unix' => 'node_creation_date_unix',
        'node_creation_date_human' => 'node_creation_date_human',
        'node_creation_time_human' => 'node_creation_time_human',
        'node_creation_date_year' => 'node_creation_date_year',
        'node_creation_date_month' => 'node_creation_date_month',
        'node_creation_date_day' => 'node_creation_date_day',
        'node_changed_date_human' => 'node_changed_date_human',
        'is_published' => 'is_published',
        'node_promoted' => 'node_promoted',
        'user_id' => 'user_id',
        'user_name' => 'user_name',
        'node_title' => 'node_title'
    );

    // Build SELECT caluse
    // Also add to the list of headers we should expect to be returned when running this MySQL query
    $selectClause = '';
    $selCount = 0;
    foreach ( $fieldStorageInfos as $fieldStorageInfo ) {
        $tblName = $fieldStorageInfo['storage_sql_table'];
        foreach ( $fieldStorageInfo['storage_sql_fields'] as $mysqlFieldName ) {
            $selCount++;
            if ( $selectClause !== '' ) { $selectClause .= ", \n"; }
            $selectClause .= "    ( SELECT GROUP_CONCAT($mysqlFieldName SEPARATOR ' ++ ') FROM $tblName selTbl$selCount WHERE selTbl$selCount.entity_id = n.nid AND selTbl$selCount.revision_id = n.vid AND selTbl$selCount.deleted = 0 ) AS $mysqlFieldName";
            $expRetHeaders[$mysqlFieldName] = $mysqlFieldName;
        }
    }

    // Build join clause
    $joinClause = '';
    /*foreach ( $fieldStorageInfos as $fieldStorageInfo ) {
        $tblName = $fieldStorageInfo['storage_sql_table'];
        $joinClause .= "LEFT JOIN ( SELECT * FROM $tblName WHERE $tblName.entity_id = n.nid AND $tblName.revision_id = n.vid AND $tblName.deleted = 0 LIMIT 1 ) joinedTbl$tblName ON ( joinedTbl$tblName.entity_id = n.nid ) \n";
    }*/

    // Build the MySQL query with all caluses
    $query = "
        SELECT
            n.nid AS nid,
            n.type AS content_type,
            n.title AS node_title,
            CONCAT('https://data-entry-business.usa.gov/node/', n.nid, '/edit') AS link,
            n.created AS node_creation_date_unix,
            FROM_UNIXTIME(n.created, '%Y-%m-%d') AS node_creation_date_human,
            FROM_UNIXTIME(n.created, '%r') AS node_creation_time_human,
            FROM_UNIXTIME(n.created, '%Y') AS node_creation_date_year,
            FROM_UNIXTIME(n.created, '%m') AS node_creation_date_month,
            FROM_UNIXTIME(n.created, '%d') AS node_creation_date_day,
            FROM_UNIXTIME(n.changed, '%Y-%m-%d') AS node_changed_date_human,
            n.promote AS node_promoted,
            n.status AS is_published,
            usr.uid AS user_id,
            usr.name AS user_name,
            $selectClause
        FROM node n
        $joinClause
        LEFT JOIN users usr ON ( usr.uid = n.uid )
        WHERE n.type = '$cType'
    ";

    // Are we only pulling published content?
    if ( $publishedContentOnly === 1 ) {
        $query .= ' AND n.status=1 ';
    }

    // Debug - Show the query being run
    if ( strpos(request_uri(), '-DEBUG-NODEDUMPSQL-VERBOSE-') !== false ) {
        dsm($query);
    }

    return array(
        'query' => $query,
        'expect_return_headers' => $expRetHeaders
    );
}

/**
 * array getFiledsOfContentType(string).
 *
 * Returns a list of Drupal fields of a given content-type's machine-name, and information on what tables
 * and fields in the database the given Drupal fields are stored in.
 *
 * Returns an array with the structure of...
 * An example return:
 *
 *      return array(

'field_program_city' => array(
'label' => 'City',
'storage_sql_table' => 'field_data_field_program_city',
'storage_sql_fields' => array(
'value' => 'field_data_field_program_city',
'format' => 'field_program_city_format'
)
),

'field_frontpage_daterange' => array(
'label' => 'Front Page Date Range',
'storage_sql_table' => 'field_data_field_frontpage_daterange',
'storage_sql_fields' => array(
'value' => 'field_frontpage_daterange_value',
'value2' => 'field_frontpage_daterange_value2'
)
),

[...]

);
 */
function getFiledsOfContentType($cType) {

    // $rtn will be our return buffer
    $rtn = array();

    // Get a mapping of all fields associated [with all] content-types
    $map = field_info_instances();
    $map = $map['node'];

    // Get details of all fields
    $fieldReadFields = field_read_fields();

    // Get a mapping of all fields associated [with THIS] content-type
    if ( !empty($map[$cType]) ) {
        $mappedFields = $map[$cType];
    } else {
        return false;
    }

    // Foreach field in this content-type...
    foreach ( $mappedFields as $fieldMachineName => $fieldInfoInstance ) {

        // Assume table-name is "field_data_" & the machine-name of this Drupal-field
        $tblName = 'field_data_' . $fieldMachineName;

        $thisFieldData = array(
            'label' => $fieldInfoInstance['label'],
            'storage_sql_table' => $tblName,
            'storage_sql_fields' => array()
        );

        // Get the details of THIS field as returned by field_read_fields()
        $fieldReadField = $fieldReadFields[$fieldMachineName];

        // Add the field-names that hold the values, in the MySQL tables, to the result
        foreach ( $fieldReadField['storage']['details']['sql']['FIELD_LOAD_CURRENT'][$tblName] as $databaseFieldLabel => $databaseFieldName ) {
            $thisFieldData['storage_sql_fields'][$databaseFieldLabel] = $databaseFieldName;
        }

        // If we did not find any fields-names for MySQL...
        if ( count($thisFieldData['storage_sql_fields']) === 0 ) {

            // If it exists, add the field of: machine-name  & '_value'
            if ( db_field_exists($tblName, $fieldMachineName . '_value') ) {
                $thisFieldData['storage_sql_fields']['value'] = $fieldMachineName . '_value';
            }

            // If it exists, add the field of: machine-name  & '_tid'
            if ( db_field_exists($tblName, $fieldMachineName . '_tid') ) {
                $thisFieldData['storage_sql_fields']['tid'] = $fieldMachineName . '_tid';
            }
        }

        // Some validation before we add to the return buffer (double check some attributes of this result)
        if ( !db_table_exists($tblName) || count($thisFieldData['storage_sql_fields']) < 1 ) {
            // Validation failed
        } else {
            // Ok, add this result to the result-set
            $rtn[$fieldMachineName] = $thisFieldData;
        }
    }

    return $rtn;
}