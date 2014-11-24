<?php
/**
 * @file
 * rei_editor_toolbars.features.user_permission.inc
 */

/**
 * Implements hook_user_default_permissions().
 */
function rei_editor_toolbars_user_default_permissions() {
  $permissions = array();

  // Exported permission: 'access dashboard'.
  $permissions['access dashboard'] = array(
    'name' => 'access dashboard',
    'roles' => array(
      'Editor' => 'Editor',
      'administrator' => 'administrator',
    ),
    'module' => 'dashboard',
  );

  // Exported permission: 'access site reports'.
  $permissions['access site reports'] = array(
    'name' => 'access site reports',
    'roles' => array(
      'Editor' => 'Editor',
      'administrator' => 'administrator',
    ),
    'module' => 'system',
  );

  return $permissions;
}
