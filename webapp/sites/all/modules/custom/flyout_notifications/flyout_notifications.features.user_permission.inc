<?php
/**
 * @file
 * flyout_notifications.features.user_permission.inc
 */

/**
 * Implements hook_user_default_permissions().
 */
function flyout_notifications_user_default_permissions() {
  $permissions = array();

  // Exported permission: 'access efq javascript callbacks'.
  $permissions['access efq javascript callbacks'] = array(
    'name' => 'access efq javascript callbacks',
    'roles' => array(
      'administrator' => 'administrator',
      'anonymous user' => 'anonymous user',
      'authenticated user' => 'authenticated user',
    ),
    'module' => 'entity_js',
  );

  // Exported permission: 'node entity js read'.
  $permissions['node entity js read'] = array(
    'name' => 'node entity js read',
    'roles' => array(
      'administrator' => 'administrator',
      'anonymous user' => 'anonymous user',
      'authenticated user' => 'authenticated user',
    ),
    'module' => 'entity_js',
  );

  // Exported permission: 'node entity js read recommended_flyout'.
  $permissions['node entity js read recommended_flyout'] = array(
    'name' => 'node entity js read recommended_flyout',
    'roles' => array(
      'administrator' => 'administrator',
      'anonymous user' => 'anonymous user',
      'authenticated user' => 'authenticated user',
    ),
    'module' => 'entity_js',
  );

  return $permissions;
}
