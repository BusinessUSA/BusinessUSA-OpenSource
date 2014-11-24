<?php
/**
 * @file
 * disaster_splash_page_enhancements.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function disaster_splash_page_enhancements_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "feeds" && $api == "feeds_importer_default") {
    return array("version" => "1");
  }
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_views_api().
 */
function disaster_splash_page_enhancements_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_node_info().
 */
function disaster_splash_page_enhancements_node_info() {
  $items = array(
    'disaster_splash_link' => array(
      'name' => t('Disaster Splash Link'),
      'base' => 'node_content',
      'description' => t('Links on the disaster splash page.'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
    'fema_disaster' => array(
      'name' => t('Fema Disaster'),
      'base' => 'node_content',
      'description' => t('Imported nodes from the fema disaster feed.'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  return $items;
}
