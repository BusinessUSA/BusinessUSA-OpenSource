<?php
/**
 * @file
 * rei_editor_toolbars.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function rei_editor_toolbars_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}
