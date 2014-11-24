<?php
/**
 * @file
 * disaster_splash_page_enhancements.feeds_importer_default.inc
 */

/**
 * Implements hook_feeds_importer_default().
 */
function disaster_splash_page_enhancements_feeds_importer_default() {
  $export = array();

  $feeds_importer = new stdClass();
  $feeds_importer->disabled = FALSE; /* Edit this to true to make a default feeds_importer disabled initially */
  $feeds_importer->api_version = 1;
  $feeds_importer->id = 'fema_disaster_declarations';
  $feeds_importer->config = array(
    'name' => 'Fema Disaster Declarations',
    'description' => 'RSS feed of U.S. disaster declarations',
    'fetcher' => array(
      'plugin_key' => 'FeedsHTTPFetcher',
      'config' => array(
        'auto_detect_feeds' => 0,
        'use_pubsubhubbub' => 0,
        'designated_hub' => '',
        'request_timeout' => '',
      ),
    ),
    'parser' => array(
      'plugin_key' => 'FeedsSyndicationParser',
      'config' => array(),
    ),
    'processor' => array(
      'plugin_key' => 'FeedsNodeProcessor',
      'config' => array(
        'expire' => '31536000',
        'author' => '1',
        'authorize' => 1,
        'mappings' => array(
          0 => array(
            'source' => 'title',
            'target' => 'title',
            'unique' => FALSE,
          ),
          1 => array(
            'source' => 'description',
            'target' => 'field_fema_disaster_description',
            'unique' => FALSE,
          ),
          2 => array(
            'source' => 'url',
            'target' => 'field_fema_disaster_link:url',
            'unique' => FALSE,
          ),
          3 => array(
            'source' => 'timestamp',
            'target' => 'field_fema_disaster_date_publish:start',
            'unique' => FALSE,
          ),
          4 => array(
            'source' => 'author_name',
            'target' => 'field_fema_disaster_reporter',
            'unique' => FALSE,
          ),
          5 => array(
            'source' => 'guid',
            'target' => 'guid',
            'unique' => 1,
          ),
        ),
        'update_existing' => '2',
        'input_format' => 'plain_text',
        'skip_hash_check' => 0,
        'bundle' => 'fema_disaster',
      ),
    ),
    'content_type' => '',
    'update' => 0,
    'import_period' => '0',
    'expire_period' => 3600,
    'import_on_create' => 1,
    'process_in_background' => 0,
  );
  $export['fema_disaster_declarations'] = $feeds_importer;

  return $export;
}
