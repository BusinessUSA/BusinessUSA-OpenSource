<?php

/**
 * Search API allows users to query for a page of results.
 *
 */

$solr = apachesolr_get_solr();
$solrsort = '';
$params = array();
$params['q'] = $_REQUEST['query'];
$params['fl'] = "entity_type,bundle,label,ds_created,path,url,teaser";

// Some solr defaults
$params['mm'] = array(1);
$params['pf'] = array('content^2.0');
$params['ps'] = array(15);
$params['hl'] = TRUE;
$params['hl.fl'] = 'content';
$params['hl.snippets'] = 3;
$params['hl.mergeContigious'] = array(TRUE);
$params['f.content.hl.alternateField'] = array('teaser');
$params['f.content.hl.maxAlternateFieldLength'] = array(256);
$params['qf'] = array (
  'content^40',
  'label^5.0',
  'tags_h1^5.0',
  'tags_h2_h3^3.0',
  'tags_h4_h5_h6^2.0',
  'tags_inline^1.0',
  'taxonomy_names^2.0',
  'tos_name^3.0',
);
$params['facet'] = TRUE;
$params['facet.sort'] = array('count');
$params['facet.mincount'] = 1;
$params['facet.field'] = array('{!ex=bundle}bundle');
$params['f.bundle.facet.limit'] = array(50);
$params['f.bundle.facet.mincount'] = array(1);
$params["start"] = 0;
$params["rows"] = 10;

$query = apachesolr_drupal_query('apachesolr', $params);
apachesolr_search_add_boost_params($query);

$response = $query->search();
$results = $response->response->docs;
if (!empty($_REQUEST['format']) && strcasecmp('csv', $_REQUEST['format']) !== FALSE ) {
  ob_clean();
  $out = fopen('php://output', 'w');
  header( 'Content-Type: text/csv' );
  header( 'Content-Disposition: inline');
  //   Print the results
  $header_done = FALSE;
  foreach ($results as $result_object) {
    $result = (array) $result_object;
    if (!$header_done) {
      $header = array_keys($result);
      fputcsv($out, $header);
      $header_done = TRUE;
    }
    fputcsv($out, $result);
  }
  fclose($out);
}
else {
  drupal_json_output($results);
}

