<?php
global $resultsToShow;;
$resultsToShow = 9;
function getEvents($client, $searchFilters, $row_start=0){

    $query = $client->createSelect();

    $query->setQuery(setEventSearchString($searchFilters));

    // set start and rows param (comparable to SQL limit) using fluent interface
    global $resultsToShow;
    $results_per_page = $resultsToShow;
    $row_start = $row_start * $results_per_page;
    $query->setStart($row_start)->setRows($results_per_page);
    // set fields to fetch (this overrides the default setting 'all fields')
    $query->setFields(array('score', 'entity_id', 'label','ds_field_event_date'));
    //$query->addSort(array('score' => 'desc', 'ds_field_event_date' => 'asc'));
    $query->addSort('score desc, ds_field_event_date asc');
    $documents = $client->select($query);
    return $documents;
}
function setEventSearchString($searchFilters){
    $queryString = "";
    $keyword = htmlspecialchars($searchFilters['keyword']);
    $applySubmit = htmlspecialchars($searchFilters['applySubmit']);
    if($keyword && !$applySubmit) $queryString = "(bundle:event AND spell:" . solrEscapeQuery($keyword);
    else {
        $field_event_type = $searchFilters['field_event_type'];
        $field_program_industry = $searchFilters['field_program_industry'];
        $field_program_org_tht_owns_prog = $searchFilters['field_program_org_tht_owns_prog'];
        $field_event_state = $searchFilters['field_event_state'];
        $queryString = "(bundle:event";
        //All the following variables come from the extract call
        foreach($field_event_type as $index => $event_type)
            $queryString .= " OR ts_field_event_type:" . solrEscapeQuery(htmlspecialchars($event_type));
        foreach($field_program_industry as $index => $industry)
            $queryString .= " OR ts_field_program_industry:" . solrEscapeQuery(htmlspecialchars($industry));
        foreach($field_program_org_tht_owns_prog as $index => $organization)
            $queryString .= " OR ts_field_program_org_tht_owns_prog:" . solrEscapeQuery(htmlspecialchars($organization));
        foreach($field_event_state  as $index => $state)
            $queryString .= " OR ts_field_event_state:" . solrEscapeQuery(htmlspecialchars($state));
        $startDate = htmlspecialchars($searchFilters['startDate']);
        $endDate = htmlspecialchars($searchFilters['endDate']);
        if(!$startDate) $queryString .= ' AND ds_field_event_date:[NOW TO *]';
        else {
            $startDate = new DateTime($startDate);
            $endDate = new DateTime($endDate);
            if (!$endDate) $endDate = $startDate->modify('+1 day');
            $startDate = $startDate->format('Y-m-d') . 'T00:00:01Z';
            $endDate = $endDate->format('Y-m-d') . 'T23:59:59Z';
            $queryString .= " AND ds_field_event_date:[$startDate TO $endDate]";
        }
    }
    $queryString .= ")";
    dsm($queryString);
    return $queryString;
}

function printPaginationLinks($numResults, $start){
    global $resultsToShow;
    $numPages = ceil($numResults/$resultsToShow);
    if($numPages <= 1) return "";
    $linksToLeft = floor($resultsToShow / 2);
    $linksPrintedCount = 0;

    $links = "<ul id='pagination-links' class='pager'>";
    //We know that we have pages behind us, print out a prev link
    if(($prev = $start - 1) >= 0) $links .= "<li class='pager-previous'><a href='javascript:void(0)' onClick=\"jQuery('#hidden-page-number').val($prev); jQuery('#apply-submit').click();\">&laquo</a></li>";
    //we start at the left most page to print, skipping any that are less than 0
    for($i = ($start - $linksToLeft); $i <= $start; $i++){
        //continue if we are before the first page
        if($i < 0) continue;
        $pageNum = $i + 1;
        if($i == $start) $links .= "<li class='pager-current'>$pageNum</li>";
        else $links .="<li><a href='javascript:void(0)' onClick=\"jQuery('#hidden-page-number').val($i); jQuery('#apply-submit').click();\">$pageNum</a></li>";
        $linksPrintedCount++;
    }
    $remainingLinks = $resultsToShow - $linksPrintedCount;
    for($i = $start + 1; $i <= ($start + $remainingLinks); $i++){
        if($i >= $numPages) break;
        $pageNum = $i + 1;
        $links .= "<li><a href='javascript:void(0)' onClick=\"jQuery('#hidden-page-number').val($i); jQuery('#apply-submit').click();\">$pageNum</a></li>";
    }
    //We know that we have pages behind us, print out a prev link
    if(($next = $start + 1) < $numPages) $links .= "<li class='pager-ellipsis'>...</li><li class='pager-next'><a href='javascript:void(0)' onClick=\"jQuery('#hidden-page-number').val($next); jQuery('#apply-submit').click();\"></a></li>";
    return $links . "</ul>";
}
