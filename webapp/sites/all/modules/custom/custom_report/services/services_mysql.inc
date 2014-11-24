<?php

/**
 * progrmas mysql queries
 */
 function _services_mysql() {
  $query = db_select('node', 'n');
  $query->leftjoin('field_data_field_services_detail_desc', 'detail_desc', 'n.nid = detail_desc.entity_id');
  $query->leftjoin('field_data_field_services_eligibility', 'eligibility', 'n.nid = eligibility.entity_id');
  $query->leftjoin('field_data_field_services_ext_url', 'ext_url', 'n.nid = ext_url.entity_id');
  $query->leftjoin('field_data_field_program_industry', 'industry', 'n.nid = industry.entity_id');
  $query->leftjoin('field_data_field_program_city', 'city', 'n.nid = city.entity_id');
  $query->leftjoin('field_data_field_program_owner_share', 'owner_share', 'n.nid = owner_share.entity_id');
  $query->leftjoin('field_data_field_services_purpose', 'purpose', 'n.nid = purpose.entity_id');
  $query->leftjoin('field_data_field_services_agency', 'agency', 'n.nid = agency.entity_id');  
  $query->leftjoin('field_data_field_services_office_in_org', 'office_in_org', 'n.nid = office_in_org.entity_id');
  $query->fields('n', array('nid', 'title'))
        ->fields('detail_desc', array('field_services_detail_desc_value'))
        ->fields('eligibility', array('field_services_eligibility_value'))
        ->fields('ext_url', array('field_services_ext_url_url'))
        ->fields('industry', array('field_program_industry_value'))
        ->fields('city', array('field_program_city_value'))
        ->fields('owner_share', array('field_program_owner_share_value'))
        ->fields('purpose', array('field_services_purpose_value'))
        ->fields('agency', array('field_services_agency_value'))
        ->fields('office_in_org', array('field_services_office_in_org_value'))
        ->condition('n.type', 'services', '=')
        ->condition(('n.status'), '1', '=')
        ->range();
  $results = $query->execute()->fetchAll();
  return $results;
 }

/*
 * Query to fetch all services by organization
 */
function get_service_count_by_organization_mysql($status) {
    $results = db_query("
  SELECT COUNT(n.nid) AS node_count, po.field_services_office_in_org_value AS org_name
  FROM {node} n
  LEFT JOIN {field_data_field_services_office_in_org} po ON n.nid = po.entity_id
  WHERE n.type = 'services' AND n.status = $status
  AND ((po.field_services_office_in_org_value IN (:getOrganizationName)) OR (po.field_services_office_in_org_value is null))
  GROUP BY org_name
  ORDER BY org_name ASC", array(':getOrganizationName' => getOrganizationName('field_services_office_in_org')))->fetchAll();
    return $results;
}

/*
 * Query to fetch all services by agency
 */
function get_service_count_by_agency_mysql($status) {
    $results = db_query("
  SELECT COUNT(n.nid) AS node_count, po.field_services_agency_value AS agency_name
  FROM {node} n
  LEFT JOIN {field_data_field_services_agency} po ON n.nid = po.entity_id
  WHERE n.type = 'services' AND n.status = $status
  AND ((po.field_services_agency_value IN (:getAgencyName)) OR (po.field_services_agency_value is null))
  GROUP BY agency_name
  ORDER BY agency_name ASC", array(':getAgencyName' => getAgencyName('field_services_agency')))->fetchAll();
    return $results;
}
