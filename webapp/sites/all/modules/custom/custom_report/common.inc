<?php

function getFeedsImporters(){
    $feeds = array(
        'USCIS_ I9feedImporter',
        'USCIS_E-VerifyUser',
        'USCIS_EmpRights',
        'USCIS_FedContFeedImporter',
        'USCIS_SelfChkfeedImporter',
        'USCIS_SpanishEmpRigfeedImporter',
        'USCIS_SpanishI9feedImporter',
        'sys_sba_events_importer ',
        'sys_newexportgov_importer',
        'USCIS_overviewUser',
        'sys_export_event_importer',
        'sys_exim_impoter',
        'MRL_FeedImporter',
        'tradelead_feedimporter','anonymous');

    return $feeds;
}


function getOrganizationName($field) {
    $results = db_query("SELECT a.data FROM {field_config} a WHERE a.field_name = '" . $field . "'")->fetchAll();
    foreach ($results as $serializedData) {
        $unSerializedData[] = unserialize($serializedData->data);
    }
    $agencyNameList = $unSerializedData['0']['settings']['allowed_values'];
    foreach ($agencyNameList as $key => $value) {
        $orgName[] = $key;
    }
    return $orgName;
}

function getAgencyName($field) {
    $results = db_query("SELECT a.data FROM {field_config} a WHERE a.field_name = '". $field . "'")->fetchAll();
    foreach ($results as $serializedData) {
        $unSerializedData[] = unserialize($serializedData->data);
    }
    $agencyNameList = $unSerializedData['0']['settings']['allowed_values'];
    foreach ($agencyNameList as $key => $value) {
        $agencyName[] = $key;
    }
    return $agencyName;
}

/**
 * Query to fetch all nodes that were manually entered based on the node type and status
 */
function get_manual_entry_users_mysql($type, $status) {
    $results = db_query("
  SELECT COUNT(n.nid) AS node_count, u.name AS user_name
  FROM {node} n
  LEFT JOIN {users} u ON n.uid = u.uid
  WHERE (n.type = '". $type ."' AND n.status = " . $status . " AND (u.name NOT IN (:getFeedsImporters) AND n.uid != 0))
  GROUP BY u.name
  ORDER BY u.name ASC", array(':getFeedsImporters' => getFeedsImporters()))->fetchAll();
    return $results;
}

/**
 * Query to fetch all active nodes that were feed imported based on the node type
 */
function get_feed_importers_name_mysql($type, $status) {
    $results = db_query("
  SELECT COUNT(n.nid) AS node_count, if (u.name is null or u.name = '', 'Anonymous', u.name) AS user_name
  FROM {node} n
  LEFT JOIN {users} u ON n.uid = u.uid
  WHERE (n.type = '". $type ."' AND n.status = " . $status . " AND ((u.name IN (:getFeedsImporters) OR n.uid = 0 or u.name is null)))
  GROUP BY user_name
  ORDER BY user_name ASC", array(':getFeedsImporters' => getFeedsImporters()))->fetchAll();
    return $results;
}

function get_expired_count_mysql($type, $status = 0) {
    $results = db_query("
  SELECT COUNT(n.nid) AS count
  FROM {node} n
  WHERE n.type = '". $type ."' AND n.status = :status", array(':status' => $status))->fetchAll();
    return $results;
}

function get_current_count_mysql($type, $status = 1) {
    $results = db_query("
  SELECT COUNT(n.nid) AS count
  FROM {node} n
  WHERE n.type = '". $type ."' AND n.status = :status", array(':status' => $status))->fetchAll();
    return $results;
}