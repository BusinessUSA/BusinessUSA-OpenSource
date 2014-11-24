<?php
/*

    [--] PURPOSE [--]
    
    This script is used to lookup which ["Export.gov Micro-Site Page"] node in the database corresponds to 
    a [targeted] site-ripped page from export.gov.
    
    [--] NOTES TO DEVS [--]
    
    - If you are looking for the template (markup) that results from this script, you are looking in the 
    wrong place, refer to the export-landing-page.tpl.php file instead.
    - This template may redirect the user to another URL, if the $_SERVER['QUERY_STRING'] is a url 
    that exists in the spreadsheet at sites/default/files/export-dashboard-mappings.xls
    
    [--] IMPLEMENTATION [--]
    
    This page shall assume $_SERVER['QUERY_STRING'] as the export.gov target (URL).
    This target shall be a URL path on export.gov, minus the "http://export.gov/" part.

    i.e. when targeting the page located at http://export.gov/finance/eg_main_018098.asp ...
    the valid target for this script would be: finance/eg_main_018098.asp
    
    This script will test to see if the target exists in the [export-dashboard-mappings.xls] spreadsheet, if it does,
    the user will be redirected to another URL, the rendering of that page will be handeled by Coder Bookmark: CB-JDSQWX0-BC
    
    This script will lookup nodes in the database based on the "Original Source URL" field, for more information 
    on this field, refer to: https://dev.business.usa.reisys.com/admin/structure/types/manage/export-gov-micro-site-page/fields

    When the target node is found, this script will invoke the export-landing-page.tpl.php template
    
    
    [!!] NOTICE [!!]
    
    This script will print direct output, it WILL NOT return output.
    
*/

    // Get everything in the address bar in the clients breowser following the question-mark (?)
    if ( empty($_SERVER['QUERY_STRING']) ) {
        print 'Please provide URL-query';
        return; // Cease rendering this script
    } else {
        $target = $_SERVER['QUERY_STRING'];
    }

    //BUSUSA-2387
    $override_indexasp_replace = array("industry/aerospace",);
    // Validating expectations of target - This target shall be a URL path on export.gov, minus the "http://export.gov" part.
    $target = str_replace('http://export.gov/', '', $target);
    $target = str_replace('http://www.export.gov/', '', $target);
    if(preg_match("/^industry\/.+\/index.asp$/",$target)!==false){
        $target = str_replace('index.asp', '', $target);
    }
    else{
        $target = str_replace('/index.asp', '', $target);
    }
    $target = ltrim($target, '/');
    if ( substr($target, 0, 3) == '../' ) {
        $target = substr($target, 3);
    }
    
    // Validating expectations of target - This should be a link to export.gov, due to the above code, if there is a "http://" in $target, then this is a link to another domain.
    if ( strpos($target, 'http://') !== false ) {
        print 'Error - this script is designed only to synch/return/recall content from export.gov, not other domains.';
        return;
    }
    
    // We will not handle page rips involving URL-queries, in this given case, redirect the user to a page without the [additional] URL-query
    if ( strpos($target, '?') !== false ) {
        $target = explode('?', $target);
        $target = $target[0];
        @ob_end_clean();
        while (@ob_end_clean());
        header('Location: /export-portal?' . $target);
        exit();
    }
    
    // Check if the target [url] is referenced on the export-dashboard-mappings.xls spreadsheet
    if ( function_exists('excelToArray') == false ) { // Ensure we have declared our helper functions for use with the PHPExcel library
        include_once('sites/all/libraries/PHPExcelHelper/phpexcel-helper-functions.php');
    }
    $mappingsFilePath = variable_get('exporter_dashboard_mappings_filepath', drupal_get_path('module', 'exporter_dashboard') . '/export-dashboard-mappings.xls' );
    $mappings = excelToArray($mappingsFilePath); // Load all mappings for the Export Dashboard - load this information into an array. Note: excelToArray() is defined in phpexcel-helper-functions.php
    foreach ( $mappings as $mapping ) { // Foreach row in this spreadsheet...
        $linkOnThisRow = trim($mapping['assoc']['dashboard_link_url'], '/ ');
        $linkOnThisRow = str_replace('http://www.export.gov/', '', $linkOnThisRow);
        $linkOnThisRow = str_replace('http://export.gov/', '', $linkOnThisRow);
        $linkOnThisRow = str_replace('www.export.gov/', '', $linkOnThisRow);
        $linkOnThisRow = str_replace('export.gov/', '', $linkOnThisRow);
        $linkOnThisRow = str_replace('/index.asp', '', $linkOnThisRow);
        $linkOnThisRow = trim($linkOnThisRow, '/ ');
        if ( trim($target, '/ ') === $linkOnThisRow ) {
            
            // If this line is hit, then that means the target (url) was found in the spreadhseet. We shall redirect the user
            $redirectLink = '/export/' . $mapping['assoc']['dashboard_cateory_title'] . '/';
            if ( strval($mapping['assoc']['dashboard_link_title']) === '' && strval($mapping['assoc']['topic_post']) !== '' ) {
                $redirectLink .= str_replace('/', '-', $mapping['assoc']['topic_post']);
            } else {
                $redirectLink .= str_replace('/', '-', $mapping['assoc']['dashboard_link_title']);
            }
            $redirectLink = str_replace(' ', '-', $redirectLink);
            $redirectLink = str_replace('?', '-', $redirectLink);
            $redirectLink = str_replace('&', '-', $redirectLink);
            
            @ob_end_clean();
            while (@ob_end_clean());
            header('Location: ' . $redirectLink);
            exit();
        }
    }
    
    // Screen-scrape the target (or pull it from cache)
    if ( strpos($target, 'tcc/') === 0 ) {
        $pageData = ripExportDotGovPage( 'http://tcc.export.gov/' . substr($target, 4) ); //  NOTE: ripExportDotGovPage() is defined in ConsumeData-Export.govSiteRip.php
    } elseif ( $target == "neinext-strategic-framework.pdf" ){
        $pageData = ripExportDotGovPage('http://trade.gov/neinext/neinext-strategic-framework.pdf'); //  NOTE: ripExportDotGovPage() is defined in ConsumeData-Export.govSiteRip.php

    } else {
        $pageData = ripExportDotGovPage( 'http://export.gov/' . $target ); //  NOTE: ripExportDotGovPage() is defined in ConsumeData-Export.govSiteRip.php
    }
    
    // Enforce the MIME-Type HTTP-Responce-Header
    foreach ( $pageData['special-headers'] as $specialHeader ) {
        header( $specialHeader );
    }
    
    // If the target export.gov content is an image/pdf/Excel/file-attachment, return it alone, and terminate this PHP thread
    if ( $pageData['is-image'] === true || $pageData['is-file-attachment'] === true ) {
    
        // Destroy every currently held in the output buffer, since we want ONLY data in $pageData['export-data-source'] to be sent to the client-browser
        @ob_end_clean();
        while (@ob_end_clean());
        
        // If no file-name for the attachment was given in the HTTP-response header, set one now
        if ( stripos(implode('-', $pageData['special-headers']), 'filename=') === false ) {
            $fileName =  basename(parse_url($pageData['url'], PHP_URL_PATH));
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
        }
        
        // Send the image/pdf/exel raw-data into the web-socket (to the client-browser)
        print $pageData['export-data-source'];
        
        exit(); // Terminate this PHP thread
    }
    
    // Debug and verbosity
    print '
        <!-- The following debug data is rendered from export-portal.tpl.php -->
        <div class="debug-info debuginfo-ripdata admin-only">
            ' . kprint_r($pageData, true) . '
        </div>
    ';
    
    $variables = array(
        'exportTitle' => $pageData['title'], /* $exportTitle is a variable expected to be declared in the export-landing-page.tpl.php template (see that file for more information) */
        'exportContentHTML' => $pageData['content-html'], /* $exportContentHTML is a variable expected to be declared in the export-landing-page.tpl.php template (see that file for more information) */
        'exportSideBars' => $pageData['navigation-links'], /* $exportSideBars is a variable expected to be declared in the export-landing-page.tpl.php template (see that file for more information) */
        'exportCachePath' => $pageData['storage-filepath']
    );
    
    // With the $pageData obtained, render this information in the appropriate template
    //dsm($variables);
    print theme('export_portal_page', $variables);
    
?>