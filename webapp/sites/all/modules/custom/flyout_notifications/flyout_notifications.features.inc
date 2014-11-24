<?php
/**
 * @file
 * flyout_notifications.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function flyout_notifications_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_node_info().
 */
function flyout_notifications_node_info() {
  $items = array(
    'recommended_flyout' => array(
      'name' => t('Recommended Flyout'),
      'base' => 'node_content',
      'description' => t('Create and add a flyout to a page to promote another page. A SelectUSA event will display in a side pop up while the user is viewing a page related to that event.'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
