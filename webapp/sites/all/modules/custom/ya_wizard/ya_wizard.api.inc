<?php

/**
 * @file
 */

/**
 * Callback for exporting wizard results.
 *
 * @param $wizard_name
 *   The name of the wizard to export. This will be the path for the wizard.
 * @param $format
 *   Either xml, json, csv.
 *
 * @return string
 *   The results from the wizard in the requested format.
 */
function ya_wizard_api_export($wizard_name, $format) {
  $results = array();
  $path = _ya_wizard_result_path($wizard_name);
  if (!empty($path)) {
    $results = _ya_wizard_load_results($wizard_name);
  }
  else {
    // TODO: Flesh out requirements around missing wizards.
    print t('No wizard found.');
    drupal_exit();
  }

  $output = call_user_func('ya_wizard_api_export_' . $format, $results);
  print $output;
  drupal_exit();
}

/**
 *  Tests if a wizard has a valid result path.
 *
 * @return string
 *   The result path for a wizard if valid.
 */
function _ya_wizard_result_path($wizard_name) {
  $result_path = 'sites/all/pages/';

  $known_result_paths = array(
    'access-financing'    => $result_path . 'access-financing',
    'begin-exporting'     => $result_path . 'begin-exporting',
    'disaster-assistance' => $result_path . 'disaster-assistance',
    'expand-exporting'    => $result_path . 'expand-exporting',
    'find-opportunities'  => $result_path . 'find-opportunities',
    'healthcare'          => $result_path . 'healthcare',
    'jobcenter-wizard'    => $result_path . 'jobcenter-wizard', // This is implemented, but not rigorously validated to work correctly.
    'rural-exporting'     => $result_path . 'rural-exporting', // This is implemented, but not validated and possibly not needed.
    'select-usa'          => $result_path . 'select-usa',
    'start-a-business'    => $result_path . 'start-a-business', // This is implemented, but not rigorously validated to work correctly.
    'taxes-and-credits'   => $result_path . 'taxes-and-credits',
    'veterans'            => $result_path . 'veterans',
  );

  $path = !empty($known_result_paths[$wizard_name]) ? $known_result_paths[$wizard_name] : FALSE;
  return $path;
}

/**
 * Load results from source.
 *
 * @param $path
 * @return array
 */
function _ya_wizard_load_results($wizard_name) {
  $results = array();

  if (!empty($_POST)) {
    // Submit results fetch answers

		ob_start();

		$api_results = array();
		require _ya_wizard_result_path($wizard_name) . '/wizard-results.page.php';
		ob_end_clean();
		
		$raw_results = $api_results;

  }
  else {
    // Get all results
    $raw_results = ya_wizard_excelToArray(_ya_wizard_result_path($wizard_name) . '/wizard-results.xls');
  }

  foreach ($raw_results as $result) {
    $results[] = $result['assoc'];
  }
  return $results;
}

/**
 * Output results as csv.
 *
 * @param $results
 */
function ya_wizard_api_export_csv($results) {
  ob_clean();
  $out = fopen('php://output', 'w');
  header( 'Content-Type: text/csv' );
  header( 'Content-Disposition: inline');
  //   Print the results
  $header_done = FALSE;
  foreach ($results as $result) {
    if (!$header_done) {
      $header = array_keys($result);
      fputcsv($out, $header);
      $header_done = TRUE;
    }
    fputcsv($out, $result);
  }
  fclose($out);
}

/**
 * Output results as json.
 *
 * @param $results
 */
function ya_wizard_api_export_json($results) {
  ob_clean();
  drupal_json_output($results);
}

/**
 * Output results as json.
 *
 * @param $results
 */
function ya_wizard_api_export_xml($results) {
  $xml_elements['results'] = array();
  foreach ($results as $result) {
    $xml_element_row = array('key' => 'row', 'value' => array());
    $xml_element_fields = array();
    foreach ($result as $field_key => $field) {
      if (!empty($field_key)) {
        if ($field === NULL) {
          $field = '';
        }
        $xml_element_fields[$field_key] = array('value' => $field);
      }
    }
    $xml_element_row['value'] = $xml_element_fields;

    $xml_elements['results'][] = $xml_element_row;
  }

  ob_clean();
  $xmlstr = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
  $xmlstr .= format_xml_elements($xml_elements);
  return $xmlstr;
}

/**
 * Returns the results of three views based on the values
 * @param $resource
 * @param $zip
 * @param $format
 */
function ya_wizard_api_resource($zipcode) {
  $results = array(
    'events' => array(),
    'resource_centers' => array(),
    'state_resources' => array(),
  );
  $resouce_master_view = 'front_page_resource_and_events_views';
  $lat_long_resources = array('front_page_resource_centers', 'front_page_events');
  $state_resources = array('front_page_state_resources');

  if(!$zipcode){
    $zipcode = "22070";//for testing, i think we can get this from myusa
  }

  $loc_info = getLatLongFromZipCode($zipcode);
  $lat_long_arguments = array($loc_info['lat'],$loc_info['lng']);
  $stateresource_params = array(acronymToStateName($loc_info['state']));

  $results['events']            = _ya_wizard_resource_view_result($resouce_master_view, 'front_page_events', $lat_long_arguments);
  $results['resource_centers']  = _ya_wizard_resource_view_result($resouce_master_view, 'front_page_events', $lat_long_arguments);
  $results['state_resources']   = _ya_wizard_resource_view_result($resouce_master_view, 'front_page_events', $stateresource_params);

  ya_wizard_api_export_json($results);
}

/**
 * Helper function to get results from front_page_resource_and_events_views.
 */
function _ya_wizard_resource_view_result($resouce_master_view, $display, $arguments, $page = 0) {
  $resource_view = views_get_view($resouce_master_view);
  $resource_view->set_display($display);
  $resource_view->set_arguments($arguments);
  $resource_view->set_current_page($page);
  $resource_view->pre_execute();
  $resource_view->execute();
  return $resource_view->result;
}
