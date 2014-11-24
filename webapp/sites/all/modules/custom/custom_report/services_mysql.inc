<?php

/**
 * progrmas mysql queries
 */
 function _services_mysql() {
  $query = db_select('node', 'n');
  //$query->leftjoin('users', 'u', 'n.uid = u.uid');
  $query->leftjoin('field_data_field_services_detail_desc', 'detail_desc', 'n.nid = detail_desc.entity_id');
  //$query->leftjoin('field_data_field_program_org_tht_owns_prog', 'org_tht_owns_prog', 'n.nid = org_tht_owns_prog.entity_id');
  // $query->leftjoin('field_data_field_program_public_poc_name', 'public_poc_name', 'n.nid = public_poc_name.entity_id');
  // $query->leftjoin('field_data_field_program_public_poc_org', 'public_poc_org', 'n.nid = public_poc_org.entity_id');
  // $query->leftjoin('field_data_field_program_public_poc_email', 'public_poc_email', 'n.nid = public_poc_email.entity_id');
  // $query->leftjoin('field_data_field_program_public_poc_org', 'public_poc_org', 'n.nid = public_poc_org.entity_id');
  // $query->leftjoin('field_data_field_program_public_poc_phone', 'public_poc_phone', 'n.nid = public_poc_phone.entity_id');
  $query->leftjoin('field_data_field_services_eligibility', 'eligibility', 'n.nid = eligibility.entity_id');
  $query->leftjoin('field_data_field_services_ext_url', 'ext_url', 'n.nid = ext_url.entity_id');
  $query->leftjoin('field_data_field_program_industry', 'industry', 'n.nid = industry.entity_id');
  // $query->leftjoin('field_data_field_program_export_country', 'export_country', 'n.nid = export_country.entity_id');
  // $query->leftjoin('field_data_field_program_hq_usa', 'hq_usa', 'n.nid = hq_usa.entity_id');
  // $query->leftjoin('field_data_field_program_hubzone', 'hubzone', 'n.nid = hubzone.entity_id');
  // $query->leftjoin('field_data_field_program_loc', 'loc', 'n.nid = loc.entity_id');
  $query->leftjoin('field_data_field_program_city', 'city', 'n.nid = city.entity_id');
  // $query->leftjoin('field_data_field_program_company_size', 'company_size', 'n.nid = company_size.entity_id');
  // $query->leftjoin('field_data_field_program_comp_maturity', 'comp_maturity', 'n.nid = comp_maturity.entity_id');
  // $query->leftjoin('field_data_field_program_exporting_maturity', 'exporting_maturity', 'n.nid = exporting_maturity.entity_id');
  // $query->leftjoin('field_data_field_program_needs', 'needs', 'n.nid = needs.entity_id');
  $query->leftjoin('field_data_field_program_owner', 'owner', 'n.nid = owner.entity_id');
  $query->leftjoin('field_data_field_program_owner_share', 'owner_share', 'n.nid = owner_share.entity_id');
  $query->leftjoin('field_data_field_services_purpose', 'purpose', 'n.nid = purpose.entity_id');
  $query->leftjoin('field_data_field_services_agency', 'agency', 'n.nid = agency.entity_id');  
  $query->leftjoin('field_data_field_services_office_in_org', 'office_in_org', 'n.nid = office_in_org.entity_id');  
  // $query->leftjoin('field_data_field_program_priv_poc_name', 'priv_poc_name', 'n.nid = priv_poc_name.entity_id');  
  // $query->leftjoin('field_data_field_program_priv_poc_email', 'priv_poc_email', 'n.nid = priv_poc_email.entity_id');  
  // $query->leftjoin('field_data_field_program_priv_poc_phone', 'priv_poc_phone', 'n.nid = priv_poc_phone.entity_id'); 
  // $query->leftjoin('field_data_field_program_priv_poc_org', 'priv_poc_org', 'n.nid = priv_poc_org.entity_id');
  // $query->leftjoin('field_data_field_program_effective_dt', 'publish_date', 'n.nid = publish_date.entity_id');
  // $query->leftjoin('field_data_field_program_post_end_dt', 'expire_date', 'n.nid = expire_date.entity_id');
  // $query->leftjoin('field_data_field_program_update_dt', 'last_updated', 'n.nid = last_updated.entity_id');
  $query->fields('n', array('nid', 'title'))
        //->fields('u', array('name'))
        ->fields('detail_desc', array('field_services_detail_desc_value'))
        //->fields('org_tht_owns_prog', array('field_program_org_tht_owns_prog_value'))
        ->fields('eligibility', array('field_services_eligibility_value'))
        // ->fields('public_poc_name', array('field_program_public_poc_name_value'))
        // ->fields('public_poc_org', array('field_program_public_poc_org_value'))
        // ->fields('public_poc_email', array('field_program_public_poc_email_value'))
        // ->fields('public_poc_phone', array('field_program_public_poc_phone_value'))
        ->fields('ext_url', array('field_services_ext_url_url'))
        ->fields('industry', array('field_program_industry_value'))
        // ->fields('export_country', array('field_program_export_country_value'))
        // ->fields('hq_usa', array('field_program_hq_usa_value'))
        // ->fields('hubzone', array('field_program_hubzone_value'))
        // ->fields('loc', array('field_program_loc_value'))
        ->fields('city', array('field_program_city_value'))
        // ->fields('company_size', array('field_program_company_size_value'))
        // ->fields('comp_maturity', array('field_program_comp_maturity_value'))
        // ->fields('exporting_maturity', array('field_program_exporting_maturity_value'))
        // ->fields('needs', array('field_program_needs_value'))
        // ->fields('owner', array('field_program_owner_value'))
        ->fields('owner_share', array('field_program_owner_share_value'))
        ->fields('purpose', array('field_services_purpose_value'))
        ->fields('agency', array('field_services_agency_value')) //
        ->fields('office_in_org', array('field_services_office_in_org_value'))
        // ->fields('priv_poc_name', array('field_program_priv_poc_name_value'))
        // ->fields('priv_poc_email', array('field_program_priv_poc_email_value'))
        // ->fields('priv_poc_phone', array('field_program_priv_poc_phone_value'))
        // ->fields('priv_poc_phone', array('field_program_priv_poc_phone_value'))
        // ->fields('priv_poc_org', array('field_program_priv_poc_org_value'))
        // ->fields('publish_date', array('field_program_effective_dt_value'))
        // ->fields('expire_date', array('field_program_post_end_dt_value'))
        // ->fields('last_updated', array('field_program_update_dt_value'))
        ->condition('n.type', 'services', '=')
        //->condition('org_tht_owns_prog.field_program_org_tht_owns_prog_value', getOrganizationName(), 'IN')
        //->condition('agency.field_program_agency_value', getAgencyName(), 'IN')
        // ->condition(db_and()
          // ->condition(db_or()
            // // ->condition('ed.field_event_date_value', date('Y-m-d H:i:s'), '>=')
            // // ->condition('ed.field_event_date_value2', date('Y-m-d H:i:s'), '>=')
            // // ->isNull('ed.field_event_date_value2')
           // )
         // )
         ->range();
  $results = $query->execute()->fetchAll();
  return $results;
 }

function getOrganizationName() {
  $sql = "SELECT DISTINCT org.field_program_org_tht_owns_prog_value FROM {field_data_field_program_org_tht_owns_prog} org WHERE (org.bundle = 'program' AND org.deleted = 0) ORDER BY org.field_program_org_tht_owns_prog_value ASC";
  $results = db_query($sql)->fetchAll();
  foreach ($results as $result) {
    $orgName[] = $result->field_program_org_tht_owns_prog_value;
  }
  return $orgName;
}

function getAgencyName() {
  $sql = "SELECT DISTINCT agency.field_program_agency_value FROM {field_data_field_program_agency} agency WHERE (agency.bundle = 'program' AND agency.deleted = 0) ORDER BY agency.field_program_agency_value ASC";
  $results = db_query($sql)->fetchAll();
  foreach ($results as $result) {
    $orgName[] = $result->field_program_agency_value;
  }
  return $orgName;
}
