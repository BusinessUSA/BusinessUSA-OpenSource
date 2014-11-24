<?php

require_once DRUPAL_ROOT . '/sites/all/modules/custom/custom_report/common.inc';

/**
 * progrmas mysql queries
 */
 function _programs_mysql() {
  $query = db_select('node', 'n');
  $query->leftjoin('users', 'u', 'n.uid = u.uid');
  $query->leftjoin('field_data_field_program_detail_desc', 'detail_desc', 'n.nid = detail_desc.entity_id');
  $query->leftjoin('field_data_field_program_org_tht_owns_prog', 'org_tht_owns_prog', 'n.nid = org_tht_owns_prog.entity_id');
  $query->leftjoin('field_data_field_program_eligibility', 'eligibility', 'n.nid = eligibility.entity_id');
  $query->leftjoin('field_data_field_program_ext_url', 'ext_url', 'n.nid = ext_url.entity_id');
  $query->leftjoin('field_data_field_program_industry', 'industry', 'n.nid = industry.entity_id');
  $query->leftjoin('field_data_field_program_city', 'city', 'n.nid = city.entity_id');
  $query->leftjoin('field_data_field_program_owner_share', 'ownership', 'n.nid = ownership.entity_id');
  $query->leftjoin('field_data_field_program_agency', 'agency', 'n.nid = agency.entity_id');
  $query->leftJoin('field_data_field_program_purpose', 'purpose', 'n.nid=purpose.entity_id');
  $query->fields('n', array('nid', 'title'))
        ->fields('u', array('name'))
        ->fields('detail_desc', array('field_program_detail_desc_value'))
        ->fields('org_tht_owns_prog', array('field_program_org_tht_owns_prog_value'))
        ->fields('eligibility', array('field_program_eligibility_value'))
        ->fields('ext_url', array('field_program_ext_url_url'))
        ->fields('industry', array('field_program_industry_value'))
        ->fields('city', array('field_program_city_value'))
        ->fields('ownership', array('field_program_owner_share_value'))
        ->fields('agency', array('field_program_agency_value'))
        ->fields('purpose', array('field_program_purpose_value'))
        ->condition('n.type', 'program', '=')
        ->condition(('n.status'), '1', '=')
         ->range();
  $results = $query->execute()->fetchAll();
  return $results;
 }

/*
 * Query to fetch all programs by organization
 */
function get_program_count_by_organization_mysql($status) {
    $results = db_query("
  SELECT COUNT(n.nid) AS node_count, if (po.field_program_org_tht_owns_prog_value is null or po.field_program_org_tht_owns_prog_value = '', 'None', po.field_program_org_tht_owns_prog_value) AS org_name
  FROM {node} n
  LEFT JOIN {field_data_field_program_org_tht_owns_prog} po ON n.nid = po.entity_id
  WHERE n.type = 'program' AND n.status = $status
  AND ((po.field_program_org_tht_owns_prog_value IN (:getOrganizationName)) OR po.field_program_org_tht_owns_prog_value is null OR po.field_program_org_tht_owns_prog_value = '')
  GROUP BY org_name
  ORDER BY org_name ASC", array(':getOrganizationName' => getOrganizationName('field_program_org_tht_owns_prog')))->fetchAll();
    return $results;
}

/*
 * Query to fetch all programs by agency
 */
function get_program_count_by_agency_mysql($status) {
  $results = db_query("
  SELECT COUNT(n.nid) AS node_count, if(po.field_program_agency_value is null or po.field_program_agency_value = '', 'None', po.field_program_agency_value) AS agency_name
  FROM {node} n 
  LEFT JOIN {field_data_field_program_agency} po ON n.nid = po.entity_id
  WHERE n.type = 'program' AND n.status = $status
  AND ((po.field_program_agency_value IN (:getAgencyName)) OR po.field_program_agency_value is null OR po.field_program_agency_value = '')
  GROUP BY agency_name 
  ORDER BY agency_name ASC", array(':getAgencyName' => getAgencyName('field_program_agency')))->fetchAll();
  return $results;
}

