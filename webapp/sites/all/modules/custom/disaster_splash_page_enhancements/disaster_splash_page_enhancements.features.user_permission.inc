<?php
/**
 * @file
 * disaster_splash_page_enhancements.features.user_permission.inc
 */

/**
 * Implements hook_user_default_permissions().
 */
function disaster_splash_page_enhancements_user_default_permissions() {
  $permissions = array();

  // Exported permission: 'administer disaster splash page enhancements'.
  $permissions['administer disaster splash page enhancements'] = array(
    'name' => 'administer disaster splash page enhancements',
    'roles' => array(
      'Editor' => 'Editor',
      'administrator' => 'administrator',
    ),
    'module' => 'disaster_splash_page_enhancements',
  );

  return $permissions;
}
