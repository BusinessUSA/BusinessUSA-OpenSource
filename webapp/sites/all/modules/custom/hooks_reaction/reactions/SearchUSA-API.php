<?php

/**  array apiSearchUSA()
  *  
  *  A function to hit search.usa.gov and return the results in in an array.
  *  
  *  @param $affiliate string - The Affiliate ID, this should be either "fb707f101" (Across business agencies) or "businessacrossgovernment" (Across all government)
  */
function apiSearchUSA($searchQuery, $page = 1, $affiliate = 'fb707f101') {
    
    $searchQuery = str_replace(' ', '%20', $searchQuery);
    $searchApiUrl = "http://search.usa.gov/api/search.json?query=$searchQuery&index=web&affiliate=$affiliate&page=$page";
    $strData = file_get_contents($searchApiUrl);
    if ( $strData === false ) {
        return array(
            'total' => 0,
            'startrecord' => null,
            'endrecord' => null,
            'results' => array(),
            'related' => array()
        );
    }
    $strData = str_replace(chr(238), "", $strData); // bug killer - gets ride of characters that look like boxes (search USA results seem to be littered with them)
    $strData = str_replace("\\ue000", "", $strData); // bug killer - gets ride of characters that look like boxes (search USA results seem to be littered with them)
    $strData = str_replace("\\ue001", "", $strData); // bug killer - gets ride of characters that look like boxes (search USA results seem to be littered with them)
    $arrData = json_decode($strData, true);
    return $arrData;
}