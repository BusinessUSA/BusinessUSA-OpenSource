<?php
//this was showing up twice for some reason, so I had to hack it to only display once
//forgive me
global $count;
$count = 0;
hooks_reaction_add("block_view_alter",
    function (&$data, $block){
        if($block->title == 'Events Filters'){
            global $count;
            $count++;
            if($count != 2){ $data['content'] = ' '; $block->title = ''; return ''; }
            drupal_add_js("sites/all/modules/contrib/jquery_update/replace/ui/ui/minified/jquery-ui.min.js", 'file');
            //the following is a nasty hack to get in the calendar theming, forgive me, I ran out of time figuring this out
            drupal_add_css(
                "sites/all/modules/contrib/jquery_update/replace/ui/themes/base/jquery.ui.theme.css",
                array(
                    'type' => 'external',
                    'group' => CSS_THEME,
                )
            );
            //end hack - womp womp
            $data['content'] = create_events_search_filters($_REQUEST);
        }
    }
);//end hooks reaction add
function create_events_search_filters($searchVariables){
    $keyword = htmlspecialchars($searchVariables['keyword']);
    if(htmlspecialchars($searchVariables['applySubmit']))  $keyword = 'Search Events';
    //the client wants all other form variables ignored if the keyword is set
    //the query builder in events_functions accounds for thisas well
    else $searchVariables= array();
    $startDate = $searchVariables['startDate'];
    $endDate = $searchVariables['endDate'];
    if($startDate && !$endDate) {
        $startDate = new DateTime($searchVariables['startDate']);
        $endDate = new DateTime($searchVariables['endDate']);
        if(!$endDate) $endDate = $startDate->modify('+1 day');
        $startDate->modify('-1 day');
        $startDate = $startDate->format('m/d/Y');
        $endDate = $endDate->format('m/d/Y');
    }
    echo "<div id='event-search-error'></div>";
    echo "<div class='region region-sidebar-first'>";
    echo "<div id='event-search-filters' class='views-exposed-form views-exposed-widgets clearfix'>";
    echo "<form action='/events-search' method='post' id='form-events-listings-and-filters' accept-charset='UTF-8'>";
    echo "<div id='events-text-search'>
             <label for='solr-search-events' class='element-invisible'>Search Events</label>
             <input type='text' id='solr-search-events' name='keyword' value='$keyword' onfocus='jQuery(\"#solr-search-events\").val(\"\");'>
             <input type='submit' class='form-submit solr-search-events-submit' value='Search'>
             <script>
             jQuery('#events-text-search input[type=\"text\"]').keyup(function(event){
                  if(event.keyCode == 13){
                      jQuery('#events-text-search input[type=\"button\"]').click();
                  }
              });
             </script>
          </div>";

    echo "<div id='edit-date-filter-wrapper' class='views-exposed-widget'>
         <label for='date-filter'>Event Dates</label>
         <button id='event-dates' type='button' class='btn btn-danger' data-toggle='collapse' data-target='#date-filter'>Event Dates</button>
         <div id='date-filter' class='views-exposed-widget views-widget collapse in'>
             <div id='clear-event-dates' class='date-padding'>
                <a href='#' onClick='jQuery(\"#edit-date-filter-min-date\").val(\"\"); jQuery(\"#edit-date-filter-max-date\").val(\"\"); return false;'>Clear Dates</a>
             </div>
             <div id='edit-date-filter-min-wrapper' class='date-padding'>
                <div id='edit-date-filter-min'>
                    <label for='edit-date-filter-min-date'>Start date </label>
                    <input class='bef-datepicker form-text' type='text' id='edit-date-filter-min-date' name='startDate' value='$startDate' size='60' maxlength='128' /><br />
                </div>
             </div>
             <div id='edit-date-filter-max-wrapper' class='date-padding'>
                <div id='edit-date-filter-max'>
                  <label for='edit-date-filter-max-date'>End date </label>
                  <input class='bef-datepicker form-text'  type='text' id='edit-date-filter-max-date' name='endDate' value='$endDate' size='60' maxlength='128' />
                </div>
             </div>
         </div>
      </div>";
    $filters = array("Event Type" => "field_event_type", "Industry" => "field_program_industry",
        "Organization" => "field_program_org_tht_owns_prog", "State" => "field_event_state");
    $solr_field_mapping = array("field_program_org_tht_owns_prog" => "sm_field_program_org_tht_owns_pr",
        "field_program_industry"=>"sm_field_program_industry",
        "field_event_state"=>"sm_field_event_state",
        "field_event_type"=>"sm_field_event_type");
    foreach($filters as $label => $filter){
        //this gives us a unique list of filter items that have corresponding event data
        //in other words, we have events that have a filter selected - i.e. Event Type - we only want to show
        //Event Types that actually contain corresponding events in our system
        //This prevents displaying event filters that will return an empty result
        //It also doesn't make sense to make a filter available unless it could return a result
        //**************** IMPORTANT ************************
        //We are getting event data from a feed from SBA.gov, their event types, organizations, etc don't match ours exactly
        //there is a tamper on the import events feed to do some of this mapping. but, the default is to populate
        //our field with their data - i.e. they have bad data, so some of our organizations will have a phone number.
        //In order to avoid showing this bad data as an option for the user, I only show options that are in the list
        //of allowed values in our content type for that field
        $field = field_info_field($filter);
        $allowed_values = list_allowed_values($field);
        $filter_db_column = $filter . '_value';
        $filter_db_table = 'field_data_' . $filter;
        $query = "SELECT DISTINCT($filter_db_column)
              FROM $filter_db_table
              JOIN node n ON $filter_db_table.entity_id = n.nid
              WHERE bundle = 'event' AND n.status != 0
              ORDER BY $filter_db_column";

        $events_with_filter = db_query($query);
        $events_with_filter = $events_with_filter->fetchAllKeyed();
        if(!count($events_with_filter)) continue;
        echo "<div class='views-exposed-widget' id='$filter-wrapper'>
                <label for='$filter'>$label</label>
                <button id='event-dates' type='button' class='btn btn-danger collapsed' data-toggle='collapse' data-target='#$filter-filter'>$label</button>
                <div class='filter-options-wrapper form-checkboxes views-widget form-checkboxes bef-select-as-checkboxes collapse out' id='$filter-filter'>";
        $solr_field_name = $solr_field_mapping[$filter];
        $has_any_filters = FALSE;
        foreach($events_with_filter as $filter_name => $nothing){
            //if the data contained in the database doesn't match our list of allowed values, do not show the filter
            //this will prevent the users from performing a search that returns no results
            if(!array_key_exists($filter_name, $allowed_values)) continue;
            $has_any_filters = TRUE;
            $value = str_replace("'", "\\'", $value);
            $checkboxID = str_replace('_', '-', $filter_name);
            echo "<div class='form-item form-type-bef-checkbox bef-checkboxes'>
                    <input type='checkbox' name='$filter" . "[]" . "' value='$filter_name' id='edit-field-$checkboxID-value'";
            if(in_array($filter_name, $searchVariables[$filter])) echo " checked";
            if($filter == 'field_event_state' || $filter == 'field_event_type') $filter_name = $allowed_values[$filter_name];
            echo ">";
            echo "<label class='option' for='edit-field-$checkboxID-label'>" . ucwords($filter_name) . "</label></div>";
        }
        echo "</div></div>";
        if(!$has_any_filters) print "<script type='text/javascript'>jQuery('#$filter-wrapper').hide();</script>";
    }
    echo "<div id='events-text-search' class='apply-search'>
        <br />
        <input type='hidden' name='page' value='$page' id='hidden-page-number'>
        <input type='submit' value='Apply' name='applySubmit' class='form-submit solr-search-events-submit' id='apply-submit'>
      </div>";
    echo "</form>";
    echo "</div>";
    echo "</div>";
}
?>
