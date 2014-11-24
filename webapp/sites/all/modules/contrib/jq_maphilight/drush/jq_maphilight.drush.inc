<?php
/**
 * @file
 *   drush integration for maphilight, based on colorbox.drush.inc.
 */

/**
 * The maphilight plugin URI.
 */
define('MAPHILIGHT_DOWNLOAD_URI', 'http://plugins.jquery.com/files/maphilight-1.2.2-0-gb10818c.zip');
/**
 * Directory name used in downloaded archive, to be renamed to MAPHILIGHT_DIRECTORY
 */
define('MAPHILIGHT_ARCHIVE_DIR', 'kemayo-maphilight-6e5e173');
/**
 * The maphilight plugin storage directory.
 */
define('MAPHILIGHT_DIRECTORY', 'jquery.maphilight');

/**
 * Implements hook_drush_command().
 *
 * In this hook, you specify which commands your
 * drush module makes available, what it does and
 * description.
 *
 * Notice how this structure closely resembles how
 * you define menu hooks.
 *
 * See `drush topic docs-commands` for a list of recognized keys.
 *
 * @return
 *   An associative array describing your command(s).
 */
function jq_maphilight_drush_command() {
  $items = array();

  // the key in the $items array is the name of the command.
  $items['maphilight-plugin'] = array(
    'callback' => 'drush_jq_maphilight_download',
    'description' => dt("Downloads the Maphilight plugin."),
    'bootstrap' => DRUSH_BOOTSTRAP_DRUSH, // No bootstrap.
    'arguments' => array(
      'path' => dt('Optional. A path where to install the Maphilight plugin. If omitted Drush will use the default location.'),
    ),
    'aliases' => array('maphilightplugin'),
  );

  return $items;
}

/**
 * Implements hook_drush_help().
 *
 * This function is called whenever a drush user calls
 * 'drush help <name-of-your-command>'
 *
 * @param
 *   A string with the help section (prepend with 'drush:')
 *
 * @return
 *   A string with the help text for your command.
 */
function jq_maphilight_drush_help($section) {
  switch ($section) {
    case 'drush:maphilight-plugin':
      return dt("Downloads the Maphilight from plugins.jquery.com, default location is sites/all/libraries.");
  }
}

/**
 * Command to download the Maphilight plugin.
 */
function drush_jq_maphilight_download() {
  if (!drush_shell_exec('type unzip')) {
    return drush_set_error(dt('Missing dependency: unzip. Install it before using this command.'));
  }

  $args = func_get_args();
  if (isset($args[0])) {
    $path = $args[0];
  }
  else {
    $path = 'sites/all/libraries';
  }

  // Create the path if it does not exist.
  if (!is_dir($path)) {
    drush_op('mkdir', $path);
    drush_log(dt('Directory @path was created', array('@path' => $path)), 'notice');
  }

  // Set the directory to the download location.
  $olddir = getcwd();
  chdir($path);

  $filename = basename(MAPHILIGHT_DOWNLOAD_URI);

  // Remove any existing Maphilight plugin directory
  if (is_dir(MAPHILIGHT_DIRECTORY)) {
    drush_shell_exec('rm -rf ' . MAPHILIGHT_DIRECTORY);
    drush_log(dt('An existing Maphilight plugin was deleted from @path', array('@path' => $path)), 'notice');
  }
  // Remove any existing Maphilight plugin zip archive
  if (is_file($filename)) {
    drush_op('unlink', $filename);
  }

  // Download the zip archive
  if (!drush_shell_exec('wget ' . MAPHILIGHT_DOWNLOAD_URI)) {
    drush_shell_exec('curl -O ' . MAPHILIGHT_DOWNLOAD_URI);
  }

  if (is_file($filename)) {
    // Decompress the zip archive
    drush_shell_exec('unzip -qq -o ' . $filename);
    // Remove the zip archive
    drush_op('unlink', $filename);
    // Rename the uncompressed directory.
    drush_op('rename', MAPHILIGHT_ARCHIVE_DIR, MAPHILIGHT_DIRECTORY);
  }

  // Set working directory back to the previous working directory.
  chdir($olddir);

  if (is_dir($path . '/' . MAPHILIGHT_DIRECTORY)) {
    drush_log(dt('Maphilight plugin has been downloaded to @path', array('@path' => $path)), 'success');
  }
  else {
    drush_log(dt('Drush was unable to download the Maphilight plugin to @path', array('@path' => $path)), 'error');
  }
}
