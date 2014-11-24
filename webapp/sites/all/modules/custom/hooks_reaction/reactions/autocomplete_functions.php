<?php

/**
 *  Outputs the suggestions for the given input.
 *  @param $string
 *  @return the matching words
**/
function get_autocomplete_results(){

    $search_term = _get_autocomplete_search_term();
    $matches = array();
    $content_types = array('program' => 'Programs', 'services' => 'Services',
                         'tools' => 'Tools','event' => 'Events', 'article' => 'Articles',
                         'appointment_office' => 'Resource Centers');
    $title_array = array();
    $agency_array = array();
    $wizard_array = array();

    if (!empty($search_term)) {

        $title_query = "select/?q=autocomplete_label:".$search_term .
                       "&fq=bundle:(program%20OR%20services%20OR%20tools%20OR%20event%20OR%20article%20OR%20appointment_office)" .
                       "&rows=15&wt=php";
        $title_contents = autocomplete_solr_results($title_query);
        if($title_contents['response']['numFound'] > 0){
            foreach($title_contents['response']['docs'] as $a => $b){
                if(array_key_exists($b['bundle'],$content_types))
                    $title_array[$b['bundle']][$b['id']] = $b['label'];
            }
        }

        $agency_query = "select/?q=autocomplete_ts_joined_agency:". $search_term . "&rows=0&facet=true&facet.field=ac_facet_ts_joined_agency&facet.limit=15&wt=php";
        $agency_contents = autocomplete_solr_results($agency_query);
        if($agency_contents['response']['numFound'] > 0){
            foreach($agency_contents['facet_counts']['facet_fields']['ac_facet_ts_joined_agency'] as $a => $b){
                if($b > 0){
                    array_push($agency_array, $a);
                }
            }
        }

        /*$wizard_query = "select?q=ts_wizard_indexable_text:".autocomplete_escapeSolrValue(htmlspecialchars_decode($_REQUEST['term']), ENT_QUOTES)."*"
                        ."&fq=ss_is_wizard_zero_one:1&rows=0"
                        ."&facet.limit=3&facet=true&facet.field=url&wt=php";*/

        $wizard_query = "select?q=ts_wizard_indexable_text:".autocomplete_escapeSolrValue(htmlspecialchars_decode($_REQUEST['term']), ENT_QUOTES)."*"
                                ."&fq=ss_is_wizard_zero_one:1&rows=3"
                                ."&fq=bundle:swim_lane_page&wt=php";

        $wizard_contents = autocomplete_solr_results($wizard_query);
        if($wizard_contents['response']['numFound'] > 0){
            foreach($wizard_contents['response']['docs'] as $a => $b){
                $full_node = node_load($b['entity_id']);
                $wizard_swimlane_link = ( !empty($full_node->field_swimlane_wizurl['und'][0]['value']) ? $full_node->field_swimlane_wizurl['und'][0]['value'] : '');
                $wizard_array[$wizard_swimlane_link] = $b['label'];
            }
        }
        
        $matches = array('content_types' => $content_types,
                         'title_results' => $title_array,
                         'agency_results' => $agency_array,
                         'wizard_results' => $wizard_array);
    }
       return $matches;

}



/**
* Get search term from $_REQUEST.
*
* @return string
*   Search term
*/
function _get_autocomplete_search_term() {
    if (strlen($_REQUEST['term']) > 0) {
        $autocomplete_text = trim(htmlspecialchars_decode($_REQUEST['term'], ENT_QUOTES));
        $autocomplete_terms = explode(' ', $autocomplete_text);
        $autocomplete_text = autocomplete_escapeSolrValue(trim(htmlspecialchars_decode($_REQUEST['term']), ENT_QUOTES));

        if (count($autocomplete_terms) > 0) {
          $autocomplete_text = "";
          foreach ($autocomplete_terms as $key => $value) {
            if (strlen($value) > 0) {
              $autocomplete_text .= autocomplete_escapeSolrValue($value) . "*%20AND%20";
            }
          }
          $search_term = "(" . substr($autocomplete_text, 0, -9) . ")";
        }
        else {
          $search_term = $autocomplete_text . "*";
        }

        return $search_term;
    }
    else {
        return '';
    }
}

/**
* Replaces and encodes the special characters in the given string.
*
* @param string $string
*   String that needs escaping
*
* @return string
*   Escaped string
*/
function autocomplete_escapeSolrValue($string) {

    $string = str_replace("%", "%25", $string);
    $string = str_replace("\\", "%5C%5C", $string);
    $string = str_replace(" ", "%20", $string);
    $string = str_replace("#", "%23", $string);
    $string = str_replace("/", "\%2F", $string);
    $string = str_replace("&", "%26", $string);
    $string = str_replace("[", "\[", $string);
    $string = str_replace("]", "\]", $string);
    $string = str_replace(":", "\:", $string);
    $string = str_replace("'", "%27", $string);
    $string = str_replace("+", "\%2B", $string);
    $string = str_replace("?", "\%3F", $string);
    $string = str_replace("$", "\%24", $string);
    $string = str_replace("!", "\%21", $string);
    $string = str_replace("^", "\%5E", $string);
    $string = str_replace("|", "\%7C", $string);
    $string = str_replace("(", "\%28", $string);
    $string = str_replace(")", "\%29", $string);
    $string = str_replace("*", "\%2A", $string);
    $string = str_replace(".", "\%2E", $string);
    $string = str_replace("-", "\-", $string);

    return $string;
}

/**
* Outputs the solr query result for the input query.
*
* @param string $query
*   Partially constructed query.
* @param string|null $facet
*   Facet name to get from results.
*
* @return array
*   Auto suggestions from Solr.
*/
function autocomplete_solr_results($query) {
    $results = db_query("select url from apachesolr_environment");
    foreach ($results as $result) {
        $solr_server_url = $result->url;
    }
    $url = $solr_server_url .'/' . $query;
    $contents = file_get_contents($url);
    $contents = utf8_encode("$contents");
    if ($contents) {
        eval("\$results = " . $contents . ";");
    }
    return $results;
}
?>
