<?php

/**
 * progrmas mysql queries
 */
 function _resource_center_mysql() {
  $query = db_select('node', 'n');
  $query->leftjoin('field_data_field_appoffice_phone', 'phone', 'n.nid = phone.entity_id');
  $query->leftjoin('field_data_field_appoffice_desc', 'd', 'n.nid = d.entity_id');
  $query->leftJoin('field_data_field_appoffice_link', 'fl', 'n.nid = fl.entity_id');
  $query->leftJoin('field_data_field_appoffice_email', 'em', 'n.nid = em.entity_id');
  $query->leftJoin('field_data_field_appoffice_loc_name', 'ln', 'n.nid = ln.entity_id');
  $query->leftJoin('field_data_field_address_line_1', 'ad', 'n.nid = ad.entity_id');
  $query->leftJoin('field_data_field_appoffice_address', 'address', 'n.nid = address.entity_id');
  $query->leftJoin('field_data_field_appoffice_city', 'c', 'n.nid = c.entity_id');
  $query->leftJoin('field_data_field_appoffice_state', 's', 'n.nid = s.entity_id');
  $query->leftJoin('field_data_field_appoffice_postal_code', 'pc', 'n.nid = pc.entity_id');
  $query->leftJoin('field_data_field_appoffice_country', 'fc', 'n.nid = fc.entity_id');
  $query->fields('n', array('nid', 'title'))
        ->fields('phone', array('field_appoffice_phone_value'))
        ->fields('d', array('field_appoffice_desc_value'))
        ->fields('fl', array('field_appoffice_link_value'))
        ->fields('em', array('field_appoffice_email_value'))
        ->fields('ln', array('field_appoffice_loc_name_value'))
        ->fields('ad', array('field_address_line_1_value'))
        ->fields('address', array('field_appoffice_address_value'))
        ->fields('c', array('field_appoffice_city_value'))
        ->fields('s', array('field_appoffice_state_value'))
        ->fields('pc', array('field_appoffice_postal_code_value'))
        ->fields('fc', array('field_appoffice_country_value'))
        ->condition('n.type', 'appointment_office', '=')
        ->condition(('n.status'), '1', '=')
        ->range();
  $results = $query->execute()->fetchAll();
  return $results;
 }
 
 /*
 * Query to fetch events by event type
 */
function get_resource_center_count_by_type_mysql($status) {
  $results = db_query("
  SELECT COUNT(n.nid) AS node_count, if (ot.field_appoffice_type_value = '' or ot.field_appoffice_type_value is null, 'None', field_appoffice_type_value) AS office_type
  FROM {node} n 
  LEFT JOIN {field_data_field_appoffice_type} ot ON n.nid = ot.entity_id AND ot.deleted = 0 
  WHERE n.type = 'appointment_office' AND n.status = $status
  AND ((ot.field_appoffice_type_value IN (:type)) OR ot.field_appoffice_type_value is null or ot.field_appoffice_type_value = '')
  GROUP BY office_type
  ORDER BY office_type ASC", array(':type' => get_appoffice_type_name()))->fetchAll();
  return $results;
}

/**
 * Returns an array of all event types
 */
function get_appoffice_type_name() {
  $results = db_query("SELECT data FROM {field_config} WHERE field_name = 'field_appoffice_type'")->fetchAll();
  foreach ($results as $serializedData) {
    $unSerializedData[] = unserialize($serializedData->data);
  }
  $typeList = $unSerializedData['0']['settings']['allowed_values'];
  foreach ($typeList as $key => $value) {
    $type[] = $key;
  }
  return $type;
}