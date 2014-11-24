<?php

require_once DRUPAL_ROOT . '/sites/all/modules/custom/custom_report/common.inc';
/**
 * Query to fetch all expired and unpublished events as of now
 */
function get_expired_event_count_mysql($status = 0) {  
  $results = db_query("
  SELECT COUNT(n.nid) AS count 
  FROM {node} n 
  LEFT JOIN {field_data_field_event_date} ed ON n.nid = ed.entity_id AND (ed.deleted = 0) 
  WHERE n.type = 'event' AND n.status = :status OR (ed.field_event_date_value2 IS NOT NULL AND ed.field_event_date_value2 < :now)", array(':status' => $status, ':now' => date('Y-m-d H:i:s')))->fetchAll();
  return $results;
}

/**
 * Query to fetch all current published events as of now and future
 */
function get_current_event_count_mysql($status = 1) {
  $results = db_query("
  SELECT COUNT(n.nid) AS count 
  FROM {node} n 
  LEFT JOIN {field_data_field_event_date} ed ON n.nid = ed.entity_id AND ed.deleted = 0 
  WHERE n.type = 'event' AND n.status = :status AND (ed.field_event_date_value2 IS NULL OR ed.field_event_date_value2 >= :now)", array(':status' => $status, ':now' => date('Y-m-d H:i:s')))->fetchAll();
  return $results;
}

/*
 * Query to fetch events by event type
 */
function get_expired_event_count_by_type_mysql($status = 0) {
  $results = db_query("
  SELECT COUNT(n.nid) AS node_count, et.field_event_type_value AS event_type 
  FROM {node} n 
  LEFT JOIN {field_data_field_event_type} et ON n.nid = et.entity_id AND et.deleted = 0
  LEFT JOIN {field_data_field_event_date} ed ON n.nid = ed.entity_id AND (ed.deleted = 0)
  WHERE n.type = 'event' AND n.status = :status OR (ed.field_event_date_value2 IS NOT NULL AND ed.field_event_date_value2 < :now)
  AND ((et.field_event_type_value IN (:type)) OR (et.field_event_type_value is null) )
  GROUP BY event_type 
  ORDER BY event_type ASC", array(':type' => get_event_type_name(),':status' => $status, ':now' => date('Y-m-d H:i:s')))->fetchAll();
  return $results;
}

function get_current_event_count_by_type_mysql($status = 1) {
    $results = db_query("
  SELECT COUNT(n.nid) AS node_count, et.field_event_type_value AS event_type
  FROM {node} n
  LEFT JOIN {field_data_field_event_type} et ON n.nid = et.entity_id AND et.deleted = 0
  LEFT JOIN {field_data_field_event_date} ed ON n.nid = ed.entity_id AND (ed.deleted = 0)
  WHERE n.type = 'event' AND n.status = :status AND (ed.field_event_date_value2 IS NULL OR ed.field_event_date_value2 >= :now)
  AND ((et.field_event_type_value IN (:type)) OR (et.field_event_type_value is null) )
  GROUP BY event_type
  ORDER BY event_type ASC", array(':type' => get_event_type_name(),':status' => $status, ':now' => date('Y-m-d H:i:s')))->fetchAll();
    return $results;
}

/*
 * Query to fetch all events by organization that owns the program
 */
function get_expired_event_count_by_agency_mysql($status = 0) {
  $results = db_query("
  SELECT COUNT(n.nid) AS node_count, po.field_program_org_tht_owns_prog_value AS agency_name
  FROM {node} n 
  LEFT JOIN {field_data_field_program_org_tht_owns_prog} po ON n.nid = po.entity_id
  LEFT JOIN {field_data_field_event_date} ed ON n.nid = ed.entity_id AND (ed.deleted = 0)
  WHERE n.type = 'event' AND n.status = :status OR (ed.field_event_date_value2 IS NOT NULL AND ed.field_event_date_value2 < :now)
  GROUP BY agency_name 
  ORDER BY agency_name ASC", array(':status' => $status, ':now' => date('Y-m-d H:i:s')))->fetchAll();
  return $results;
}

function get_current_event_count_by_agency_mysql($status = 1) {
    $results = db_query("
  SELECT COUNT(n.nid) AS node_count, po.field_program_org_tht_owns_prog_value AS agency_name
  FROM {node} n
  LEFT JOIN {field_data_field_program_org_tht_owns_prog} po ON n.nid = po.entity_id
  LEFT JOIN {field_data_field_event_date} ed ON n.nid = ed.entity_id AND (ed.deleted = 0)
  WHERE n.type = 'event' AND n.status = :status AND (ed.field_event_date_value2 IS NULL OR ed.field_event_date_value2 >= :now)
  GROUP BY agency_name
  ORDER BY agency_name ASC", array(':status' => $status, ':now' => date('Y-m-d H:i:s')))->fetchAll();
    return $results;
}

function get_expired_event_manual_entry_users_mysql($status = 0) {
    $results = db_query("
  SELECT COUNT(n.nid) AS node_count, u.name AS user_name
  FROM {node} n
  LEFT JOIN {users} u ON n.uid = u.uid
  LEFT JOIN field_data_field_event_date ed ON n.nid = ed.entity_id AND (ed.deleted = 0)
  WHERE ((n.type = 'event' AND n.status = " . $status . ")  OR (ed.field_event_date_value2 IS NOT NULL AND ed.field_event_date_value2 < :now)) AND u.name NOT IN (:getFeedsImporters) AND n.uid != 0
  GROUP BY u.name
  ORDER BY u.name ASC", array(':now' => date('Y-m-d H:i:s'), ':getFeedsImporters' => getFeedsImporters()))->fetchAll();
    return $results;
}

function get_current_event_manual_entry_users_mysql($status = 1) {
    $results = db_query("
  SELECT COUNT(n.nid) AS node_count, u.name AS user_name
  FROM {node} n
  LEFT JOIN {users} u ON n.uid = u.uid
  LEFT JOIN field_data_field_event_date ed ON n.nid = ed.entity_id AND (ed.deleted = 0)
  WHERE ((n.type = 'event' AND n.status = " . $status . ") AND (ed.field_event_date_value2 IS NULL OR ed.field_event_date_value2 >= :now)) AND u.name NOT IN (:getFeedsImporters) AND n.uid != 0
  GROUP BY u.name
  ORDER BY u.name ASC", array(':now' => date('Y-m-d H:i:s'), ':getFeedsImporters' => getFeedsImporters()))->fetchAll();
    return $results;
}

function get_expired_event_feed_importers_name_mysql($status = 0) {
    $results = db_query("
  SELECT COUNT(n.nid) AS node_count, u.name AS user_name
  FROM {node} n
  LEFT JOIN {users} u ON n.uid = u.uid
  LEFT JOIN field_data_field_event_date ed ON n.nid = ed.entity_id AND (ed.deleted = 0)
  WHERE ((n.type = 'event' AND n.status = " . $status . ") OR (ed.field_event_date_value2 IS NOT NULL AND ed.field_event_date_value2 < :now) ) AND u.name IN (:getFeedsImporters)
  GROUP BY u.name
  ORDER BY u.name ASC", array(':now' => date('Y-m-d H:i:s'), ':getFeedsImporters' => getFeedsImporters()))->fetchAll();
    return $results;
}

function get_current_event_feed_importers_name_mysql($status = 1) {
    $results = db_query("
  SELECT COUNT(n.nid) AS node_count, u.name AS user_name
  FROM {node} n
  LEFT JOIN {users} u ON n.uid = u.uid
  LEFT JOIN field_data_field_event_date ed ON n.nid = ed.entity_id AND (ed.deleted = 0)
  WHERE ((n.type = 'event' AND n.status = " . $status . ") AND (ed.field_event_date_value2 IS NULL OR ed.field_event_date_value2 >= :now) ) AND u.name IN (:getFeedsImporters)
  GROUP BY u.name
  union all
  SELECT COUNT(n.nid) AS node_count, u.name AS user_name
  FROM node n
  LEFT JOIN users u ON n.uid = u.uid
  LEFT JOIN field_data_field_event_date ed ON n.nid = ed.entity_id AND (ed.deleted = 0)
  WHERE ((n.type = 'event' AND n.status = 1 ) AND (ed.field_event_date_value2 IS NULL OR ed.field_event_date_value2 >= :now))and n.uid = 0
  ORDER BY user_name ASC", array(':now' => date('Y-m-d H:i:s'), ':getFeedsImporters' => getFeedsImporters()))->fetchAll();
    return $results;
}

/**
 * Query to fetch details of all current and future events
 */
function current_events_detail_list_mysql() {
  $query = db_select('node', 'n');
  $query->leftjoin('users', 'u', 'n.uid = u.uid');
  $query->leftjoin('field_data_field_event_date', 'ed', 'n.nid = ed.entity_id');
  $query->leftjoin('field_data_field_event_type', 'et', 'n.nid = et.entity_id');
  $query->leftjoin('field_data_field_program_org_tht_owns_prog', 'po', 'n.nid = po.entity_id');
  $query->leftjoin('field_data_field_event_detail_desc', 'dd', 'n.nid = dd.entity_id');
  $query->leftjoin('field_data_field_event_address_1', 'address_1', 'n.nid = address_1.entity_id');
  $query->leftjoin('field_data_field_event_address_2', 'address_2', 'n.nid = address_2.entity_id');
  $query->leftjoin('field_data_field_event_city', 'city', 'n.nid = city.entity_id');
  $query->leftjoin('field_data_field_event_state', 'state', 'n.nid = state.entity_id');
  $query->leftjoin('field_data_field_event_zip', 'zip', 'n.nid = zip.entity_id');
  $query->leftjoin('field_data_field_event_country', 'country', 'n.nid = country.entity_id');
  $query->leftjoin('field_data_field_event_url', 'url', 'n.nid = url.entity_id');
  $query->fields('n', array('nid', 'title'))
        ->fields('u', array('name'))
        ->fields('et', array('field_event_type_value'))
        ->fields('po', array('field_program_org_tht_owns_prog_value'))
        ->fields('ed', array('field_event_date_value', 'field_event_date_value2'))
        ->fields('dd', array('field_event_detail_desc_value'))
        ->fields('address_1', array('field_event_address_1_value'))
        ->fields('address_2', array('field_event_address_2_value'))
        ->fields('city', array('field_event_city_value'))
        ->fields('state', array('field_event_state_value'))
        ->fields('zip', array('field_event_zip_value'))
        ->fields('country', array('field_event_country_value'))
        ->fields('url', array('field_event_url_url'))
        ->condition('n.type', 'event', '=')
        // ->condition('ed.bundle', 'event', '=')
        // ->condition('po.bundle', 'event', '=')
        ->condition(db_and()
           ->condition(db_or()
             //->condition('ed.field_event_date_value', date('Y-m-d H:i:s'), '>=')
             ->condition('ed.field_event_date_value2', date('Y-m-d H:i:s'), '>=')
             ->isNull('ed.field_event_date_value2')
            )
            ->condition(('n.status'), '1', '=')
          );
        //->condition('et.field_event_type_value', get_event_type_name(), 'IN')
        //->condition('po.field_program_org_tht_owns_prog_value', getAgencyName(), 'IN');
  $results = $query->execute()->fetchAll();
  return $results;
}

/**
 * Returns an array of all event types
 */
function get_event_type_name() {
  $results = db_query("SELECT data FROM {field_config} WHERE field_name = 'field_event_type'")->fetchAll();
  foreach ($results as $serializedData) {
    $unSerializedData[] = unserialize($serializedData->data);
  }
  $eventTypeList = $unSerializedData['0']['settings']['allowed_values'];
  foreach ($eventTypeList as $key => $value) {
    $type[] = $key;
  }
  return $type;
}


